<?php

namespace WCS\CantineBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use WCS\CantineBundle\Form\DataTransformer\DaysOfWeeks;
use WCS\CantineBundle\Form\DataTransformer\TapToStringTransformer;
use WCS\CantineBundle\Form\DataTransformer\GarderieToStringTransformer;


class TapType extends AbstractType
{
    private $manager;
    private $daysOfWeek;

    public function __construct(\Doctrine\Common\Persistence\ObjectManager $manager, DaysOfWeeks $daysOfWeek)
    {
        $this->manager = $manager;
        $this->daysOfWeek = $daysOfWeek;
    }


    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('taps', 'hidden', array(
                'required'  => false
            ))
            ->add('garderies', 'hidden', array(
                'required'  => false
            ))
        ;

        $builder->get('taps')
            ->addModelTransformer( new TapToStringTransformer($this->manager, $builder->getData(), $this->daysOfWeek) );

        $builder->get('garderies')
            ->addModelTransformer( new GarderieToStringTransformer($this->manager, $builder->getData(), $this->daysOfWeek) );
        /**
         * @var \WCS\CantineBundle\Entity\Eleve $entity
         */
        $entity = $builder->getData();
        if (!$entity->isTapgarderieSigned()) {
            $builder
                ->add('tapgarderie_atteste','checkbox', array('required'=>true, 'mapped'=>false))
                ->add('tapgarderie_autorise','checkbox', array('required'=>true, 'mapped'=>false))
                ->add('tapgarderie_certifie','checkbox', array('required'=>true, 'mapped'=>false));
        }
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
