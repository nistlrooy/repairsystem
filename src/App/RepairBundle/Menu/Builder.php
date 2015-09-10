<?php


    namespace App\RepairBundle\Menu;

    use Knp\Menu\FactoryInterface;
    use Symfony\Component\DependencyInjection\ContainerAware;
    use Symfony\Component\Security\Core\Security;

    class Builder extends ContainerAware
    {

        public function mainMenu(FactoryInterface $factory,array $options)
        {

            $menu = $factory->createItem('root');
            $menu->setChildrenAttribute('class', 'nav navbar-nav navbar-right');//ul
            if($this->container->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED'))
            {
                $menu->addChild('userInfo',array());
            }
            if($this->container->get('request')->get('_route')=='default_homepage')
            {
                $menu->addChild('homePage', array('route' => 'default_homepage','label' => '首页'))
                    ->setAttribute('class','active')
                    ->setLabelAttribute('class', 'sr-only');
            }
            else{
                $menu->addChild('homePage', array('route' => 'default_homepage','label' => '首页'));

            }
            $menu->addChild('report',array('route' => 'default_homepage','label' => '上报故障'));
            $menu->addChild('RepairForm',array('route' => 'default_homepage','label' => '我的工单'))
                ->setAttribute('class','dropdown')
                ->setLinkAttribute('class','dropdown-toggle')//a
                ->setLinkAttribute('data-toggle','dropdown')
                ->setLinkAttribute('role','button')
                ->setLinkAttribute('aria-haspopup','true')
                ->setLinkAttribute('aria-expanded','false')
                ->setLabelAttribute('class','caret')
                ->setChildrenAttribute('class', 'dropdown-menu');//ul
            $menu['RepairForm']->addChild('myRepairForm',array('route' => 'default_homepage','label' => '我的工单'));
            $menu['RepairForm']->addChild('reportHistory',array('route' => 'reported_history','label' => '历史记录'));










            // access services from the container!
            $em = $this->container->get('doctrine')->getManager();
            // findMostRecent and Blog are just imaginary examples
            /*
            $blog = $em->getRepository('AppBundle:Blog')->findMostRecent();

            $menu->addChild('Latest Blog Post', array(
                'route' => 'blog_show',
                'routeParameters' => array('id' => $blog->getId())
            ));
            */
            // create another menu item


            // ... add more children

            return $menu;
        }
    }