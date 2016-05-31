<?php

namespace WCS\CantineBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use WCS\CalendrierBundle\Service\Periode\Periode;
use WCS\CantineBundle\Form\DataTransformer\TapToStringTransformer;
use WCS\CantineBundle\Form\DataTransformer\GarderieToStringTransformer;
use WCS\CantineBundle\Service\FeriesDayList;


class TapType extends AbstractType
{
    private $manager;
    private $periode;
    private $feriesDayList;

    public function __construct(\Doctrine\Common\Persistence\ObjectManager $manager, Periode $periode, FeriesDayList $feriesDayList)
    {
        $this->manager = $manager;
        $this->periode = $periode;
        $this->feriesDayList = $feriesDayList;
    }


    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('atteste','checkbox', array('required'=>true, 'mapped'=>false))
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
            ->addModelTransformer( new TapToStringTransformer($this->manager, $builder->getData(), $this->periode, $this->feriesDayList) );

        $builder->get('garderies')
            ->addModelTransformer( new GarderieToStringTransformer($this->manager, $builder->getData(), $this->periode, $this->feriesDayList) );
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
