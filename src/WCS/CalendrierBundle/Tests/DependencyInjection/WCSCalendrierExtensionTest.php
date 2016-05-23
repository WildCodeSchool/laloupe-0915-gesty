<?php
/*
 * Ce test permet de s'assurer que le service se charge correctement
 */
namespace WCS\CalendrierBundle\Tests\DependencyInjection;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class WCSCalendrierExtensionTest extends KernelTestCase
{
    public function testGetService()
    {
        self::bootKernel();

        $container = static::$kernel->getContainer();

        $calendrier = $container->get('wcs.calendrierscolaire');
        $this->assertInstanceOf('WCS\CalendrierBundle\Service\Service', $calendrier);
    }
}
