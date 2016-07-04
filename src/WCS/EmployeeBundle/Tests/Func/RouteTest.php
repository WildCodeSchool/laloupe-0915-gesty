<?php
/******************************************************************

    Tests meant to check if URL to access
    the employee workspace are accessible and securized.
    Some urls must be available and some must be rejected.

 ******************************************************************/
namespace WCS\EmployeeBundle\Tests\Func;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use WCS\CantineBundle\TestHelper\GestyFixturesWebTestCase;


class RouteTest extends WebTestCase
{
    /**
     * @var \Symfony\Bundle\FrameworkBundle\Client
     */
    private static $client;

    /**
     * @var GestyFixturesWebTestCase
     */
    private static $fixture;

    /**
     * provide routes when not connected
     * @return array
     */
    public function provideAnonymousUrls()
    {
        return [
            ['/login',          'Simplifiez vous la vie !'],
        ];
    }

    public function provideAuthenticatedUrls()
    {
        $credentials = [
            'employe'       => ['login' => 'employe@email.com',    'passwd' => 'aaa'],
            'cantine'       => ['login' => 'cantine@email.com',     'passwd' => 'aaa'],
            'tap'           => ['login' => 'tap@email.com',         'passwd' => 'aaa'],
            'garderie'      => ['login' => 'garderie@email.com',    'passwd' => 'aaa'],
            'tapgarderie'   => ['login' => 'tapgarderie@email.com', 'passwd' => 'aaa']
        ];

        return [
            //================================================================
            // ACCUEIL
            //================================================================
            ['/manager/',
                [
                    [200, $credentials['employe'] ],
                    [200, $credentials['cantine'] ],
                    [200, $credentials['tap'] ],
                    [200, $credentials['garderie'] ],
                    [200, $credentials['tapgarderie'] ],
                ],
            ],

            //================================================================
            // CANTINE
            //================================================================
            ['/manager/cantine/schools',
                [
                    [200, $credentials['employe'] ],
                    [200, $credentials['cantine'] ],
                    [403, $credentials['tap'] ],
                    [403, $credentials['garderie'] ],
                    [403, $credentials['tapgarderie'] ]
                ],
            ],

            ['/manager/cantine/[id_school_ecureuil]/daylist',
                [
                    [200, $credentials['employe'] ],
                    [200, $credentials['cantine'] ],
                    [403, $credentials['tap'] ],
                    [403, $credentials['garderie'] ],
                    [403, $credentials['tapgarderie'] ]
                ],
            ],

            ['/manager/cantine/[id_school_notredame]/daylist',
                [
                    [200, $credentials['employe'] ],
                    [200, $credentials['cantine'] ],
                    [403, $credentials['tap'] ],
                    [403, $credentials['garderie'] ],
                    [403, $credentials['tapgarderie'] ]
                ],
            ],

            ['/manager/cantine/[id_school_roland]/daylist',
                [
                    [200, $credentials['employe'] ],
                    [200, $credentials['cantine'] ],
                    [403, $credentials['tap'] ],
                    [403, $credentials['garderie'] ],
                    [403, $credentials['tapgarderie'] ]
                ],
            ],

            ['/manager/cantine/reservation',
                [
                    [200, $credentials['employe'] ],
                    [200, $credentials['cantine'] ],
                    [403, $credentials['tap'] ],
                    [403, $credentials['garderie'] ],
                    [403, $credentials['tapgarderie'] ]
                ],
            ],

            //================================================================
            // TAP
            //================================================================

            ['/manager/tap/schools',
                [
                    [200, $credentials['employe'] ],
                    [403, $credentials['cantine'] ],
                    [200, $credentials['tap'] ],
                    [403, $credentials['garderie'] ],
                    [200, $credentials['tapgarderie'] ],
                ],
            ],

            ['/manager/tap/[id_school_ecureuil]/daylist',
                [
                    [200, $credentials['employe'] ],
                    [403, $credentials['cantine'] ],
                    [200, $credentials['tap'] ],
                    [403, $credentials['garderie'] ],
                    [200, $credentials['tapgarderie'] ],
                ],
            ],

            ['/manager/tap/[id_school_notredame]/daylist',
                [
                    [200, $credentials['employe'] ],
                    [403, $credentials['cantine'] ],
                    [404, $credentials['tap'] ],
                    [403, $credentials['garderie'] ],
                    [404, $credentials['tapgarderie'] ],
                ],
            ],

            ['/manager/tap/[id_school_roland]/daylist',
                [
                    [200, $credentials['employe'] ],
                    [403, $credentials['cantine'] ],
                    [200, $credentials['tap'] ],
                    [403, $credentials['garderie'] ],
                    [200, $credentials['tapgarderie'] ],
                ],
            ],

            //================================================================
            // GARDERIE MATIN
            //================================================================

            ['/manager/garderie_matin/schools',
                [
                    [200, $credentials['employe'] ],
                    [403, $credentials['cantine'] ],
                    [403, $credentials['tap'] ],
                    [200, $credentials['garderie'] ],
                    [200, $credentials['tapgarderie'] ],
                ],
            ],

            ['/manager/garderie_matin/[id_school_ecureuil]/daylist',
                [
                    [200, $credentials['employe'] ],
                    [403, $credentials['cantine'] ],
                    [403, $credentials['tap'] ],
                    [200, $credentials['garderie'] ],
                    [200, $credentials['tapgarderie'] ],
                ],
            ],

            ['/manager/garderie_matin/[id_school_notredame]/daylist',
                [
                    [200, $credentials['employe'] ],
                    [403, $credentials['cantine'] ],
                    [403, $credentials['tap'] ],
                    [404, $credentials['garderie'] ],
                    [404, $credentials['tapgarderie'] ],
                ],
            ],

            ['/manager/garderie_matin/[id_school_roland]/daylist',
                [
                    [200, $credentials['employe'] ],
                    [403, $credentials['cantine'] ],
                    [403, $credentials['tap'] ],
                    [200, $credentials['garderie'] ],
                    [200, $credentials['tapgarderie'] ],
                ],
            ],

            //================================================================
            // GARDERIE SOIR
            //================================================================

            ['/manager/garderie_soir/schools',
                [
                    [200, $credentials['employe'] ],
                    [403, $credentials['cantine'] ],
                    [403, $credentials['tap'] ],
                    [200, $credentials['garderie'] ],
                    [200, $credentials['tapgarderie'] ],
                ],
            ],

            ['/manager/garderie_soir/[id_school_ecureuil]/daylist',
                [
                    [200, $credentials['employe'] ],
                    [403, $credentials['cantine'] ],
                    [403, $credentials['tap'] ],
                    [200, $credentials['garderie'] ],
                    [200, $credentials['tapgarderie'] ],
                ],
            ],

            ['/manager/garderie_soir/[id_school_notredame]/daylist',
                [
                    [200, $credentials['employe'] ],
                    [403, $credentials['cantine'] ],
                    [403, $credentials['tap'] ],
                    [404, $credentials['garderie'] ],
                    [404, $credentials['tapgarderie'] ],
                ],
            ],

            ['/manager/garderie_soir/[id_school_roland]/daylist',
                [
                    [200, $credentials['employe'] ],
                    [403, $credentials['cantine'] ],
                    [403, $credentials['tap'] ],
                    [200, $credentials['garderie'] ],
                    [200, $credentials['tapgarderie'] ],
                ],
            ],
        ];
    }

