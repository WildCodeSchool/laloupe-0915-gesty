<?php

namespace WCS\CalendrierBundle\Tests\DependencyInjection;

use WCS\CalendrierBundle\DependencyInjection\Ical;
use WCS\CalendrierBundle\DependencyInjection\IcalException;

class IcalTest extends \PhpUnit_Framework_TestCase
{
    /*==================================================================
     * S'assure que les exceptions sont correctement levées
     ==================================================================*/
    public function testNoException()
    {
        try {
            new Ical(__DIR__ . "/files/Calendrier_Scolaire_Zone_B.ics");
        }
        catch(\Exception $e) {
            $this->fail("Aucune exception ne doit être levée ici. Message : ".$e->getMessage());
        }
    }

    public function exceptionProvider()
    {
        return array(
            array(
                '',
                'Aucun nom de fichier calendrier',
                IcalException::E_NOFILENAME
            ),
            array(
                'nofile.ics',
                'Fichier calendrier introuvable : nofile.ics',
                IcalException::E_FILENOTFOUND
            ),
            array(
                __DIR__ . "/files/fake.ics",
                'Fichier calendrier iCalendar invalide : '.__DIR__ . "/files/fake.ics",
                IcalException::E_INVALIDFILE
            )
        );
    }

    /**
     * @dataProvider exceptionProvider
     */
    public function testExceptions($filepath, $message, $code)
    {
        $this->setExpectedException(
            'WCS\CalendrierBundle\DependencyInjection\IcalException',
            $message,
            $code);
        new Ical($filepath);
    }
}