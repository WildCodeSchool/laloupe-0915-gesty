<?php

/*######################################################################################

This command import attachments files from a directory (of the previous version of the website)
and link them to the concerning users in the database by the means of the PostgreSQL file.

Note : the users must already exist in the database from a previous migration
with "gesty:db:migrate" command.

In details, the script performs the following operations :

- Move files from the web/.../uploads to app/uploads
- Clean up previous migration (only files with a name starting with the prefix 'mig_')
- Load all users found in the PostgreSQL file passed in argument of this command.
- Load users' homes data (in which all original filenames are present)
- Retrieve the files in the directory passed in argument of this command
- Copy all those files in the app/uploads directory and rename them properly
- Update the database with the appropriate filenames.

######################################################################################*/



namespace WCS\GestyBundle\Command;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\ORM\EntityManager;
use Sonata\UserBundle\Model\UserManagerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Question\Question;
use Application\Sonata\UserBundle\Entity\User;



class MigrateFilesCommand extends ContainerAwareCommand
{
    // prefix used in all the filenames that will be imported
    const filename_prefix           = 'mig_';

    // column index of table "multipass_users" from original SQL file
    const mu_id                     = 0;
    const mu_email                  = 1;

    // column index of table "multipass_homes" from original SQL file
    const mh_id                     = 0;
    const mh_firstname              = 1;
    const mh_lastname               = 2;
    const mh_phone                  = 3;
    const mh_phone_2                = 4;
    const mh_address_street         = 5;
    const mh_address_postcode       = 6;
    const mh_address_city           = 7;
    const mh_payment_method         = 8;
    const mh_gender                 = 9;
    const mh_caf                    = 10;
    const mh_user_id                = 11;
    const mh_address_evidence       = 16;
    const mh_address_evidence_2     = 17;
    const mh_caf_evidence           = 18;
    const mh_salary_evidence        = 19;
    const mh_salary_evidence_2      = 20;
    const mh_salary_evidence_3      = 21;
    const mh_salary_evidence_4      = 22;
    const mh_salary_evidence_5      = 23;
    const mh_salary_evidence_6      = 24;

    const path_application          = '/app';

    private $path_original          = '';
    private $path_target            = '';
    private $userManager            = null;
    private $sql_file               = '';
    private $stats                  = array(
                                        'nb_users_load' => 0,
                                        'nb_homes_load' => 0,
                                        'nb_files_unprocessed' => 0
                                        );
 

    /**
    * Configure the command
    * - set the command name, its description
    * - expected :
    *   - 1) Filename or path of the SQL file to import
    *   - 2) Path of the directory containing the attached files to import
    */
    protected function configure()
    {
            $this->setName('gesty:db:migratefiles')
                 ->setDescription('migrate version 1 attached files in the database')
                 ->addArgument(
                    'file',
                    InputArgument::OPTIONAL,
                    'postgres dump file to read data from'
                 )
                 ->addArgument(
                    'original_files_path',
                    InputArgument::OPTIONAL,
                    'path of the original attached files'
                 );
                 

    }



    /**
    * Execute the command
    *
    * @param InputInterface
    */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
            $this->path_target = User::getPathRootUpload();
            
            // IMPORTANT !!!! Respect the sequence ordering inside this method

            // will contains all users present in the SQL file and in the database
            $users = array();

            // get arguments : get the file name to import, get the path of the attached files
            $helper = $this->getHelper('question');

            $this->sql_file         = $input->getArgument('file');
            $this->path_original    = $input->getArgument('original_files_path');
            if (empty($this->sql_file)) {
                $q1 = new Question('Please enter the path of the SQL file :');
                $this->sql_file = $helper->ask( $input, $output, $q1 );
            }

            if (empty($this->path_original)) {
                $q2 = new Question('Please enter the path of the directory that contains the files to import :');
                $this->path_original = $helper->ask( $input, $output, $q2 );
            }

            if (!is_file($this->sql_file)) {
                $output->writeln(sprintf('  <comment>></comment> <error>SQL file not found. Path entered may be wrong. Try again.</error>'
                    ));
                return;
            }

