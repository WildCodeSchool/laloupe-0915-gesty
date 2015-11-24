<?php
/**
 * Created by PhpStorm.
 * User: Mikou
 * Date: 16/11/2015
 * Time: 23:08
 */

namespace WCS\CantineBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class MessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', 'text')
            ->add('password')
            ->add('save', 'submit')
        ;
    }

    public function getName()
    {
        return 'message';
    }
}