<?php
// src/WCS/GestyBundle/Command/MigrateCommand.php
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

class MigrateCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('gesty:db:migrate')
            ->setDescription('migrate database form old postgres')
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

        if (!$this->askConfirmation($input, $output, '<question>Careful, database will be purged. Do you want to continue y/N ?</question>', false)) {
            return;
        }

        $purger = new ORMPurger($em);
        $purger->setPurgeMode(ORMPurger::PURGE_MODE_DELETE);
        $executor = new ORMExecutor($em, $purger);
        $executor->setLogger(function ($message) use ($output) {
            $output->writeln(sprintf('  <comment>></comment> <info>%s</info>', $message));
        });
        $executor->purge();



        // Load users
        $stream = fopen($file, 'r');
        while ($line = fgets($stream))
            if (strpos($line, 'COPY multipass_users (id') !== FALSE)
                break;
        $userManager = $this->getUserManager();

        $userAdmin = $userManager->createUser();
        $userAdmin->setPlainPassword('admin');
        $userAdmin->setEnabled(true);
        $userAdmin->setEmail('romain@wildcodeschool.fr');
        $userAdmin->setRoles(array('ROLE_SUPER_ADMIN'));
        $userAdmin->setFirstname('Romain');
        $userAdmin->setLastname('Coeur');
        $userAdmin->setPhone('0628974930');
        $userManager->updateUser($userAdmin, true);

        $users = array('admin' => $userAdmin);

        while ($line = fgets($stream))
        {
            $data = explode('	', $line);
            if ($data[0]=="\\.\n") break;
            $entity = $userManager->createUser();
            $entity->setUsername($data[1]);
            $entity->setUsernameCanonical($data[1]);
            $entity->setEmail($data[1]);
            $entity->setEmailCanonical($data[1]);
            $entity->setEnabled(true);
            $entity->setPlainPassword('wild1234');
            $entity->setPassword('9908e42e69c19bc0e6c0ce1bf05b381fbc94ca10aa7e6b648815d676248f8a3fe2ee124f7d9d375e9f909036e45cc9e766e3c9369655c1db1f331e71c17bc2c9');
            $userManager->updateUser($entity, true);
            $users[$data[0]] = $entity;
        }

        $em->flush();
        $output->writeln(sprintf('  <comment>></comment> <info>%s users loaded</info>', count($users)));



        // Add home data to users
        rewind($stream);
        while ($line = fgets($stream))
            if (strpos($line, 'COPY multipass_homes (id') !== FALSE)
                break;
        $nb=0;
        while ($line = fgets($stream)) {
            $data = explode('	', $line);
            if ($data[0] == "\\.\n") break;
            $user = $users[$data[11]];
            $user->setFirstname($data[1]);
            $user->setLastname($data[2]);
            $user->setPhone($data[3]);
            $user->setTelephoneSecondaire($data[4]);
            $user->setAdresse($data[5]);
            $user->setCodePostal($data[6]);
            $user->setCommune($data[7]);
            $user->setModeDePaiement($data[8]);
            $user->setGender($data[9]);
            $user->setCaf($data[10]);
            $em->persist($user);
            $nb++;
        }
        $em->flush();
        $output->writeln(sprintf('  <comment>></comment> <info>%s users updated</info>', $nb));



        // Load schools
        rewind($stream);
        while ($line = fgets($stream))
            if (strpos($line, 'COPY multipass_schools (id') !== FALSE)
                break;
        $schools = array();
        while ($line = fgets($stream)) {
            $data = explode('	', $line);
            if ($data[0] == "\\.\n") break;
            $entity = new School();
            $entity->setName($data[1]);
            $entity->setAdress($data[2]);
            $em->persist($entity);
            $schools[$data[0]] = $entity;
        }
        $em->flush();
        $output->writeln(sprintf('  <comment>></comment> <info>%s schools loaded</info>', count($schools)));



        // Load divisions
        rewind($stream);
        while ($line = fgets($stream))
            if (strpos($line, 'COPY multipass_classrooms (id') !== FALSE)
                break;
        $divisions = array();
        while ($line = fgets($stream)) {
            $data = explode('	', $line);
            if ($data[0] == "\\.\n") break;
            $entity = new Division();
            $entity->setSchool($schools[$data[10]]);
            $gradeAndTeacher=explode(' DE ', $data[1]);
            $entity->setGrade($gradeAndTeacher[0]);
            $entity->setHeadTeacher($gradeAndTeacher[1]);
            $em->persist($entity);
            $divisions[$data[0]] = $entity;
        }
        $em->flush();
        $output->writeln(sprintf('  <comment>></comment> <info>%s divisions loaded</info>', count($divisions)));



        // Load children
        rewind($stream);
        while ($line = fgets($stream))
            if (strpos($line, 'COPY multipass_children (id') !== FALSE)
                break;
        $nb=0;
        while ($line = fgets($stream)) {
            $data = explode('	', $line);
            //var_dump($nb.' - '.$data[0].' - '.$data[11]);
            if ($data[0] == "\\.\n") break;
            if ($data[5] == '\\N') continue;
            $entity = new Eleve();
            $entity->setPrenom($data[1]);
            $entity->setNom($data[2]);
            $entity->setDateDeNaissance(new \DateTime($data[3]));
            $entity->setUser($users[$data[4]]);
            $entity->setDivision($divisions[$data[5]]);
            $habits = array();
            if ($data[8]=='t') $habits[] = "lundi";
            if ($data[9]=='t') $habits[] = "mardi";
            if ($data[10]=='t') $habits[] = "jeudi";
            if ($data[11]=='t') $habits[] = "vendredi";
            $entity->setHabits($habits);
            $entity->setAllergie($data[13]);
            $entity->setRegimeSansPorc($data[14]);
            $em->persist($entity);
            $nb++;
        }
        $em->flush();
        $output->writeln(sprintf('  <comment>></comment> <info>%s divisions loaded</info>', $nb));



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
}