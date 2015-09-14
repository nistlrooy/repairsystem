<?php
/**
 * Created by PhpStorm.
 * User: nistlrooy
 * Date: 2015/9/11
 * Time: 14:57
 */

namespace App\RepairBundle\Service;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\RepairBundle\Entity\RepairMessageRepository;


class CountUnReadMessage
{

    protected $tokenStorage;
    protected $messageRepository;

    public function __construct(TokenStorageInterface  $tokenStorage,RepairMessageRepository $messageRepository)
    {
        $this->tokenStorage =  $tokenStorage;
        $this->messageRepository = $messageRepository;
    }

    public function countUnRead()
    {
        $number = $this->messageRepository->countByUnread($this->tokenStorage->getToken()->getUser()->getId());

        return $number;
    }

}