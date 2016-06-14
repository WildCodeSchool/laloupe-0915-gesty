<?php

namespace WCS\CantineBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EleveNewType extends AbstractType
{
    /**
     * @var \DateTimeInterface
     */
    private $date_day;

    /**
     * EleveNewType constructor.
     * @param \DateTimeInterface $date_day
     */
    public function __construct(\DateTimeInterface $date_day){
        $this->date_day = $date_day;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $year = $this->date_day->format('Y');
        
        $builder
            ->add('nom', 'text')
            ->add('prenom', 'text')
            ->add('dateDeNaissance', 'date',
                array(
                    'format' => 'dd-MMMM-yyyy',
                    'years' =>  range($year - 11, $year - 2)
                )
            )
            ->add('division', 'entity', array(
                'class' => 'WCSCantineBundle:Division', 'required'=>true,'placeholder'=>'Sélectionnez'))
            ->add('certifie','checkbox', array('required'=>true))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'WCS\CantineBundle\Form\FormEntity\EleveFormEntity'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'WCS_cantinebundle_eleve';
    }
}
