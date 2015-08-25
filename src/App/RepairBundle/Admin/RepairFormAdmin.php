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
                ->add('cost')

            ;
        }

        // Fields to be shown on lists
        protected function configureListFields(ListMapper $listMapper)
        {
            $listMapper
                ->addIdentifier('cost')


            ;
        }

    }