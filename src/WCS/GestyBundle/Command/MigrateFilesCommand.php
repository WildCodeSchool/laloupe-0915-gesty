<?php
/*######################################################################################


######################################################################################*/

namespace WCS\GestyBundle\Command;

use Application\Sonata\UserBundle\Entity\User;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Sonata\UserBundle\Model\UserManagerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use WCS\CantineBundle\Entity\Division;
use WCS\CantineBundle\Entity\Eleve;
use WCS\CantineBundle\Entity\School;

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

    private $path_original          = "";
    private $userManager            = null;
    private $stats                  = array(
                                        "nb_users_load" => 0,
                                        "nb_homes_load" => 0,
                                        "nb_files_unprocessed" => 0
                                        );
 
    protected function configure()
    {
        $this
            ->setName('gesty:db:migratefiles')
            ->setDescription('migrate version 1 attached files in the database')
            ->addArgument(
                'file',
                InputArgument::REQUIRED,
                'postgres dump file to read data from'
            )
            ->addArgument(
                'original_files_path',
                InputArgument::REQUIRED,
                'path of the original attached files'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //---------------------------------------------------------------
        // Set up the processing
        //---------------------------------------------------------------

        // declare local variables
        $users = array();
        $targetRootDir = "";

        // get arguments : get the file name to import, get the path of the attached files
        $file                   = $input->getArgument('file');
        $this->path_original    = $input->getArgument('original_files_path');

        // get managers
        $em = $this->getContainer()->get('doctrine')->getManager();

        if (!$this->askConfirmation($input, $output, '<question>Careful, all paths to attached files will be updated. Do you want to continue y/N ?</question>', false)) {
            return;
        }

        // open the file to import
        $stream = fopen($file, 'r');

        //---------------------------------------------------------------
        // Load users of the table 'multipass_users' from the file
        // Build an associative array in which :
        // - key : the id of the user
        // - value : the ORM object 'User' load with the corresponding user data
        //---------------------------------------------------------------
        while ($line = fgets($stream))
            if (strpos($line, 'COPY multipass_users (id') !== FALSE)
                break;

        while ($line = fgets($stream)) {
            $data = explode('	', $line);
            if ($data[0]=="\\.\n") break;

            $users[$data[self::mu_id]] = $this->getUserManager()->findUserByEmail( $data[self::mu_email] );
            if (empty($targetRootDir)) {
                $targetRootDir = $users[$data[self::mu_id]]->getUploadRootDir();
            }
        }

        $this->stats["nb_users_load"] = count($users);
        $output->writeln(
            sprintf('  <comment>></comment> <info>%s users loaded</info>', $this->stats["nb_users_load"])
            );

        //---------------------------------------------------------------
        // delete all files starting with the prefix
        //---------------------------------------------------------------
        foreach (glob($targetRootDir.'/'.self::filename_prefix."*") as $absoluteFilePath) {
            unlink($absoluteFilePath);
        }


        //---------------------------------------------------------------
        // Load users of the table 'multipass_users' from the file
        // Get the filenames of attached files from this table
        // TO COMPLETE...
        //---------------------------------------------------------------
        rewind($stream);
        while ($line = fgets($stream))
            if (strpos($line, 'COPY multipass_homes (id') !== FALSE)
                break;

        while ($line = fgets($stream)) {
            $data = explode('	', $line);
            if ($data[0] == "\\.\n") break;

            // get the associated user data from the home foreign key user id
            $user = &$users[$data[self::mh_user_id]];

            // process the import itself
            $this->importRow($data, $user, $output);
            
            // add in the database transaction
            $em->persist($user);

            // get rid of the processed user object
            unset($users[$data[self::mh_user_id]]);

            $this->stats["nb_homes_load"]++;
        }

        // save all users updated data in the database
        try {
            $em->flush();
            $output->writeln(
                sprintf('  <comment>></comment> <info>%s users updated</info>', $this->stats["nb_homes_load"])
                );            
        }
        catch(Exception $e) {
            $output->writeln(sprintf('  <comment>></comment> <error>Error during the database updating. Import not done. Error returned : %s</error>', $e));            
        }

        // close the file        
        fclose($stream);
/*
        $output->writeln(
            sprintf('  <comment>></comment> <info>%s files not processed</info>', $this->stats["nb_files_unprocessed"])
            );            

        // display the list of not imported users
        foreach ($users as &$user) {
            $output->writeln(sprintf('  <comment>></comment> <error>not loaded : %s </error>', $user->getEmail()));
        }
*/
        $output->writeln(sprintf('<info>done</info>'));

    }


    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @param string          $question
     * @param bool            $default
     *
     * @return bool
     */
    private function askConfirmation(InputInterface $input, OutputInterface $output, $question, $default)
    {
        if (!class_exists('Symfony\Component\Console\Question\ConfirmationQuestion')) {
            $dialog = $this->getHelperSet()->get('dialog');

            return $dialog->askConfirmation($output, $question, $default);
        }

        $questionHelper = $this->getHelperSet()->get('question');
        $question = new ConfirmationQuestion($question, $default);

        return $questionHelper->ask($input, $output, $question);
    }

    /**
     * @return UserManagerInterface
     */
    public function getUserManager()
    {
        return $this->getContainer()->get('fos_user.user_manager');
    }

    /**
    * replace every item of an associative array 
    * that contain "\\N" (NULL) by "".
    *
    * @param array an associative array row
    */
    public function escapeNulls(&$row)
    {
        foreach($row as &$d) {
            if ($d == "\\N" ) {
                $d = "";    
            }
        }
    }

    /**
    * concat a subdirectory to a filename 
    *
    * @param string subdirectory
    * @param string filename
    *
    * @return string return the subpath (subdirectory + filename)
    */
    public function buildSubPath($subdirectory, $filename)
    {
        if (!empty($filename)) {
            $filename = $subdirectory . "/" . $filename;
        }
        return $filename;
    }

    /**
    * @param string subpath, the subpath of a file in the "path_original" location
    * @return number return 1 if the file exist, 0 otherwise
    */
    public function doesOriginalFileExist($subpath)
    {
        $nb = 0;
        if (!empty($subpath)) {
            $nb = file_exists($this->path_original . "/" . $subpath)?1:0;
        }
        return $nb;
    }

    /**
    * Create a unique filename
    * It "repeats" the method 
    * Moreover, in this method, we add a prefix to the filename in order to be
    * distinguish files uploaded by the means of the parent website
    * and files copied by this script.
    *
    * @param string originalFilename - the path or the filename of the original file
    * @return string the unique filename with prefix generated
    */
    public function createUniqueName($originalFilename)
    {
        if (!empty($originalFilename)) {
            return self::filename_prefix . uniqid() . "." . pathinfo($originalFilename)["extension"];
        }
        return '';
    }

    /**
    * TO COMMENT
    */
    public function importFile($user, $row, $params)
    {
        $filename       = call_user_func( array($user, $params["user_get_path"]) );
        $absolutePath   = call_user_func( array($user, $params["user_get_absolute_path"]) );

        // first of all, we check if a user has already uploaded a file, if it exists 
        // in the target directory and if this file is not one of the files copied by this script
        //if (FALSE == strpos($filename, self::filename_prefix)) {
        if (!empty($filename) && 
            !preg_match('/^'.(self::filename_prefix).'/', $filename) &&
            is_file($absolutePath)) {
                return;
        }

        $sourceSubPath =  $this->buildSubPath( 
                                    $params['original_subdir'] .'/'.$row[self::mh_id], 
                                    $params['original_filename'] 
                                    );

        $targetFilename = $this->createUniqueName($params['original_filename']);

        // update the attached file path of the current user
        call_user_func_array( 
            array($user, $params["user_set_path"]),
            array($targetFilename)
            );

        // copy the user file to the final upload directory
        $sourcePath = $this->path_original . "/" .$sourceSubPath;
        $targetPath = $user->getUploadRootDir() . "/" . $targetFilename;

        if (is_file($sourcePath)) {
            copy( $sourcePath, $targetPath );
        }
    }
 
    /**
    * Import the row into the user object
    * @param array associative array containing all columns
    * @param User user ORM
    */
    public function importRow($row, $user, OutputInterface $output)
    {
        // clean the "null" indication of empty values
        $this->escapeNulls( $row );

        $params = array(
            'original_subdir'           => 'address_evidence',
            'original_filename'         => $row[self::mh_address_evidence],
            'user_get_path'             => 'getPathDomicile',
            'user_get_absolute_path'    => 'getAbsolutePathDomicile',
            'user_set_path'             => 'setPathDomicile'
            );
        $this->importFile( $user, $row, $params );

        $params = array(
            'original_subdir'           => 'caf_evidence',
            'original_filename'         => $row[self::mh_caf_evidence],
            'user_get_path'             => 'getPathPrestations',
            'user_get_absolute_path'    => 'getAbsolutePathPrestations',
            'user_set_path'             => 'setPathPrestations'
            );
        $this->importFile( $user, $row, $params );

        $params = array(
            'original_subdir'           => 'salary_evidence',
            'original_filename'         => $row[self::mh_salary_evidence],
            'user_get_path'             => 'getPathSalaire1',
            'user_get_absolute_path'    => 'getAbsolutePathSalaire1',
            'user_set_path'             => 'setPathSalaire1'
            );
        $this->importFile( $user, $row, $params );

        $params = array(
            'original_subdir'           => 'salary_evidence_2',
            'original_filename'         => $row[self::mh_salary_evidence_2],
            'user_get_path'             => 'getPathSalaire2',
            'user_get_absolute_path'    => 'getAbsolutePathSalaire2',
            'user_set_path'             => 'setPathSalaire2'
            );
        $this->importFile( $user, $row, $params );

        $params = array(
            'original_subdir'           => 'salary_evidence_3',
            'original_filename'         => $row[self::mh_salary_evidence_3],
            'user_get_path'             => 'getPathSalaire3',
            'user_get_absolute_path'    => 'getAbsolutePathSalaire3',
            'user_set_path'             => 'setPathSalaire3'
            );
        $this->importFile( $user, $row, $params );
    }    
}