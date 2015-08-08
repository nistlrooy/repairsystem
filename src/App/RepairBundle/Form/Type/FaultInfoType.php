<?php

    namespace App\RepairBundle\Form\Type;



    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;

    class FaultInfoType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            // add your custom field
            $builder->add('priority','entity',array(
                            'class' => 'RepairBundle:FaultPriority',
                            'property' => 'name',
                            'label' => 'form.faultPriority',
                            'translation_domain' => 'RepairBundle'
                        ))
                    ->add('faultType','entity',array(
                            'class' => 'RepairBundle:FaultType',
                            'property' => 'name',
                            'label' => 'form.faultType',
                            'translation_domain' => 'RepairBundle'
                        ))
                    ->add('location','entity',array(
                            'class' => 'UserBundle:Group',
                            'property' => 'name',
                            'label' => 'form.location',
                            'translation_domain' => 'RepairBundle'
                        ))
                    ->add('reportDescription','textarea',array(
                            'label' => 'form.reportDescription',
                            'translation_domain' => 'RepairBundle'
                        ))
                    ->add('workerDescription','textarea',array(
                            'label' => 'form.workertDescription',
                            'translation_domain' => 'RepairBundle'
                        ))
                    ->add('maintenanceSchedule','textarea',array(
                            'label' => 'form.maintenanceSchedule',
                            'translation_domain' => 'RepairBundle'
                        ))
                    ->add('faultOrder','entity',array(
                            'class' => 'RepairBundle:FaultOrder',
                            'property' => 'lead_order',
                            'label' => 'form.faultOrder',
                            'translation_domain' => 'RepairBundle'
                        ));
        }

        public function configureOptions(OptionsResolver $resolver)
        {
            $resolver->setDefaults(array(
                'data_class' => 'RepairBundle\Entity\FaultInfo',
            ));
        }

        public function getName()
        {
            return 'fault_info_form';
        }
    }