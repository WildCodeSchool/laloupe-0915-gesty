<?php

namespace WCS\EmployeeBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use WCS\CantineBundle\Entity\EleveRepository;



class ActivityEleveType extends AbstractType
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
                    return $er->getQueryGetEleves($options['additional_options'] );
                }
            ))
            ->add('status', 'choice', array(
                'choices' => array('1' => 'Non-Inscrit'),
                'label' => false
            ))
            ->add('date', 'date', array(
            'format' => 'yyyy-MMMM-dd',
            'label' => false
            ))
            ->add('Ajouter', 'submit');

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