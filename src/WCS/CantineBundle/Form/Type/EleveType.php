<?php

namespace WCS\CantineBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;

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
            ->add('dateDeNaissance', 'date')
            ->add('idEtablissement', 'choice', array (
                'choices'   => array('0' => 'Mme WITKIEWICZ Marie-Agnès - Ecole Notre Dame des Fleurs - PS/MS',
                    '1' => 'Mme BOUCHER Anne-lise - Ecole Notre Dame des Fleurs - MS/GS',
                    '2' => 'Mme LEMOUE Laurence - Ecole Notre Dame des Fleurs - CP/CE1',
                    '3' =>'Mme LABBEY Hélène - Ecole Notre Dame des Fleurs - CE1/CE2',
                    '4' =>'Mme CATTEEU Anne-Sophie - Ecole Notre Dame des Fleurs - CE2/CM1',
                    '5' => 'Mme BRAULT Agnès - Ecole Notre Dame des Fleurs - CM1/CM2',
                    '6' => 'Mme AVARE Frédérique - Ecole "Les Ecureuils" - TPS',
                    '7' => 'Mme GRISON Nadia - Ecole "Les Ecureuils" - PS/MS',
                    '8' => 'Mme PICHODO Marie-Pierre - Ecole "Les Ecureuils" - GS',
                    '9' => 'M PATARIN David - Ecole "Les Ecureuils" - MS/GS',
                    '10' => 'Mme LABUSSIERE Elodie - Ecole "Les Ecureuils" - PS',
                    '11' => 'Mmes TRICOT Corinne/DESSAUX Aurélie - Ecole "Roland-Garros" - CP',
                    '12' => 'Mle NOUAILLE-DEGORCE Valérie - Ecole "Roland-Garros" - CE1',
                    '13' => 'Mme LUCIEN Nathalie - Ecole "Roland-Garros" - CE2',
                    '14' => 'Mle BENOIT/Mme BISSIERE - Ecole "Roland-Garros" - CLIS',
                    '15' => 'Mme SAUVAGET - Ecole "Roland-Garros" - CM1/CM2',
                    '16' => 'Mme MOURAND-PERINO Mélanie - Ecole "Roland-Garros" - CP/CE1',
                    '17' => 'Mme POMMIER Emilie - Ecole "Roland-Garros" - CE2/CM1',
                    '18' => 'Mme DESSEAUX Aurélie/m MARECAUX François - Ecole "Roland-Garros" - CM2',
                )))
            ->add('regimeSansPorc', 'checkbox', array('required'=>false))
            ->add('allergie', 'text', array('label' =>'allergie', 'required'=>false))
            ->add('atteste','checkbox', array('required'=>true))
            ->add('autorise','checkbox', array('required'=>true))
            ->add('certifie','checkbox', array('required'=>true))
            ->add('dates')
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
