<?php
// src/WCS/GestyBundle/Command/MigrateCommand.php
namespace WCS\GestyBundle\Command;

use Application\Sonata\UserBundle\Entity\User;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

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



        // Migrate users
        $stream = fopen($file, 'r');
        $nb = 0;
        while ($line = fgets($stream))
            if (strpos($line, 'COPY multipass_users (id') !== FALSE)
                break;
        while ($line = fgets($stream))
        {
            $data = explode('	', $line);
            $entity = new User($data[0]);
            $entity->setUsername($data[1]);
            $entity->setUsernameCanonical($data[1]);
            $entity->setEmail($data[1]);
            $entity->setEmailCanonical($data[1]);
            $entity->setCreatedAt(new \DateTime($data[5]));
            $entity->setEnabled(str_replace('"', '', $data[5]));
            $entity->setPassword(str_replace(',', '.', str_replace('"', '', $data[7])));
            $entity->setLastLogin(new \DateTime(str_replace('"', '', $data[8])));
            $entity->setLocked(str_replace('"', '', $data[9]));
            $entity->setExpired(str_replace('"', '', $data[10]));
            $entity->setExpiresAt(new \DateTime(str_replace('"', '', $data[11])));
            $entity->setConfirmationToken(str_replace('"', '', $data[12]));
            //$entity->setPasswordRequestedAt(new \DateTime(str_replace('"', '', $data[13])));
            //$entity->setRoles(str_replace('"', '', $data[14]));
            $entity->setCredentialsExpired(str_replace('"', '', $data[15]));
            //$entity->setCredentialsExpireAt(new \DateTime(str_replace('"', '', $data[16])));
            if ($data[17] != "NULL") $entity->setCreatedAt(new \DateTime(str_replace('"', '', $data[17])));
            else $entity->setCreatedAt(new \DateTime());
            if ($data[18] != "NULL") $entity->setUpdatedAt(new \DateTime(str_replace('"', '', $data[18])));
            //$entity->setDateOfBirth(new \DateTime(str_replace('"', '', $data[19])));
            $entity->setFirstname(str_replace('"', '', $data[20]));
            $entity->setLastname(str_replace('"', '', $data[21]));
            $entity->setWebsite(str_replace('"', '', $data[22]));
            $entity->setBiography(str_replace('"', '', $data[23]));
            $entity->setGender(str_replace('"', '', $data[24]));
            $entity->setLocale(str_replace('"', '', $data[25]));
            $entity->setTimezone(str_replace('"', '', $data[26]));
            $entity->setPhone(str_replace('"', '', $data[27]));
            $entity->setAdresse(str_replace('"', '', $data[39]));
            $entity->setCodePostal(str_replace('"', '', $data[40]));
            $entity->setCommune(str_replace('"', '', $data[41]));
            $entity->setTelephoneSecondaire(str_replace('"', '', $data[42]));
            $entity->setCaf(str_replace('"', '', $data[43]));
            $entity->setModeDePaiement(str_replace('"', '', $data[44]));
            $entity->setNumeroIban(str_replace('"', '', $data[45]));
            $entity->setMandatActif(str_replace('"', '', $data[46]));
            $entity->setPathDomicile(str_replace('"', '', $data[47]));
            $entity->setPathPrestations(str_replace('"', '', $data[48]));
            $entity->setValidation(str_replace('"', '', $data[52]));
            $em->persist($entity);
            $nb++;
        }
        fclose($stream);
        $em->flush();
        $output->writeln(sprintf('  <comment>></comment> <info>%s users loaded</info>', $nb));

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
}