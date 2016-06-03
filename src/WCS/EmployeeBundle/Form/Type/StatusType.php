<?php

namespace WCS\EmployeeBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StatusType extends AbstractType
{
    private $repositoryName;

    public function __construct($repositoryName)
    {
        $this->repositoryName = $repositoryName;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('status', 'text', array(
                'required' => false,
                'label' => false,
                ));
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->repositoryName
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'wcs_cantinebundle_status';
    }
}
