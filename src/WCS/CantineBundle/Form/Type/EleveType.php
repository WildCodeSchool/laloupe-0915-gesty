<?php

namespace WCS\CantineBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use WCS\CantineBundle\Entity\Eleve;


class EleveType extends AbstractType
{


    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', 'text')
            ->add('prenom', 'text')
            ->add('dateDeNaissance', 'date', array(
            'format' => 'dd-MMMM-yyyy',
            'years' =>  range(\date("Y") - 11, \date("Y") - 2),))
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
            ->add('regimeSansPorc', 'checkbox', array('required'=>false))
            ->add('allergie', 'text', array('label' =>'allergie', 'required'=>false))
            ->add('atteste','checkbox', array('required'=>true))
            ->add('autorise','checkbox', array('required'=>true))
            ->add('certifie','checkbox', array('required'=>true))
            ->add('dates')
            ->add('habits', null, array('required'=>false))
            ->add('habits', 'choice', array(
                'choices'   => Eleve::getHabitDaysLabels(),
                'expanded' => true,
                'multiple' => true,
                'required'  => false
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'WCS\CantineBundle\Entity\Eleve'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'WCS_cantinebundle_eleve';
    }
}
