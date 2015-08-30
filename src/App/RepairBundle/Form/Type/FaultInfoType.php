<?php

    namespace App\RepairBundle\Form\Type;



    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class FaultInfoType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            // add your custom field
            $builder->add('faultPriority','entity',array(
                            'class' => 'RepairBundle:FaultPriority',
                            'property' => 'name',

                            'translation_domain' => 'RepairBundle'
                        ))
                    ->add('faultType','entity',array(
                            'class' => 'RepairBundle:FaultType',
                            'property' => 'name',

                            'translation_domain' => 'RepairBundle'
                        ))
                    ->add('group','entity',array(
                            'class' => 'UserBundle:Group',
                            'property' => 'name',

                            'translation_domain' => 'RepairBundle'
                        ))
                    ->add('reporterDescription','textarea',array(

                            'translation_domain' => 'RepairBundle'
                        ))
                    ->add('workerDescription','textarea',array(

                            'translation_domain' => 'RepairBundle'
                        ))
                    ->add('maintenanceSchedule','textarea',array(

                            'translation_domain' => 'RepairBundle'
                        ))
                    ->add('title','text',array(

                        'translation_domain' => 'RepairBundle'
                    ))
                    ->add('faultOrder','entity',array(
                            'class' => 'RepairBundle:FaultOrder',
                            'property' => 'leader_order',

                            'translation_domain' => 'RepairBundle'
                        ));
        }

        public function configureOptions(OptionsResolver $resolver)
        {
            $resolver->setDefaults(array(
                'data_class' => 'App\RepairBundle\Entity\FaultInfo',
            ));
        }

        public function getName()
        {
            return 'fault_info_form';
        }
    }