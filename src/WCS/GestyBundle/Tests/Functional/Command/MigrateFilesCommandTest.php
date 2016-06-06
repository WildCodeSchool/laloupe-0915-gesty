<?php
/**
 * @see
 * http://symfony.com/doc/current/cookbook/console/console_command.html#testing-commands
 * http://symfony.com/doc/current/components/console/helpers/questionhelper.html#testing-a-command-that-expects-input
 */
namespace WCS\GestyBundle\Tests;


use Symfony\Component\Console\Command\Command;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

use WCS\GestyBundle\Command\MigrateCommand;
use WCS\GestyBundle\Command\MigrateFilesCommand;


class MigrateFilesCommandTest extends KernelTestCase
{
    private $mg_kernel;
    private $path_file_sql;
    private $path_original_files;
    private $path_old_uploads;
    private $path_new_uploads;
    private $app;

    const ID = 0;
    const OLD_FILE = 1;
    const EMAIL = 2;
    const FIRSTNAME = 3;
    const LASTNAME = 4;
    const DOMICILE = 5;
    const CAF = 6;
    const SALARY_1 = 7;
    const SALARY_2 = 8;
    const SALARY_3 = 9;

    private $files_checksum;
    private $beforeUserData = [
        [ '12', '', 'toto@email.com', 'Sébastien', 'DUPONT', 'domicile-dupont.pdf', 'caf-dupont.pdf', 'salaire-dupont-1.pdf', 'salaire-dupont-2.pdf', 'salaire-dupont-3.pdf' ],
        [ '13', '', 'tata@email.com', 'candice',   'PICHON', 'domicile-pichon.pdf', 'caf-pichon.pdf', 'salaire-pichon-1.pdf', 'salaire-pichon-2.pdf', 'salaire-pichon-3.pdf' ],
        [ '3', 'old-salaire-durand-2.pdf', 'titi@email.com', 'CATHERINE', 'DURAND', 'domicile-durand.pdf', '', 'salaire-durand-1.pdf', '', '' ],
        [ '2', 'old-salaire-lenoir-3.pdf', 'tutu@email.com', 'Amandine',  'LENOIR', 'domicile-lenoir.pdf', '', 'salaire-lenoir-1.pdf', 'salaire-lenoir-2.pdf', 'salaire-lenoir-3.pdf' ]
    ];


    /**
     *
     */
    protected function setUp()
    {
        // init the necessary to run the command
        $this->mg_kernel = $this->createKernel(array('environment'=>'test', 'debug'=>false));
        $this->mg_kernel->boot();

        $this->app = new Application($this->mg_kernel);
        $this->app->setAutoExit(false);

        // set up all paths
        $testpath = $this->mg_kernel->getRootDir().'/../src/WCS/GestyBundle/Tests/Resources/';

        $this->path_file_sql        = $testpath.'fake_postgresql_export.sql';
        $this->path_original_files  = $testpath.'/fake_files/';
        $this->path_old_uploads     = $testpath.'/old_uploads/';
        $this->path_new_uploads     = $testpath.'/new_uploads/';

        // copy all fake old uploads to the old upload folder that will be moved by the migrate files command
        foreach (glob($testpath.'fake_old_uploads/*') as $current_file_path) {
            $filename = pathinfo($current_file_path, PATHINFO_BASENAME);
            copy($current_file_path, $this->path_old_uploads .'/'. $filename);
        }

        // compute the checksum of files to import.
        foreach ($this->beforeUserData as $userData) {
            $this->files_checksum[] = [
                $this->getBeforeChecksum($this->path_original_files.'/address_evidence/'.$userData[self::ID].'/'.$userData[self::DOMICILE]),
                $this->getBeforeChecksum($this->path_original_files.'/caf_evidence/'.$userData[self::ID].'/'.$userData[self::CAF]),
                $this->getBeforeChecksum($this->path_original_files.'/salary_evidence/'.$userData[self::ID].'/'.$userData[self::SALARY_1]),
                $this->getBeforeChecksum($this->path_original_files.'/salary_evidence_2/'.$userData[self::ID].'/'.$userData[self::SALARY_2]),
                $this->getBeforeChecksum($this->path_original_files.'/salary_evidence_3/'.$userData[self::ID].'/'.$userData[self::SALARY_3]),
                $this->getBeforeChecksum($this->path_old_uploads.'/'.$userData[self::OLD_FILE]),
            ];
        }

    }

    protected function tearDown()
    {

    }