            if (!is_dir($this->path_original)) {
                $output->writeln(sprintf('  <comment>></comment> <error>Directory with the files to import not found. Path entered may be wrong. Try again.</error>'
                    ));
                return;
            }

            // load Doctrine service
            $em = $this->getContainer()->get('doctrine')->getManager();

            // ask for confirmation before process
            if (!$this->askConfirmation($input, $output, '<question>Careful, all paths to attached files will be updated. Do you want to continue y/N ?</question>')) {
                return;
            }

            $stream = null;
            try {

                // 0 - Create the app/uploads folder if it does not exist.
                $output->writeln('  <comment>></comment> <info>0. Create app/uploads if missing.</info>');

                $this->createUploadsDir($output);
                
                // 1 - Migrate web upload files to the secure app upload files
                $output->writeln('  <comment>></comment> <info>1. Moving files from web/../uploads to app/uploads.</info>');
                
                $this->moveWebUploadFiles();

                // 2 - Delete any files copied from a previous import 
                //    (does delete moved web upload files )
                $output->writeln('  <comment>></comment> <info>2. Cleaning up any previous files migration.</info>');
                
                $this->deleteAnyPreviousImport();

                // 3 - import files
                $output->writeln('  <comment>></comment> <info>3. Importing files and updating users info.</info>');
                
                $stream = fopen($this->sql_file, 'r');
                if (!$stream) {
                    throw new \Exception("Failed to open the file : $file");
                }

                $this->loadUsers( $stream, $users, $output );
                $this->importUsersFiles( $stream, $users, $output );

                // 4. save all users updated data in the database
                $output->writeln('  <comment>></comment> <info>4. Updating the database.</info>');
                $em->flush();

                // display the stats
                $output->writeln('    <comment>></comment> <info>'.$this->stats["nb_users_load"].' users loaded</info>');
                $output->writeln('    <comment>></comment> <info>'.$this->stats["nb_homes_load"].' users updated</info>' );
                $output->writeln('  <info>done</info>');
            }
            catch(\Exception $e) {
                $output->writeln(sprintf('  <comment>></comment> <error>%s</error>', $e->getMessage()));
            }

