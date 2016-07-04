<?php

namespace WCS\CantineBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use WCS\CantineBundle\Form\DataTransformer\TapToStringTransformer;
use WCS\CantineBundle\Form\DataTransformer\GarderieToStringTransformer;


class TapGarderieType extends AbstractType
{
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
            ->addModelTransformer(
                new TapToStringTransformer($options['manager'], $builder->getData(), $options['days_of_week'])
            );

        $builder->get('garderies')
            ->addModelTransformer(
                new GarderieToStringTransformer($options['manager'], $builder->getData(), $options['days_of_week'])
            );
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
        $resolver->setDefined(array(
            'data_class',
            'manager',
            'days_of_week'
        ));
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
