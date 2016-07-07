<?php

namespace WCS\CantineBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use WCS\CantineBundle\Entity\Eleve;
use WCS\CantineBundle\Form\DataTransformer\LunchToStringTransformer;


class CantineType extends AbstractType
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
            ->add('regimeSansPorc', 'checkbox', array('required'=>false))
            ->add('allergie', 'text', array('label' =>'allergie', 'required'=>false))
            ->add('habits', 'choice', array(
                'choices'   => Eleve::getHabitDaysLabels(),
                'expanded' => true,
                'multiple' => true,
                'required'  => false
            ))
            ->add('lunches', 'hidden', array(
                'required'  => false
            ))
        ;

        $builder->get('lunches')
            ->addModelTransformer(new LunchToStringTransformer($this->manager, $builder->getData()));

        /**
         * @var \WCS\CantineBundle\Entity\Eleve $entity
         */
        $entity = $builder->getData();
        if (!$entity->isCanteenSigned()) {
            $builder
                ->add('canteen_atteste','checkbox', array('required'=>true, 'mapped'=>false))
                ->add('canteen_autorise','checkbox', array('required'=>true, 'mapped'=>false))
                ->add('canteen_certifie','checkbox', array('required'=>true, 'mapped'=>false));
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
        return 'WCS_cantinebundle_cantine';
    }
}
