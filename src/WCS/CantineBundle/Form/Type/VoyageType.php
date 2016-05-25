<?php

namespace WCS\CantineBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use WCS\CantineBundle\Entity\Eleve;
use WCS\CantineBundle\Form\DataTransformer\VoyageToStringTransformer;


class VoyageType extends AbstractType
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

            ->add('voyages', 'hidden', array(
                'required'  => false
            ))
        ;

        $builder->get('voyages')
            ->addModelTransformer(new VoyageToStringTransformer($this->manager, $builder->getData()));
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
        return 'WCS_cantinebundle_voyage';
    }
}
