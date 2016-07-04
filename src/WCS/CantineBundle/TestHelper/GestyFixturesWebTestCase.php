<?php
/**
 * @see http://symfony.com/doc/current/components/finder.html
 */

namespace WCS\CantineBundle\TestHelper;

use Application\Sonata\UserBundle\Entity\User;
use Symfony\Component\HttpKernel\KernelInterface;

class GestyFixturesWebTestCase extends \Liip\FunctionalTestBundle\Test\WebTestCase
{
    const PSR_PREFIX = "WCS\\CantineBundle\\DataFixtures\\ORM\\";
    const LOGFILE = '/logs/test_wcs.html.log';

    /**
     * @var \Doctrine\Common\DataFixtures\Executor\AbstractExecutor
     */
    private $fixtures = null;


    /**
     *
     */
    public function loadGestyFixtures()
    {
        $this->fixtures = $this->loadFixtures(array(
            self::PSR_PREFIX.'LoadSchoolYearData',
            self::PSR_PREFIX.'LoadFeriesData',

            self::PSR_PREFIX.'LoadUserData',
            self::PSR_PREFIX.'LoadSchoolData',
            self::PSR_PREFIX.'LoadDivisionData',
            self::PSR_PREFIX.'LoadEleveData',

          //  self::PSR_PREFIX.'LoadLunchData',
          //  self::PSR_PREFIX.'LoadTapData',
          //  self::PSR_PREFIX.'LoadGarderieData',
          //  self::PSR_PREFIX.'LoadVoyageData',
        ));
    }

    /**
     * @param $referenceName
     * @return object
     */
    public function getReference($referenceName)
    {
        return $this->fixtures->getReferenceRepository()->getReference($referenceName);
    }

    /**
     * @param $referenceName
     * @return int
     */
    public function getEntityId($referenceName)
    {
        $entity = $this->getReference($referenceName);
        if ($entity) {
            return $entity->getId();
        }
        return 0;
    }

    /**
     * @param $referenceName
     * @return User
     */
    public function getUserReference($referenceName)
    {
        return $this->getReference($referenceName);
    }

    /**
     * Record the response in a file in the "app/logs" directory.
     * 
     * @param \Symfony\Bundle\FrameworkBundle\Client $client
     */
    public function logResponse(
        KernelInterface $kernel,
        \Symfony\Bundle\FrameworkBundle\Client $client,
        $filename
    )
    {
        $path = $kernel->getRootDir().$filename;
        file_put_contents($path, $client->getResponse()->getContent());
    }

}
