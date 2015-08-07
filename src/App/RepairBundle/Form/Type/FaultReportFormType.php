<?php

    namespace App\RepairBundle\Form\Type;



    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;

    class FaultReportFormType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            // add your custom field
            $builder->add('faultInfo', new FaultInfoType());
        }

        public function configureOptions(OptionsResolver $resolver)
        {
            $resolver->setDefaults(array(
                'data_class' => 'RepairBundle\Entity\RepairForm',
            ));
        }

        public function getName()
        {
            return 'fault_report_form';
        }
    }