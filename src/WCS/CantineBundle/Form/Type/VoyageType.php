<?php

namespace WCS\CantineBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;



class VoyageType extends AbstractType
{
    private static $division;

    public function __construct($division)
    {
        self::$division = $division;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('autorise','checkbox', array('required'=>true, 'mapped'=>false))
            ->add('certifie','checkbox', array('required'=>true, 'mapped'=>false))

            ->add('voyages', 'entity', array(
                'class'   => 'WCSCantineBundle:Voyage',

                'query_builder' => function(EntityRepository $er)
                {
                    return $er->findByDivisionsAnneeScolaire(array('division'=>self::$division));
                },

                'expanded' => true,
                'multiple' => true,
                'required'  => false,
                'mapped' => true
            ))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
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
        return 'WCS_cantinebundle_voyage';
    }
}