    /**
     *
     */
    public static function setUpBeforeClass()
    {
        self::$fixture = new GestyFixturesWebTestCase();
        self::$fixture->loadGestyFixtures();
    }
    
    /**
     * @dataProvider provideAnonymousUrls
     * @coversNothing
     *
     * @param string $url
     * @param string $expectedText
     */
    public function testAnonymousUrl($url, $expectedText)
    {
        self::$client = static::createClient();
        self::$client->followRedirects();

        $crawler = self::$client->request('GET', $url);

        $this->assertEquals(
            200,
            self::$client->getResponse()->getStatusCode(),
            'Ensure status is 200'
        );

        $this->assertEquals(
            1,
            $crawler->filter('html:contains("'.$expectedText.'")')->count(),
            "The text '$expectedText' should be present in the HTML page"
        );
    }


    /**
     * @dataProvider provideAuthenticatedUrls
     * @coversNothing
     *
     * @param $login
     * @param $password
     * @param $url
     * @param $expectedText
     */
    public function testEmployeeAuthenticatedUrl($url, $data)
    {
        $urlBefore = $url;

        $url = str_replace(
            '[id_school_ecureuil]',
            self::$fixture->getEntityId('school-ecureuils'),
            $url
        );

        $url = str_replace(
            '[id_school_notredame]',
            self::$fixture->getEntityId('school-nddf'),
            $url
        );

        $url = str_replace(
            '[id_school_roland]',
            self::$fixture->getEntityId('school-rg'),
            $url
        );

        foreach ($data as $expected) {
            $codeStatus = $expected[0];

            $login = $expected[1]['login'];
            $password = $expected[1]['passwd'];

            self::$client = static::createClient(array(), array(
                'PHP_AUTH_USER'     => $login,
                'PHP_AUTH_PW'       => $password
            ));

            self::$client->request('GET', $url);

            $this->assertEquals(
                $codeStatus,
                self::$client->getResponse()->getStatusCode(),
                "For : ".$urlBefore." and user : '".$login."' - ensure status is ".$codeStatus
            );
        }
    }

}
