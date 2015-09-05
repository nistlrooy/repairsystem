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
            ->add('title','text',array(
                'label' => '标题',

                'translation_domain' => 'RepairBundle'
            ))
            ->add('faultType','entity',array(
                'label' => '故障类型',
                'class' => 'App\RepairBundle\Entity\FaultType',
                'translation_domain' => 'RepairBundle'
            ))
            ->add('group','entity',array(
                'label' => '故障地点',
                'class' => 'App\UserBundle\Entity\Group',
                'translation_domain' => 'RepairBundle'
            ))
            ->add('faultPriority','entity',array(
                'label' => '优先级',
                'class' => 'App\RepairBundle\Entity\FaultPriority',
                'translation_domain' => 'RepairBundle'
            ))

            ->add('reporterDescription','text',array(
                'label' => '报修人描述',

                'translation_domain' => 'RepairBundle'
            ))
            ->add('workerDescription','textarea',array(
                'label' => '维修人描述',

                'translation_domain' => 'RepairBundle'
            ))
            ->add('maintenanceSchedule','textarea',array(
                'label' => '维修方案',

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
            ->addIdentifier('title')
            ->add('reporterDescription')
            ->add('faultPriority')
            ->add('faultType')
            ->add('group')


        ;
    }

}