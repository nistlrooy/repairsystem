<?php
/**
 * Created by PhpStorm.
 * User: nistlrooy
 * Date: 2015/9/11
 * Time: 14:57
 */

namespace App\RepairBundle\Service;
use Symfony\Component\DependencyInjection\ContainerInterface;
use App\RepairBundle\Entity\RepairMessageRepository;


class CountUnReadMessage
{

    protected $container;
    protected $messageRepository;

    public function __construct(ContainerInterface $container,RepairMessageRepository $messageRepository)
    {
        $this->container = $container;
        $this->messageRepository = $messageRepository;
    }

    public function countUnRead()
    {
        $number = $this->messageRepository->countByUnread($this->container->get('security.token_storage')->getToken()->getUser()->getId());
        return $number;
    }

}