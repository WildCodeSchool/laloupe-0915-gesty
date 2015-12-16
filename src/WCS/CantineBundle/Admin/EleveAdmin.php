<?php
//WCS/CantineBundle/Admin/EleveAdmin.php
namespace WCS\CantineBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;


class EleveAdmin extends Admin
{




    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('user')
            ->add('nom','text')
            ->add('prenom', 'text')
            ->add('dateDeNaissance','date')
            ->add('regimeSansPorc', null, array('required' => false))
            ->add('allergie',null ,array('required' => false))
            ->add('Etablissement', 'choice', array (
                'choices' => array(
                    'Mme WITKIEWICZ Marie-Agnès - Ecole Notre Dame des Fleurs - PS/MS' => 'Mme WITKIEWICZ Marie-Agnès - Ecole Notre Dame des Fleurs - PS/MS',
                    'Mme BOUCHER Anne-lise - Ecole Notre Dame des Fleurs - MS/GS' => 'Mme BOUCHER Anne-lise - Ecole Notre Dame des Fleurs - MS/GS',
                    'Mme LEMOUE Laurence - Ecole Notre Dame des Fleurs - CP/CE1' => 'Mme LEMOUE Laurence - Ecole Notre Dame des Fleurs - CP/CE1',
                    'Mme LABBEY Hélène - Ecole Notre Dame des Fleurs - CE1/CE2' =>'Mme LABBEY Hélène - Ecole Notre Dame des Fleurs - CE1/CE2',
                    'Mme CATTEEU Anne-Sophie - Ecole Notre Dame des Fleurs - CE2/CM1' =>'Mme CATTEEU Anne-Sophie - Ecole Notre Dame des Fleurs - CE2/CM1',
                    'Mme BRAULT Agnès - Ecole Notre Dame des Fleurs - CM1/CM2' => 'Mme BRAULT Agnès - Ecole Notre Dame des Fleurs - CM1/CM2',
                    'Mme AVARE Frédérique - Ecole "Les Ecureuils" - TPS' => 'Mme AVARE Frédérique - Ecole "Les Ecureuils" - TPS',
                    'Mme GRISON Nadia - Ecole "Les Ecureuils" - PS/MS' => 'Mme GRISON Nadia - Ecole "Les Ecureuils" - PS/MS',
                    'Mme PICHODO Marie-Pierre - Ecole "Les Ecureuils" - GS' => 'Mme PICHODO Marie-Pierre - Ecole "Les Ecureuils" - GS',
                    'M PATARIN David - Ecole "Les Ecureuils" - MS/GS' => 'M PATARIN David - Ecole "Les Ecureuils" - MS/GS',
                    'Mme LABUSSIERE Elodie - Ecole "Les Ecureuils" - PS' => 'Mme LABUSSIERE Elodie - Ecole "Les Ecureuils" - PS',
                    'Mmes TRICOT Corinne/DESSAUX Aurélie - Ecole "Roland-Garros" - CP' => 'Mmes TRICOT Corinne/DESSAUX Aurélie - Ecole "Roland-Garros" - CP',
                    'Mle NOUAILLE-DEGORCE Valérie - Ecole "Roland-Garros" - CE1' => 'Mle NOUAILLE-DEGORCE Valérie - Ecole "Roland-Garros" - CE1',
                    'Mme LUCIEN Nathalie - Ecole "Roland-Garros" - CE2' => 'Mme LUCIEN Nathalie - Ecole "Roland-Garros" - CE2',
                    'Mle BENOIT/Mme BISSIERE - Ecole "Roland-Garros" - CLIS' => 'Mle BENOIT/Mme BISSIERE - Ecole "Roland-Garros" - CLIS',
                    'Mme SAUVAGET - Ecole "Roland-Garros" - CM1/CM2' => 'Mme SAUVAGET - Ecole "Roland-Garros" - CM1/CM2',
                    'Mme MOURAND-PERINO Mélanie - Ecole "Roland-Garros" - CP/CE1' => 'Mme MOURAND-PERINO Mélanie - Ecole "Roland-Garros" - CP/CE1',
                    'Mme POMMIER Emilie - Ecole "Roland-Garros" - CE2/CM1' => 'Mme POMMIER Emilie - Ecole "Roland-Garros" - CE2/CM1',
                    'Mme DESSEAUX Aurélie/M MARECAUX François - Ecole "Roland-Garros" - CM2' => 'Mme DESSEAUX Aurélie/M MARECAUX François - Ecole "Roland-Garros" - CM2',
                )))
            ->add('atteste')
            ->add('autorise')
            ->add('certifie')
            ->add('dates')
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('nom')
            ->add('prenom')
            ->add('dateDeNaissance')


        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add('nom', 'text')
            ->add('prenom', 'text')
            ->add('dateDeNaissance', 'date', array('format' => 'd/m/Y',))
            ->add('Etablissement','choice', array('label'=>'classe'))
            ->add('_action', 'actions', array('actions' => array(
                'edit' => array(),
                'delete' => array(),
            )))
            ->add('allergie', 'text')
            ->add('regimeSansPorc', 'boolean')
        ;
    }


}