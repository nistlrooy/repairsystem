<?php

namespace App\RepairBundle\Form\Type;



use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolver;

class FormCommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // add your custom field
        $builder->add('comment','textarea',array('label' => 'form.comment','translation_domain' => 'RepairBundle'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\RepairBundle\Entity\FormComment',
        ));
    }

    public function getName()
    {
        return 'form_comment_form';
    }
}