<?php

/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace WCS\CantineBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Sonata\UserBundle\Model\UserInterface;


class ProfileType extends \Sonata\UserBundle\Form\Type\ProfileType
{

    /**
     * ProfileType constructor.
     */
    public function __construct()
    {
       parent::__construct('Application\Sonata\UserBundle\Entity\User');
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('gender', 'sonata_user_gender', array(
                'label'    => 'form.label_gender',
                'required' => true,
                'translation_domain' => 'SonataUserBundle',
                'choices' => array(
                    UserInterface::GENDER_FEMALE => 'gender_female',
                    UserInterface::GENDER_MALE   => 'gender_male',
                )
            ))
            ->add('firstname', null, array(
                'label'    => 'form.label_firstname',
                'required' => true
            ))
            ->add('lastname', null, array(
                'label'    => 'form.label_lastname',
                'required' => true
            ))
            ->add('adresse', 'text', array( 'required' => true))
            ->add('codePostal', 'text', array( 'required' => true))
            ->add('commune', 'text', array( 'required' => true))
            ->add('phone', 'text', array( 'required' => true))
            ->add('telephoneSecondaire', 'text', array( 'required' => false))
            ->add('caf', 'text', array( 'required' => false))
            ->add('modeDePaiement', 'choice',array('required'=>true,
                    'choices' => array(
                    ''=>'Sélectionnez',
                    'Cheque' => 'Chèque',
                    'Especes' => 'Espèces',
                    'Prelevements' => 'Prélèvements',

                )))
            ->add('numeroIban', 'text', array( 'required' => false))
            ->add('mandatActif', 'checkbox', array( 'required' => false))
            
            
            ->add('file_domicile', 'file', array(
                'label'=>'Justificatif de Domicile',
                'required'=>false
            ))
            ->add('file_prestations', 'file', array(
                'label'=>'Notification des prestations CAF-MSA',
                'required'=>false
            ))
            ->add('file_salaire_1', 'file', array('label'=>'Justificatif de revenus',
                'required'=>false,
                ))
            ->add('file_salaire_2', 'file', array('label'=>false,
                'required'=>false
            ))
            ->add('file_salaire_3', 'file', array('label'=>false,
                'required'=>false))

            ->add('file_impots', 'file', array('label'=>"Dernier avis d'imposition",
                'required'=>false))
            ->add('envoyer', 'submit')

        ;

    }
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'application_sonata_user_profile';
    }
}
