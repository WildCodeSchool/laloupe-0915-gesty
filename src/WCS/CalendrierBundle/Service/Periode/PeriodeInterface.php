<?php
/**
 * Created by PhpStorm.
 * User: rod
 * Date: 13/06/16
 * Time: 11:54
 */

namespace WCS\CalendrierBundle\Service\Periode;


interface PeriodeInterface
{
    /**
     * @return \DateTimeImmutable renvoit la date de début, non modifiable
     */
    public function getDebut();
    
    /**
     * @return \DateTimeImmutable renvoit la date de fin, non modifiable
     */
    public function getFin();

}
