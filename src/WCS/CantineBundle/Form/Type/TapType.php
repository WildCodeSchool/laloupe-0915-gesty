<?php

namespace WCS\CantineBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use WCS\CantineBundle\Entity\Eleve;
use WCS\CantineBundle\Form\DataTransformer\TapToStringTransformer;
use WCS\CantineBundle\Form\DataTransformer\GarderieToStringTransformer;


class TapType extends AbstractType
{
    private $manager;

    public function __construct(\Doctrine\Common\Persistence\ObjectManager $manager)
    {
        $this->manager = $manager;
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

            ->add('taps', 'hidden', array(
                'required'  => false
            ))
            ->add('garderies', 'hidden', array(
                'required'  => false
            ))
        ;

        $builder->get('taps')
            ->addModelTransformer(new TapToStringTransformer($this->manager, $builder->getData()));

        $builder->get('garderies')
            ->addModelTransformer(new GarderieToStringTransformer($this->manager, $builder->getData()));
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
        return 'WCS_cantinebundle_tapgarderie';
    }
}
