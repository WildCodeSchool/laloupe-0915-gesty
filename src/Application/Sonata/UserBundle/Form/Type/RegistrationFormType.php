<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;


class RegistrationFormType extends \Sonata\UserBundle\Form\Type\RegistrationFormType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', 'email', array_merge(array(
                'label' => 'form.email',
                'translation_domain' => 'SonataUserBundle',
            ), $this->mergeOptions))
            ->add('plainPassword', 'repeated', array_merge(array(
                'type' => 'password',
                'options' => array('translation_domain' => 'SonataUserBundle'),
                'first_options' => array_merge(array(
                    'label' => 'form.password',
                ), $this->mergeOptions),
                'second_options' => array_merge(array(
                    'label' => 'form.password_confirmation',
                ), $this->mergeOptions),
                'invalid_message' => 'fos_user.password.mismatch',
            ), $this->mergeOptions))
        ;
    }

    public function getName()
    {
        return 'application_sonata_user_registration';
    }
}
