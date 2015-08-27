<?php


namespace App\RepairBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class FaultInfoAdmin extends Admin
{
    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {

        $formMapper
            ->add('faultType','entity',array(
                'label' => 'form.faultType',
                'class' => 'App\RepairBundle\Entity\FaultType',
                'translation_domain' => 'RepairBundle'
            ))
            ->add('group','entity',array(
                'label' => 'form.location',
                'class' => 'App\UserBundle\Entity\Group',
                'translation_domain' => 'RepairBundle'
            ))
            ->add('faultPriority','entity',array(
                'label' => 'form.priority',
                'class' => 'App\RepairBundle\Entity\FaultPriority',
                'translation_domain' => 'RepairBundle'
            ))
            ->add('reporterDescription','text',array(
                'label' => 'form.reporterDescription',

                'translation_domain' => 'RepairBundle'
            ))
            ->add('workerDescription','text',array(
                'label' => 'form.workerDescription',

                'translation_domain' => 'RepairBundle'
            ))
            ->add('maintenanceSchedule','text',array(
                'label' => 'form.maintenanceSchedule',

                'translation_domain' => 'RepairBundle'
            ))


        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('group')
            ->add('faultType')
            ->add('faultPriority')

        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('reporterDescription')


        ;
    }

}