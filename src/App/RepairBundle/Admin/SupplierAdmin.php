<?php


namespace App\RepairBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class SupplierAdmin extends Admin
{
    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {

        $formMapper
            ->add('name',null,array(
                'label' => '赞助商名'
            ))

            ->add('description',null,array(
                'label' => '赞助商简介'
            ))
            ->add('phone',null,array(
                'label' => '联系电话'
            ))
            ->add('location',null,array(
                'label' => '地址'
            ))
            ->add('website',null,array(
                'label' => '网站'
            ))



        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')


        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->add('phone')
            ->add('location')
            ->add('website')

        ;
    }

}