<?php

namespace App\RepairBundle\Security\Authorization\Voter;

use Symfony\Component\Security\Core\Authorization\Voter\AbstractVoter;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class EditVoter extends AbstractVoter
{
    const VIEW = 'view';
    const RECEIVE = 'receive';
    const EDIT = 'edit';
    const SUBMIT = 'submit';
    const CONFIRM = 'confirm';
    const COMMENT = 'comment';
    const ORDERCREATE = 'orderCreate';
    const ORDERSET = 'orderSet';
    const CANCEL = 'cancel';
    const REJECT = 'reject';

    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    protected function getSupportedAttributes()
    {
        return array(self::VIEW,self::RECEIVE, self::EDIT,self::SUBMIT,self::CONFIRM,self::REJECT,self::COMMENT,self::ORDERCREATE,self::ORDERSET,self::CANCEL);
    }

    protected function getSupportedClasses()
    {
        return array('App\RepairBundle\Entity\RepairForm');
    }

    protected function isGranted($attribute, $repairForm, $user = null)
    {
        // make sure there is a user object (i.e. that the user is logged in)
        if (!$user instanceof UserInterface) {
            return false;
        }

        $authChecker = $this->container->get('security.authorization_checker');

        switch ($attribute) {
            case self::VIEW:
                // the data object could have for example a method isPrivate()
                // which checks the Boolean attribute $private
                //所有view都允许，允许所有人查看每个故障的详细信息
                return true;
                break;
            case self::RECEIVE:
                //只有工单状态为待接单时，维修人员才可以接单。
                if($authChecker->isGranted('ROLE_REPAIR'))
                {
                    if($repairForm->getFormCondition()->getId() == 1)
                    {
                        return true;
                    }
                }
                break;
            case self::EDIT:
                // we assume that our data object has a method getOwner() to
                // get the current owner user entity for this data object
                //只有报修单状态为待接单，报修人才能修改自己的报修单
                //只有用户身份为接单的维修人员，且工单状态为待维修，接单的维修人员才能修改工单
                if ($user->getId() == $repairForm->getRepairTask()->getUser()->getId())
                {
                    if($repairForm->getFormCondition()->getId() == 1)
                        return true;
                }
                if($authChecker->isGranted('ROLE_REPAIR'))
                {
                    if(($repairForm->getFormCondition()->getId() == 2)&&($repairForm->getReceive()->getId() == $user->getId()))
                    {
                        return true;
                    }
                }
                break;
            case self::SUBMIT:
                //只有当前用户为接收报修单的人，且报修单状态为待维修状态，维修人才可以反馈报修单
                if($user->getId() == $repairForm->getReceive()->getId())
                {
                    if($repairForm->getFormCondition()->getId() == 2)
                        return true;
                }
                break;
            case self::CONFIRM:
                //只有报修单状态为待确认，报修人才能确认自己的报修单
                if ($user->getId() == $repairForm->getRepairTask()->getUser()->getId())
                {
                    if($repairForm->getFormCondition()->getId() == 3)
                        return true;
                }
                break;
            case self::REJECT:
                //只有报修单状态为待确认，报修人才能驳回自己的报修单
                if ($user->getId() == $repairForm->getRepairTask()->getUser()->getId())
                {
                    if($repairForm->getFormCondition()->getId() == 3)
                        return true;
                }
                break;
            case self::COMMENT:
                //只有报修单状态为待评价，报修人才能评价自己的报修单
                if ($user->getId() == $repairForm->getRepairTask()->getUser()->getId())
                {
                    if($repairForm->getFormCondition()->getId() == 4)
                        return true;
                }
                break;
            case self::ORDERCREATE:
                //只有维修人员才能上报故障给领导
                if($authChecker->isGranted('ROLE_REPAIR'))
                {
                    return true;
                }
                break;
            case self::ORDERSET:
                //只有领导才能对表单进行批示
                if($authChecker->isGranted('ROLE_LEADER'))
                {
                    $faultOrder = $repairForm->getFaultInfo()->getFaultOrder();
                    if($faultOrder)
                    {
                        return true;
                    }
                    else
                    {
                        return false;
                    }
                }

                break;
            case self::CANCEL:
                //只有报修单状态为待接单，报修人才能取消自己的报修单
                if ($user->getId() == $repairForm->getRepairTask()->getUser()->getId())
                {
                    if($repairForm->getFormCondition()->getId() == 1)
                        return true;
                }
                break;

            default:
                return false;
        }
    }
}