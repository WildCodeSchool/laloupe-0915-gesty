<?php

namespace WCS\CantineBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use WCS\CantineBundle\Entity\Eleve;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class LunchType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('eleve', 'entity', array(
                'class' => 'WCSCantineBundle:Eleve'
            ))
            ->add('status', 'choice', array(
                'choices' => array('0' => 'Non-Inscrit'),
                'label' => false
                ))
            ->add('date', 'date', array(
                'format' => 'yyyy-MMMM-dd',
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
