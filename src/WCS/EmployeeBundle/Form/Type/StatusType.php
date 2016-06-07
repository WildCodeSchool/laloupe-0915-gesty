<?php

namespace WCS\EmployeeBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class StatusType extends AbstractType
{
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
     * @return string
     */
    public function getName()
    {
        return 'wcs_cantinebundle_status';
    }
}
