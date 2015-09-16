<?php

namespace App\RepairBundle\Security\Authorization\Voter;

use Symfony\Component\Security\Core\Authorization\Voter\AbstractVoter;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MessageVoter extends AbstractVoter
{
    const VIEW = 'view';
    const DELETE = 'delete';


    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    protected function getSupportedAttributes()
    {
        return array(self::VIEW,self::DELETE);
    }

    protected function getSupportedClasses()
    {
        return array('App\RepairBundle\Entity\RepairMessage');
    }

    protected function isGranted($attribute, $message, $user = null)
    {
        // make sure there is a user object (i.e. that the user is logged in)
        if (!$user instanceof UserInterface) {
            return false;
        }

        $authChecker = $this->container->get('security.authorization_checker');

        switch ($attribute) {
            case self::VIEW:
                //只有自己才能看
                if($user->getId() == $message->getUser()->getId())
                {
                    return true;
                }
                break;
            case self::DELETE:
                //只有自己才能删
                if($user->getId() == $message->getUser()->getId())
                {
                    return true;
                }
                break;
            default:
                return false;
        }
    }
}