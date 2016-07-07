<?php

namespace WCS\EmployeeBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use WCS\CantineBundle\Entity\EleveRepository;



class ListType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('eleve', 'entity', array(
                'class'         => 'WCSCantineBundle:Eleve',
                'query_builder' => function(EleveRepository $er ) use ($options) {
                    return $er->getQueryUnregisteredPupils($options['additional_options'] );
                }
            ))
            ->add('Ajouter', 'submit', array('label'=>'Inscrire un élève supplémentaire'));

    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'additional_options' => array()
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'wcs_employeebundle_activity';
    }
}
