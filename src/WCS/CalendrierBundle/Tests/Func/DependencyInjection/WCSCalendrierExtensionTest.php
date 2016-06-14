<?php
/*
 * Ce test permet de s'assurer que le service se charge correctement
 */
namespace src\WCS\CalendrierBundle\Tests\Func\DependencyInjection;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class WCSCalendrierExtensionTest extends KernelTestCase
{
    public function testGetService()
    {
        $kernel = static::createKernel();
        $kernel->boot();
        $app = new Application($kernel);
        $app->setAutoExit(false);

        $container = $kernel->getContainer();

        $calendrier = $container->get('wcs.calendrierscolaire');
        $this->assertInstanceOf('WCS\CalendrierBundle\Service\Service', $calendrier);
    }
}
