<?php

namespace WCS\CantineBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use WCS\CantineBundle\Entity\EleveRepository;



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
                'class'         => 'WCSCantineBundle:Eleve',
                'query_builder' => function(EleveRepository $er ) use ($options) {
                    return $er->createQueryBuilder('e')
                        ->join('e.division', 'd')
                        ->join('d.school', 's')
                        ->where('s.id = :id')
                        ->setParameter('id', $options['schoolId'])
                        ->orderBy('e.nom', 'ASC');
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
            'schoolId' => 0
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
