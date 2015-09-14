<?php

namespace App\RepairBundle\Form\Type;



use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolver;

class RepairMessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // add your custom field
        $builder->add('message','textarea',array('label' => 'form.message','translation_domain' => 'RepairBundle'))
                ->add('isRead');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\RepairBundle\Entity\RepairMessage',
        ));
    }

    public function getName()
    {
        return 'repair_message_form';
    }
}