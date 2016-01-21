<?php

namespace WCS\CantineBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class LunchType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('eleve', 'choice', array(
                'choices' => array(46 => 'test'),
                'label' => false
            ))
            ->add('status', 'choice', array(
                'choices' => array('0' => 'Non-Inscrit'),
                'label' => false
                ))
            ->add('date', 'text', array(
                'label' => false
            ))
            ->add('Ajouter', 'submit');
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'WCS\CantineBundle\Entity\Lunch'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'wcs_cantinebundle_lunch';
    }
}
