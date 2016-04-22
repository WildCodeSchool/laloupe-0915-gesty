<?php
// src/WCS/GestyBundle/Command/MigratePathsCommand.php
namespace WCS\GestyBundle\Command;

use Application\Sonata\UserBundle\Entity\User;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
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

class MigratePathsCommand extends ContainerAwareCommand
{
        // columns indexes of multipass_homes
        //  0  : 'id'
        //  1  : 'firstname'
        //  2  : 'lastname'
        //  3  : 'phone'
        //  4  : 'phone_2'
        //  5  : 'address_street'
        //  6  : 'address_postcode'
        //  7  : 'address_city'
        //  8  : 'payment_method'
        //  9  : 'gender'
        //  10 : 'caf'
        //  11 : 'user_id'
        //  12 : 'created_at'
        //  13 : 'updated_at'
        //  14 : 'rib'
        //  15 : 'approved'
        //  16 : 'address_evidence'
        //  17 : 'address_evidence_2'
        //  18 : 'caf_evidence'
        //  19 : 'salary_evidence'
        //  20 : 'salary_evidence_2'
        //  21 : 'salary_evidence_3'
        //  22 : 'salary_evidence_4'
        //  23 : 'salary_evidence_5'
        //  24 : 'salary_evidence_6'

    const user_id               = 11;
    const address_evidence      = 16;
    const address_evidence_2    = 17;
    const caf_evidence          = 18;
    const salary_evidence       = 19;
    const salary_evidence_2     = 20;
    const salary_evidence_3     = 21;
    const salary_evidence_4     = 22;
    const salary_evidence_5     = 23;
    const salary_evidence_6     = 24;

    protected function configure()
    {
        $this
            ->setName('gesty:db:migratepaths')
            ->setDescription('migrate path to attached files in the database')
            ->addArgument(
                'file',
                InputArgument::REQUIRED,
                'postgres dump file to read data from'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $file = $input->getArgument('file');
        $em = $this->getContainer()->get('doctrine')->getManager();

        if (!$this->askConfirmation($input, $output, '<question>Careful, all paths to attached files will be updated. Do you want to continue y/N ?</question>', false)) {
            return;
        }

        $userManager = $this->getUserManager();

        // Load users
        $stream = fopen($file, 'r');
        while ($line = fgets($stream))
            if (strpos($line, 'COPY multipass_users (id') !== FALSE)
                break;

        $users = array();

        while ($line = fgets($stream))
        {
            $data = explode('	', $line);
            if ($data[0]=="\\.\n") break;
            $entity = $this->getUserManager()->findUserByEmail( $data[1] );
            $users[$data[0]] = $entity;
        }

        $output->writeln(sprintf('  <comment>></comment> <info>%s users loaded</info>', count($users)));


        // add paths from home data to users
        rewind($stream);
        while ($line = fgets($stream))
            if (strpos($line, 'COPY multipass_homes (id') !== FALSE)
                break;
        $nb=0;
        while ($line = fgets($stream)) {
            $data = explode('	', $line);
            if ($data[0] == "\\.\n") break;

            $user = $users[$data[self::user_id]];

            $user->setPathDomicile(     $this->get( $data, self::address_evidence   ));
            $user->setPathPrestations(  $this->get( $data, self::caf_evidence       ));
            $user->setPathSalaire1(     $this->get( $data, self::salary_evidence    ));
            $user->setPathSalaire2(     $this->get( $data, self::salary_evidence_2  ));
            $user->setPathSalaire3(     $this->get( $data, self::salary_evidence_3  ));

            $em->persist($user);

            $nb++;
        }
        $em->flush();
        $output->writeln(sprintf('  <comment>></comment> <info>%s users updated</info>', $nb));

        fclose($stream);
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
    * return the data at the column_index, and cleaned without "\\N" (NULL)
    *
    * @param array table row indexed by column.
    * @param integer column index
    * @return string the data for the column_index
    */
    public function get($row, $column_index)
    {
        return ( $row[$column_index] != "\\N" ) ? $row[$column_index] : "";
    }
}