    /**
     * @covers MigrateFilesCommand::execute
     */
    public function testExecute()
    {
        // import fixture SQL file
        $this->importFixtureData();

        // get the data from the database
        $repo = $this->mg_kernel->getContainer()->get('fos_user.user_manager');
        $user_dupont = $repo->findUserByEmail($this->beforeUserData[0][self::EMAIL]);
        $user_pichon = $repo->findUserByEmail($this->beforeUserData[1][self::EMAIL]);
        $user_durand = $repo->findUserByEmail($this->beforeUserData[2][self::EMAIL]);
        $user_lenoir = $repo->findUserByEmail($this->beforeUserData[3][self::EMAIL]);

        // save old file into the database to ensure old files are not overwritten by migration
        $em = $this->mg_kernel->getContainer()->get('doctrine')->getManager();
        $user_durand->setPathSalaire2($this->beforeUserData[2][self::OLD_FILE]);
        $em->persist($user_durand);
        $user_lenoir->setPathSalaire3($this->beforeUserData[3][self::OLD_FILE]);
        $em->persist($user_lenoir);
        $em->flush();

        // execute the migration files command
        $this->executeCommand();


        // check users exists
        $this->assertInstanceOf('Application\Sonata\UserBundle\Entity\User', $user_dupont);
        $this->assertInstanceOf('Application\Sonata\UserBundle\Entity\User', $user_pichon);
        $this->assertInstanceOf('Application\Sonata\UserBundle\Entity\User', $user_durand);
        $this->assertInstanceOf('Application\Sonata\UserBundle\Entity\User', $user_lenoir);

        // check dupont
        $this->assertEquals($this->beforeUserData[0][self::FIRSTNAME], $user_dupont->getFirstname());
        $this->assertEquals($this->beforeUserData[0][self::LASTNAME], $user_dupont->getLastname());

        $this->assertFileImported($this->files_checksum[0][0], $user_dupont->getPathDomicile());
        $this->assertFileImported($this->files_checksum[0][1], $user_dupont->getPathPrestations());
        $this->assertFileImported($this->files_checksum[0][2], $user_dupont->getPathSalaire1());
        $this->assertFileImported($this->files_checksum[0][3], $user_dupont->getPathSalaire2());
        $this->assertFileImported($this->files_checksum[0][4], $user_dupont->getPathSalaire3());

        // check pichon
        $this->assertEquals($this->beforeUserData[1][self::FIRSTNAME], $user_pichon->getFirstname());
        $this->assertEquals($this->beforeUserData[1][self::LASTNAME], $user_pichon->getLastname());

        $this->assertFileImported($this->files_checksum[1][0], $user_pichon->getPathDomicile());
        $this->assertFileImported($this->files_checksum[1][1], $user_pichon->getPathPrestations());
        $this->assertFileImported($this->files_checksum[1][2], $user_pichon->getPathSalaire1());
        $this->assertFileImported($this->files_checksum[1][3], $user_pichon->getPathSalaire2());
        $this->assertFileImported($this->files_checksum[1][4], $user_pichon->getPathSalaire3());


        // check durand
        $this->assertEquals($this->beforeUserData[2][self::FIRSTNAME], $user_durand->getFirstname());
        $this->assertEquals($this->beforeUserData[2][self::LASTNAME], $user_durand->getLastname());

        $this->assertFileImported($this->files_checksum[2][0], $user_durand->getPathDomicile());
        $this->assertFileImported($this->files_checksum[2][2], $user_durand->getPathSalaire1());
        $this->assertFileImported($this->files_checksum[2][5], $user_durand->getPathSalaire2());


        // check lenoir
        $this->assertEquals($this->beforeUserData[3][self::FIRSTNAME], $user_lenoir->getFirstname());
        $this->assertEquals($this->beforeUserData[3][self::LASTNAME], $user_lenoir->getLastname());

        $this->assertFileImported($this->files_checksum[3][0], $user_lenoir->getPathDomicile());
        $this->assertFileImported($this->files_checksum[3][2], $user_lenoir->getPathSalaire1());
        $this->assertFileImported($this->files_checksum[3][3], $user_lenoir->getPathSalaire2());
        $this->assertFileImported($this->files_checksum[3][5], $user_lenoir->getPathSalaire3());
    }


    /**
     *
     */
    private function importFixtureData()
    {
        // execute the first migration command to import the fake database
        $this->app->add(new MigrateCommand());
        $command = $this->app->find("gesty:db:migrate");
        $tester = new CommandTester($command);

        $this->mockDialogHelper($command);

        $tester->execute(array(
            'command'   => $command->getName(),
            'file'      => $this->path_file_sql
        ));
    }

    /**
     *
     */
    private function executeCommand()
    {
        $this->app->add(new MigrateFilesCommand());
        $command = $this->app->find("gesty:db:migratefiles");
        $tester = new CommandTester($command);

        $this->mockDialogHelper($command);

        $tester->execute(array(
            'command'               => $command->getName(),
            'file'                  => $this->path_file_sql,
            'original_files_path'   => $this->path_original_files,
            'old_upload_path'       => $this->path_old_uploads,
            'target_upload_path'    => $this->path_new_uploads
        ));
    }

    /**
     * @param Command $command
     */
    private function mockDialogHelper(Command $command)
    {
        $mockBuilder = $this->getMockBuilder(\Symfony\Component\Console\Helper\QuestionHelper::class);
        $mockBuilder->setMethods(['ask']);

        $helperMock = $mockBuilder->getMock();
        $helperMock->method('ask')->willReturn(true);

        $command->getHelperSet()->set($helperMock, 'question');
    }

    /**
     * @param $file
     * @return string
     */
    private function getBeforeChecksum($file)
    {
        if (is_file($file)) {
            return \md5_file($file);
        }
        return '';
    }

    /**
     * @param $newFilename
     * @param $checksum
     */
    private function assertFileImported($checksum, $newFilename)
    {
        $path = $this->path_new_uploads.'/'.$newFilename;
        $this->assertTrue(is_file($path), 'must be a file');
        $this->assertEquals($checksum, \md5_file($path), 'md5 must be identical');
    }

}