            // 7. close the file
            if ($stream) {
                fclose($stream);
            }        
    }



    /**
    * Create the app/uploads directory
    */
    private function createUploadsDir(OutputInterface $output)
    {
            if (!is_writable(__DIR__.'/../../../..'.self::path_application)) {
                $output->writeln("  <comment>></comment> Please create the folder 'uploads' in app/ manually with the permissions for Apache then run this command again.");
                return;
            }

            if (is_dir($this->path_target)) {
                $output->writeln('  <comment>></comment> <info>   The folder already exists. OK.</info>');
                return;
            }

            if (!mkdir($this->path_target)) {
                throw new \Exception("Failed to create the app/uploads directory.\n
                    Please create the folder 'uploads' in app/ manually then run this command again.
                    ");
            }
            $output->writeln('  <comment>></comment> Don\'t forget to make this folder accessible for Apache, otherwise uploads from the website won\'t work.');
    }


    /**
    * Move all current files from web/.../upload to app/uploads
    */
    private function moveWebUploadFiles()
    {
            $path_web_upload =  __DIR__.'/../../../../web/bundles/wcscantine/uploads';

            foreach (glob($path_web_upload.'/*') as $current_web_file_path) {
                
                $filename = pathinfo($current_web_file_path, PATHINFO_BASENAME);

                rename($current_web_file_path, $this->path_target .'/'. $filename);
            }
    }


    /**
    * Clean up any previous import done with this script
    */
    private function deleteAnyPreviousImport()
    {
            foreach (glob($this->path_target.'/'.self::filename_prefix."*") as $absoluteFilePath) {
                unlink($absoluteFilePath);
            }
    }



   /**
    * Load users of the table 'multipass_users' from the file
    * Build an associative array in which :
    * - key : the id of the user
    * - value : the ORM object 'User' load with the corresponding user data
    *
    * @param file stream - the opened SQL file stream
    * @param array - users associative array that will be populated by this method.
    *
    * @throws an exception in case no users have been found in the database or the file
    */
    private function loadUsers($stream, &$users, OutputInterface $output)
    {
            $nbLineRead = 0;

            rewind($stream);
            while ($line = fgets($stream))
                if (strpos($line, 'COPY multipass_users (id') !== FALSE)
                    break;

            while ($line = fgets($stream)) {
                $data = explode("\t", $line);
                if ($data[0]=="\\.\n") break;

                $user = $this->getUserManager()->findUserByEmail( $data[self::mu_email] );

                // if any user is not found, don't go further
                if ($user) {
                    $users[$data[self::mu_id]] = $user;
                    $this->stats['nb_users_load']++;
                }

                $nbLineRead++;
            }

            if (!$this->stats['nb_users_load']) {
                throw new \Exception(
                    "Users from the file don't exist in the database.\n".
                    "Maybe you should import the database first with the following command :\n".
                    "\tapp/console gesty:db:migrate '".$this->sql_file."'\n".
                    "then run the 'migratefiles' command again");
            }

            if (!$nbLineRead) {
                throw new \Exception('No users found in the SQL file.');
            }
    }



    /**
    * import every files beloning to users, update the array users with the new filenames
    * 
    * @param file stream - the opened PostgreSQL file
    * @param array - users associative array that will be updated by this method.
    * @param \Doctrine\ORM\EntityManager - entity manager
    * @param OutputInterface - console output object
    *
    * @throws an exception if this method failed in importing the files.
    */
    private function importUsersFiles($stream, &$users, OutputInterface $output)
    {
            $em = $this->getContainer()->get('doctrine')->getManager();

            $progress = new ProgressBar($output, count($users));
            $progress->setMessage('Import in progress...');
            $progress->setFormat('    <comment>>></comment> <info>%message% (%current% / %max% users)</info>');
            $progress->start();

            rewind($stream);
            while ($line = fgets($stream))
                if (strpos($line, 'COPY multipass_homes (id') !== FALSE)
                    break;

            while ($line = fgets($stream)) {
                $data = explode("\t", $line);
                if ($data[0] == "\\.\n") break;

                // get the associated user data from the home foreign key user id
                $user = &$users[$data[self::mh_user_id]];

                // process the import itself
                if (!$this->importRow($data, $user)) {
                    $this->stats['nb_homes_load'] = 0;
                    break;
                }
                
                // add in the database transaction
                $em->persist($user);

                $this->stats['nb_homes_load']++;

                $progress->advance();
            }

            if ($this->stats['nb_homes_load']) {
                $progress->setMessage('Files migration done.');
            }
            else {
                $progress->setMessage('Files migration failed.');
            }
            $progress->finish();
            $output->writeln('');

            if (!$this->stats['nb_homes_load']) {
                throw new \Exception('file import failed.'); 
            }
    }



    /**
    * Import the row into the user object
    *
    * @param array - associative array containing all columns for one row
    * @param User - the user that will be updated and for whom the files will be imported
    */
    private function importRow($row, User $user)
    {
            $success = true;

            // replace every item of an associative array that contain "\\N" (NULL) by "".
            foreach($row as &$d) {
                if ($d == "\\N" ) {
                    $d = "";    
                }
            }

            // import files in the appropriate directory and in the database
            $subdirs_to_import = array(
                self::mh_address_evidence   => 'address_evidence/'.$row[self::mh_id],
                self::mh_caf_evidence       => 'caf_evidence/'.$row[self::mh_id],
                self::mh_salary_evidence    => 'salary_evidence/'.$row[self::mh_id],
                self::mh_salary_evidence_2  => 'salary_evidence_2/'.$row[self::mh_id],
                self::mh_salary_evidence_3  => 'salary_evidence_3/'.$row[self::mh_id]
                );

            $filenames_to_import = array(
                self::mh_address_evidence   => $row[self::mh_address_evidence],
                self::mh_caf_evidence       => $row[self::mh_caf_evidence],
                self::mh_salary_evidence    => $row[self::mh_salary_evidence],
                self::mh_salary_evidence_2  => $row[self::mh_salary_evidence_2],
                self::mh_salary_evidence_3  => $row[self::mh_salary_evidence_3]
                );

            $filenames_currently_in_db = array(
                self::mh_address_evidence   => $user->getPathDomicile(),
                self::mh_caf_evidence       => $user->getPathPrestations(),
                self::mh_salary_evidence    => $user->getPathSalaire1(),
                self::mh_salary_evidence_2  => $user->getPathSalaire2(),
                self::mh_salary_evidence_3  => $user->getPathSalaire3()
                );

            $user_method_set_paths = array(
                self::mh_address_evidence   => 'setPathDomicile',
                self::mh_caf_evidence       => 'setPathPrestations',
                self::mh_salary_evidence    => 'setPathSalaire1',
                self::mh_salary_evidence_2  => 'setPathSalaire2',
                self::mh_salary_evidence_3  => 'setPathSalaire3'
                );

            foreach($user_method_set_paths as $key => $user_method_set_path) {

                $newFilename = $this->copyFile( array(
                                'subdir_to_import'           => $subdirs_to_import[$key],
                                'filename_to_import'         => $filenames_to_import[$key],
                                'filename_currently_in_db'   => $filenames_currently_in_db[$key]
                                ));

                // on success, update the filename in the User instance with the appropriate setter method
                if (!empty($newFilename)) {
                    $user->{$user_method_set_path}( $newFilename );
                }
            }
            return $success;
    }    



    /**
    * Import a file into :
    * - the corresponding column in the database for a given user.
    *   This data will not be updated if there is an existing
    *   file already recorded and if this file was not imported by this script.
    * - the app/uploads directory
    *
    * @param array. params is an associatif array that must contains the following key / value :
    *        'subdir_to_import'          => the sub directory where the file to import is stored,
    *        'filename_to_import'        => the filename of the file to import (not the path),
    *        'filename_currently_in_db'  => the current stored filename from database.
    * @return string the new filename or '' if the file has not been copied.
    */
    private function copyFile($params)
    {
            // we must ensure there is no previously imported file already 
            // present in the database and in the target directory.
            $filename       = $params['filename_currently_in_db'];
            
            if (!empty($filename) && 
                !preg_match('/^'.(self::filename_prefix).'/', $filename) &&
                is_file($this->path_target."/".$filename)) {
                    return;
            }
          
            // build the original path
            $originalPath = $this->path_original .'/'. $params['subdir_to_import'] .'/'. $params['filename_to_import'];

            // ensure the file exists        
            if (!is_file($originalPath)) {
                return '';
            }

            // create the new filename
            $newFilename  = $this->createUniqueName( $params['filename_to_import'] );

            // copy the file
            return copy( $originalPath, $this->path_target .'/'. $newFilename ) ? $newFilename : '';
    }



    /**
    * Create a unique filename
    * It "repeats" the method from Application\Sonata\UserBundle\Entity\User
    * Moreover, in this method, we add a prefix to the filename in order to
    * distinguish files uploaded by the means of the parent website
    * and files copied by this script.
    *
    * @param string filename - a path or a filename
    *
    * @return string the unique filename with prefix
    */
    private function createUniqueName($filename)
    {
        if (!empty($filename)) {
            return self::filename_prefix . uniqid() .'.'. pathinfo($filename, PATHINFO_EXTENSION);
        }
        return '';
    }


    /**
     * Ask for confirmation
     *
     * @param InputInterface  this command input object
     * @param OutputInterface this command output object
     * @param string          the question to display
     *
     * @return bool return true if the user of this command has confirmed.
     */
    private function askConfirmation(InputInterface $input, OutputInterface $output, $question)
    {
            if (!class_exists('Symfony\Component\Console\Question\ConfirmationQuestion')) {
                $dialog = $this->getHelperSet()->get('dialog');

                return $dialog->askConfirmation($output, $question, false);
            }

            $questionHelper = $this->getHelperSet()->get('question');
            $question = new ConfirmationQuestion($question, false);

            return $questionHelper->ask($input, $output, $question);
    }



    /**
     * @return UserManagerInterface
     */
    private function getUserManager()
    {
            return $this->getContainer()->get('fos_user.user_manager');
    }

}
