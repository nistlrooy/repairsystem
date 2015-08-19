<?php

    namespace App\RepairBundle\Form\Type;



    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;

    use Symfony\Component\OptionsResolver\OptionsResolver;

    class FaultOrderType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            // add your custom field
            $builder->add('leaderOrder','textarea',array('label' => 'form.order','translation_domain' => 'RepairBundle'));
        }

        public function configureOptions(OptionsResolver $resolver)
        {
            $resolver->setDefaults(array(
                'data_class' => 'App\RepairBundle\Entity\FaultOrder',
            ));
        }

        public function getName()
        {
            return 'fault_order_form';
        }
    }