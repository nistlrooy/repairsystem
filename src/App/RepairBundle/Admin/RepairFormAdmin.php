<?php


    namespace App\RepairBundle\Admin;

    use Sonata\AdminBundle\Admin\Admin;
    use Sonata\AdminBundle\Datagrid\ListMapper;
    use Sonata\AdminBundle\Datagrid\DatagridMapper;
    use Sonata\AdminBundle\Form\FormMapper;

    class RepairFormAdmin extends Admin
    {
        // Fields to be shown on create/edit forms
        protected function configureFormFields(FormMapper $formMapper)
        {

            $formMapper
                ->add('faultInfo','sonata_type_admin',array(
                        'btn_delete'    => false,
                        'btn_add'    => false,
                    )
                )
                ->add('cost','number')
                ->add('formCondition', 'entity', array(
                    'label' => 'form.condition',
                    'class' => 'App\RepairBundle\Entity\FormCondition',
                    'translation_domain' => 'RepairBundle'
                ))


            ;
        }

        // Fields to be shown on filter forms
        protected function configureDatagridFilters(DatagridMapper $datagridMapper)
        {
            $datagridMapper
                ->add('faultInfo.group')
                ->add('faultInfo.faultType')
                ->add('faultInfo.faultPriority')

            ;
        }

        // Fields to be shown on lists
        protected function configureListFields(ListMapper $listMapper)
        {
            $listMapper
                ->addIdentifier('faultInfo.title')
                ->add('faultInfo.group')
                ->add('faultInfo', null, array(
                    'lable'=> 'form.faultType',
                    'translation_domain' => 'RepairBundle',
                    'associated_tostring' => 'getFaultType')
                )


            ;
        }

    }