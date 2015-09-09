<?php


    namespace App\RepairBundle\Menu;

    use Knp\Menu\FactoryInterface;
    use Symfony\Component\DependencyInjection\ContainerAware;

    class Builder extends ContainerAware
    {
        public function mainMenu(FactoryInterface $factory,array $options)
        {
            $menu = $factory->createItem('root');
            $menu->setChildrenAttribute('class', 'nav navbar-nav navbar-right');//ul
            if($this->get('request')->get('_route')=='default_homepage')
            {

            }
            $menu->addChild('首页', array('route' => 'homepage'))

                ->setAttribute('','');
            $menu->addChild('上报故障',array('route' => 'default_homepage'));
            $menu->addChild('我的工单',array('route' => '#'))->setAttribute('','')


            // access services from the container!
            $em = $this->container->get('doctrine')->getManager();
            // findMostRecent and Blog are just imaginary examples
            $blog = $em->getRepository('AppBundle:Blog')->findMostRecent();

            $menu->addChild('Latest Blog Post', array(
                'route' => 'blog_show',
                'routeParameters' => array('id' => $blog->getId())
            ));

            // create another menu item
            $menu->addChild('About Me', array('route' => 'about'));
            // you can also add sub level's to your menu's as follows
            $menu['About Me']->addChild('Edit profile', array('route' => 'edit_profile'));

            // ... add more children

            return $menu;
        }
    }