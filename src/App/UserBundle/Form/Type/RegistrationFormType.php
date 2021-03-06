<?php

namespace App\UserBundle\Form\Type;

use App\UserBundle\Entity\Group;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // add your custom field
        $builder->add('name', null, array('label' => 'form.name', 'translation_domain' => 'FOSUserBundle'))
                ->add('phone', null, array('label' => 'form.phone', 'translation_domain' => 'FOSUserBundle'))
                ->add('group','entity',array(
                    'class' => 'UserBundle:Group',
                    'property' => 'name',
                    //'multiple' => true,
                    'label' => 'form.groupname',
                    'translation_domain' => 'FOSUserBundle'
                ));
    }

    public function getParent()
    {
        return 'fos_user_registration';
    }

    public function getName()
    {
        return 'app_user_registration';
    }
}