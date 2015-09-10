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
                $menu->addChild('用户',array());
            }
            if($this->container->get('request')->get('_route')=='default_homepage')
            {
                $menu->addChild('首页', array('route' => 'default_homepage'))
                    ->setAttribute('class','active')
                    ->setLabelAttribute('class', 'sr-only');
            }
            else{
                $menu->addChild('首页', array('route' => 'default_homepage'));

            }
            $menu->addChild('上报故障',array('route' => 'default_homepage'));
            $menu->addChild('我的工单',array())
                ->setAttribute('class','dropdown-toggle')
                ->setAttribute('data-toggle','dropdown')
                ->setAttribute('role','button')
                ->setAttribute('aria-haspopup','true')
                ->setAttribute('aria-expanded','false')
                ->setLabelAttribute('class','caret');


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