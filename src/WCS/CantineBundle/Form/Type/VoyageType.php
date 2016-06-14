<?php

namespace WCS\CantineBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use WCS\CantineBundle\Entity\VoyageRepository;


class VoyageType extends AbstractType
{
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

                'query_builder' => function(VoyageRepository $er) use ($options)
                {
                    return $er->getQueryByEnabledAndDivisions($options);
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

        $resolver->setDefined(array('date_day', 'division'));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'WCS_cantinebundle_voyage';
    }
}
