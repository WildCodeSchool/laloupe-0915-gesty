<?php

/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\UserBundle\Admin\Model;


use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use FOS\UserBundle\Model\UserManagerInterface;

class UserAdmin extends \Sonata\UserBundle\Admin\Model\UserAdmin
{

    /**
     * {@inheritdoc}
     */
    public function getFormBuilder()
    {
        $this->formOptions['data_class'] = $this->getClass();

        $options = $this->formOptions;
        $options['validation_groups'] = (!$this->getSubject() || is_null($this->getSubject()->getId())) ? 'Registration' : 'Profile';

        $formBuilder = $this->getFormContractor()->getFormBuilder( $this->getUniqid(), $options);

        $this->defineFormBuilder($formBuilder);

        return $formBuilder;

    }

    /**
     * {@inheritdoc}
     */
    public function getExportFields()
    {
        // avoid security field to be exported
        return array_filter(parent::getExportFields(), function($v) {
            return !in_array($v, array('password', 'salt'));
        });
    }



    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper

            ->addIdentifier('username',null,array('label'=>'email'))
            ->add('lastname')
            ->add('firstname')
            ->add('createdAt','date', array('format'=>'d/m/Y',))
            ->add('modeDePaiement',null, array(
                'label'=>'moyen de paiement'),array(
                'Cheque' => 'Chèque',
                'Especes' => 'Espèces',
                'Prelevements' => 'Prélèvements',
                'Carte Bancaire' => 'Carte Bancaire',



            ))
            ->add('path_domicile', null, array('label' => 'Pièces jointes','template' => 'WCSCantineBundle:User:files_list.html.twig'))
            ->add('eleves',null,array('label'=>'Nom enfant(s)', 'admin_code'=>'sonata.admin.eleve'))
            ->add('enabled', null, array('editable' => true))
            ->add('_action', 'actions', array('label'=>'Action','actions' => array(
                'edit' => array(),
                'delete' => array(),
            )))
            ->add('validation','boolean',array('label'=>'Validation du compte', 'required'=>false, 'editable' => true))
        ;



    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $filterMapper)
    {
        $filterMapper


            ->add('username',null,array('label'=>'Email'))
            ->add('lastname',null,array('label'=>'Nom'))
            ->add('firstname',null,array('label'=>'Prénom'))
            ->add('modeDePaiement', 'doctrine_orm_choice', array('label'=>'Mode de paiement'),
                'choice' ,
                array(
                    'choices' => array('Cheque' => 'Chèque', 'Especes' => 'Especes', 'Prélèvements' => 'Prélèvements', 'Carte Bancaire' => 'Carte Bancaire')));
    }

    /**
     * {@inheritdoc}
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->with('General')
            ->add('username')
            ->add('email')
            ->end()
            ->with('Profile')
            ->add('dateOfBirth','date', array('format' => 'd/m/Y',))
            ->add('lastname')
            ->add('gender')
            ->add('phone')
            ->end()
            ->with('Security')
            ->add('token')
            ->add('twoStepVerificationCode')
            ->end()
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('General')
            ->add('email')
            ->add('plainPassword', 'text', array(
                'required' => (!$this->getSubject() || is_null($this->getSubject()->getId()))
            ))
            ->end()
            ->with('Profile')
            ->add('firstname', null, array('required' => false))
            ->add('lastname', null, array('required' => false))
            ->add('gender', 'sonata_user_gender', array(
                'required' => false,
                'translation_domain' => $this->getTranslationDomain()
            ))
            ->end()
            ->With('Contact')
            ->add('codePostal','text', array(
                'label' => 'Code postal',
                'required' => false
            ))
            ->add('commune', 'text', array(
                'label'=>'Commune',
                'required'=>false
            ))
            ->add('phone', null, array('required' => false))
            ->add('modeDePaiement','choice', array(
                'label'=>'mode de paiement',
                'required'=>false,
                'choices'=>array(

                    ''=>'Sélectionnez','Cheque'=>'Chèque','Especes'=>'Espèces','Prélèvements'=>'Prélèvements','Carte Bancaire'=>'Carte Bancaire'
              )
            ))
            ->add('validation',null, array('label'=>'Validation du compte', 'required'=>false))
            ->end()

            ->with('Management')
            ->add('realRoles', 'sonata_security_roles', array(
                'label'    => 'form.label_roles',
                'expanded' => true,
                'multiple' => true,
                'required' => false
            ))
            ->add('enabled', null, array('required' => false, 'label'=>'Validé par le parent'))
            ->end()
        ;

    }

    /**
     * {@inheritdoc}
     */
    public function preUpdate($user)
    {
        $this->getUserManager()->updateCanonicalFields($user);
        $this->getUserManager()->updatePassword($user);
    }

    /**
     * @param UserManagerInterface $userManager
     */
    public function setUserManager(UserManagerInterface $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * @return UserManagerInterface
     */
    public function getUserManager()
    {
        return $this->userManager;
    }


}
