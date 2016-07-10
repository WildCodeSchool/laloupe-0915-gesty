<?php

namespace WCS\CantineBundle\Entity;
use Symfony\Component\VarDumper\VarDumper;

/**
 * ArchiveStatRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ArchiveStatRepository extends \Doctrine\ORM\EntityRepository
{

    /**
     * TODO : to finish.
     *
     * archivestats is meant to store all computations
     * but, the code that saves all results is in comment (yes, it is bad !)
     * as this algorithm must be think more thorougly.
     * One of the issue : what if any changes must be done by the user
     * after this method is called. There is no way to correct one of the stats
     * (adding or suppressing a pupil subscribing, cancelling a travel...)
     *
     * That is why the persist code is currently commented.
     *
     *
     * @param $month
     * @return array
     */
    public function getStatsFromRepository($month)
    {
        $stats = array();

        $dateStart  = new \DateTime($month.'-01');
        $dateEnd    = new \DateTime($month.'-01 last day of this month');

        // récupère les repositories
        $repoEleve      = $this->getEntityManager()->getRepository('WCSCantineBundle:Eleve');
        $repoArchive    = $this->getEntityManager()->getRepository('WCSCantineBundle:ArchiveStat');

        // récupère les stats archivées pour la date sélectionnée
        $archiveStats = $repoArchive->findBy(
            array('dateMois'=>$dateStart),
            array('ecole'=>'ASC', 'classe'=>'ASC', 'nom'=>'ASC')
        );

        // construit les stats si pas de stats
        if (empty($archiveStats)){

            $liste_eleves = $repoEleve->findBy(array(),
                array('division' => 'ASC', 'nom' => 'ASC', 'prenom' => 'ASC')
                );

            foreach ($liste_eleves as $eleve) {

                // archive les stats pour l'élève donné
                $archiveStat = new ArchiveStat();

                $archiveStat->setParentUserIdBackup( $eleve->getUser()->getId() );
                $archiveStat->setParentNom(  $eleve->getUser()->getLastname() );
                $archiveStat->setParentPrenom( $eleve->getUser()->getFirstname() );
                $archiveStat->setParentEmail( $eleve->getUser()->getEmail() );
                
                $archiveStat->setEleveIdBackup( $eleve->getId() );
                $archiveStat->setNom( $eleve->getNom() );
                $archiveStat->setPrenom( $eleve->getPrenom() );
                
                $archiveStat->setEcoleSchoolIdBackup( $eleve->getDivision()->getSchool()->getId() );
                $archiveStat->setEcole( $eleve->getDivision()->getSchool()->getName() );
                
                $archiveStat->setClasseDivisionIdBackup( $eleve->getDivision()->getId() );
                $archiveStat->setClasse( $eleve->getDivision()->getGrade() );
                $archiveStat->setInstit( $eleve->getDivision()->getHeadTeacher() );

                $archiveStat->setTotalGarderieTap( $repoEleve->findTotalTapGarderieFor(
                    $eleve,
                    $dateStart,
                    $dateEnd
                )
                );
                $archiveStat->setTotalCantine(  $repoEleve->findTotalCantineFor(
                    $eleve,
                    $dateStart,
                    $dateEnd
                )
                );
                $archiveStat->setDateMois( $dateStart );

                //$this->getEntityManager()->persist($archiveStat);
                //$this->getEntityManager()->flush();

                // récupère les stats
                $stats[] = $this->archiveStatEntityToArray($archiveStat);
            }
        }
        else
        {
            foreach($archiveStats as $archiveStat) {
                $stats[] = $this->archiveStatEntityToArray($archiveStat);
            }
        }

        return $stats;
    }

    /**
     * @param ArchiveStat $archiveStat
     * @return array
     */
    private function archiveStatEntityToArray( ArchiveStat $archiveStat )
    {
        $tmp['parent_nom']          = $archiveStat->getParentNom();
        $tmp['parent_prenom']       = $archiveStat->getParentPrenom();
        $tmp['parent_email']        = $archiveStat->getParentEmail();

        $tmp['eleve_nom']           = strtoupper($archiveStat->getNom());
        $tmp['eleve_prenom']        = ucfirst($archiveStat->getPrenom());
        $tmp['ecole']               = $archiveStat->getEcole();
        $tmp['classe']               = $archiveStat->getClasse();
        $tmp['instit']               = $archiveStat->getInstit();
        $tmp['total_tapgarderie']   = $archiveStat ->getTotalGarderieTap();
        $tmp['total_cantine']       = $archiveStat ->getTotalCantine();
        $tmp['date_mois']           = $archiveStat ->getDateMois();

        return $tmp;
    }
}
