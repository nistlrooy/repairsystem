<?php


    namespace App\UserBundle\Admin;

    use Sonata\AdminBundle\Admin\Admin;
    use Sonata\AdminBundle\Datagrid\ListMapper;
    use Sonata\AdminBundle\Datagrid\DatagridMapper;
    use Sonata\AdminBundle\Form\FormMapper;

    class UserAdmin extends Admin
    {
        // Fields to be shown on create/edit forms
        protected function configureFormFields(FormMapper $formMapper)
        {
            $rolesChoices = self::flattenRoles($this->getConfigurationPool()->getContainer()->getParameter('security.role_hierarchy.roles'));

            $formMapper
                ->add('email', 'email', array('label' => 'form.email', 'translation_domain' => 'FOSUserBundle'))
                ->add('username', null, array('label' => 'form.username', 'translation_domain' => 'FOSUserBundle'))
                ->add('name',null,array('label' => 'form.name', 'translation_domain' => 'FOSUserBundle'))
                ->add('plainPassword', 'repeated', array(
                        'type' => 'password',
                        'options' => array('translation_domain' => 'FOSUserBundle'),
                        'first_options' => array('label' => 'form.password'),
                        'second_options' => array('label' => 'form.password_confirmation'),
                        'invalid_message' => 'fos_user.password.mismatch',
                        'required'    => false,
                    )
                )
                ->add('phone','text',array(
                    'label' => 'form.phone',
                    'translation_domain' => 'FOSUserBundle'
                ))
                ->add('enabled', 'checkbox', array(
                    'label'     => 'Enable Account',
                    'translation_domain' => 'FOSUserBundle',
                    'required'  => false,
                ))
                ->add('roles','choice',array('choices'=>$rolesChoices,
                    'multiple'=>true ));
            ;
        }

        // Fields to be shown on filter forms
        protected function configureDatagridFilters(DatagridMapper $datagridMapper)
        {
            $datagridMapper
                ->add('username')
                ->add('name')
            ;
        }

        // Fields to be shown on lists
        protected function configureListFields(ListMapper $listMapper)
        {
            $listMapper
                ->addIdentifier('username')
                ->add('name')

            ;
        }

        public function prePersist($object)
        {
            parent::prePersist($object);
            $this->updateUser($object);
        }

        public function preUpdate($object)
        {
            parent::preUpdate($object);
            $this->updateUser($object);
        }
        //看似无意义，但是不添加就不会修改密码，原因待查
        public function updateUser(\App\UserBundle\Entity\User $u)
        {
            //$u->setName($u->getPassword().'+'.$u->getPlainPassword());
            if (!$u->getPlainPassword())
            {
                $u->setPassword($u->getPassword());
            }

            $um = $this->getConfigurationPool()->getContainer()->get('fos_user.user_manager');
            $um->updateUser($u, true);
        }


        /**
         * Turns the role's array keys into string <ROLES_NAME> keys.
         * @todo Move to convenience or make it recursive ? ;-)
         */
        protected static function flattenRoles($rolesHierarchy)
        {
            $flatRoles = array();
            foreach($rolesHierarchy as $roles) {

                if(empty($roles)) {
                    continue;
                }

                foreach($roles as $role) {
                    if(!isset($flatRoles[$role])) {
                        $flatRoles[$role] = $role;
                    }
                }
            }

            return $flatRoles;
        }







    }