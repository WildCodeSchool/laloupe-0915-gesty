<?php

namespace WCS\CantineBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
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
            ->add('division', 'entity', array(
                'class' => 'WCSCantineBundle:Division', 'required'=>true,'placeholder'=>'Sélectionnez'))
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
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'WCS\CantineBundle\Form\Model\EleveNew'
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
