<?php

namespace App\RepairBundle\Controller;


use App\RepairBundle\Entity\FormComment;
use App\RepairBundle\Entity\RepairMessage;
use App\RepairBundle\Form\Type\FaultOrderType;
use App\RepairBundle\Form\Type\FaultReportFormType;
use App\RepairBundle\Form\Type\FormCommentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use App\RepairBundle\Entity\RepairTask;
use App\RepairBundle\Entity\RepairFormGroup;
use App\RepairBundle\Entity\RepairForm;
use App\RepairBundle\Entity\FaultInfo;
use App\RepairBundle\Entity\FaultOrder;
use App\RepairBundle\Form\Type\FaultInfoType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Response;


class RepairController extends Controller
{
    /**
     * @Route("/fault/post",name="fault_report")
     * @Template()
     * 提交报修单
     */
    public function postAction(Request $request)
    {
        $form = $this->createForm(new FaultInfoType(),new FaultInfo());
        $form->handleRequest($request);
        if ($form->isValid())
        {
            // the validation passed, do something with the object
            //get form data
            $data = $form->getData();
            $repairForm = new RepairForm();
            $repairTask = new RepairTask();
            $repairFormGroup = new RepairFormGroup();
            //var_dump($data->getFaultType()->getId());
            //默认设置为none
            if(!$data->getWorkerDescription())
                $data->setWorkerDescription('none');
            if(!$data->getMaintenanceSchedule())
                $data->setMaintenanceSchedule('none');
            //set datetime to now

            $repairForm->setCost(0);
            //find id = 1 ,get the obj and update
            //设置状态为待接单
            $condition = $this->getDoctrine()->getManager()->getRepository('RepairBundle:FormCondition')->find(1);
            $repairForm->setFormCondition($condition);
            //get current user id and update
            $user = $this->getDoctrine()->getManager()->getRepository('UserBundle:User')->find($this->get('security.token_storage')->getToken()->getUser()->getId());
            $repairForm->setUser($user);
            $repairTask->setUser($user);
            $repairFormGroup->setUser($user);
            //绑定设置外键id
            $repairForm->setRepairTask($repairTask);
            $repairForm->setRepairFormGroup($repairFormGroup);
            $repairForm->setFaultInfo($data);

            $em = $this->getDoctrine()->getManager();
            $em->persist($repairTask);
            $em->persist($repairFormGroup);
            $em->persist($repairForm);
            $em->persist($data);
            $em->flush();


            $id = intval($repairForm->getId());
            $url = $this->generateUrl(
                'fault_info',
                array('id' => $id)
            );
            $title = "您的故障上报成功";
            $message ="您的故障上报成功，已经生成维修工单，等待维修人员接单中。点击下方链接查看详细信息";
            $this->sendMessage($this->get('security.token_storage')->getToken()->getUser()->getId(),$title,$message,$url);

            return $this->redirectToRoute('default_homepage');
        }
        $repairForm = $this->getDoctrine()->getRepository('RepairBundle:RepairForm')->getRepairFormByCreater($this->get('security.token_storage')->getToken()->getUser()->getId(),0,4);

        return $this->render('@Repair/Default/defaultIndex.html.twig',array(
            'form' => $form->createView(),
            'repairForm' =>$repairForm
        ));

    }

    /**
     *
     *
     * @Route("/fault/show",name="fault_show")
     * @Template()
     *
     * 显示所有故障
     */
    public function showAction()
    {
        $repairForm = $this->getDoctrine()->getManager()->getRepository('RepairBundle:RepairForm')->findAll();
        return array(
            'repairForm' => $repairForm,
        );
    }


    /**
     *
     *
     * @Route("/fault/info/{id}/{errorMsg}",name="fault_info",requirements={"id": "\d+"})
     * @Template()
     *
     * 每个故障的信息
     */
    public function infoAction($id,$errorMsg = null)
    {
        $repairForm = $this->getDoctrine()->getManager()->getRepository('RepairBundle:RepairForm')->find($id);
        if (!$repairForm) {
            throw $this->createNotFoundException(
                'No repair form found for this id: '.$id
            );
        }
        //判断批示和接受者的值是否为空
        $orderIsNull = is_null($repairForm->getfaultInfo()->getFaultOrder());
        $receiveIsNull = is_null($repairForm->getReceive());
        $userId = $this->get('security.token_storage')->getToken()->getUser()->getId();
        $isReceiver = false;
        if(!$receiveIsNull)
        {
            if($repairForm->getReceive()->getId() == $userId)
                $isReceiver = true;
        }

        $orderSet = false;
        if(!$orderIsNull)
        {
            if($repairForm->getFaultInfo()->getFaultOrder()->getUser())
            {
                $orderSet = true;
            }
        }
        $createrId = $repairForm->getRepairTask()->getUser()->getId();
        if($createrId == $userId)
        {
            $isCreater = true;
        }
        else
        {
            $isCreater = false;
        }
        $form = $this->createForm(new FaultReportFormType(),$repairForm);
        if((!$orderIsNull)&&(!$orderSet))
        {

            $order = $this->createForm(new FaultOrderType(),new FaultOrder());
            return array(
                'repairForm' => $repairForm,
                'orderIsNull' => $orderIsNull,
                'receiveIsNull' => $receiveIsNull,
                'isCreater' => $isCreater,
                'isReceiver' => $isReceiver,
                'orderSet' => $orderSet,
                'errorMsg' => $errorMsg,
                'form' => $form->createView(),
                'order' => $order->createView()
            );
        }

        if($repairForm->getFormCondition()->getId() == 4)
        {
            $comment = $this->createForm(new FormCommentType(),new FormComment());
            return array(
                'repairForm' => $repairForm,
                'orderIsNull' => $orderIsNull,
                'receiveIsNull' => $receiveIsNull,
                'isCreater' => $isCreater,
                'isReceiver' => $isReceiver,
                'orderSet' => $orderSet,
                'errorMsg' => $errorMsg,
                'form' => $form->createView(),
                'comment' => $comment->createView()
            );
        }
        return array(
            'repairForm' => $repairForm,
            'orderIsNull' => $orderIsNull,
            'receiveIsNull' => $receiveIsNull,
            'isCreater' => $isCreater,
            'isReceiver' => $isReceiver,
            'orderSet' => $orderSet,
            'errorMsg' => $errorMsg,
            'form' => $form->createView()
        );
    }


    /**
     * @Route("/fault/comment/{id}",requirements={"id":"\d+"},name="fault_comment")
     *
     *
     *
     */
    public function commentAction(Request $request,$id)
    {

        $form = $this->createForm(new FormCommentType(),new FormComment());
        $form->handleRequest($request);
        $data = $form->getData();

        if($form->isValid())
        {
            $repairForm = $this->getDoctrine()->getManager()->getRepository('RepairBundle:RepairForm')->find($id);
            if (!$repairForm) {
                throw $this->createNotFoundException(
                    'No repair form found for this id: '.$id
                );
            }
            $authChecker = $this->get('security.authorization_checker');

            if(false === $authChecker->isGranted('comment',$repairForm))
            {
                throw $this->createAccessDeniedException('Unauthorized access!');
            }
            $comment = new FormComment();
            $comment->setComment($data->getComment());
            $comment->setRepairForm($repairForm);
            $condition = $this->getDoctrine()->getManager()->getRepository('RepairBundle:FormCondition')->find(5);
            $repairForm->setFormCondition($condition);
            $em = $this->getDoctrine()->getManager();
            $em->persist($repairForm);
            $em->persist($comment);
            $em->flush();

            $id = intval($repairForm->getId());
            $url = $this->generateUrl(
                'fault_info',
                array('id' => $id)
            );
            $title = "您的维修已被评价";
            $message ="您的维修工单已获报修人——".$repairForm->getRepairTask()->getUser()->getName()." 的评价——“".$comment->getComment()."” 点击下方链接查看更多详细信息";
            $this->sendMessage($repairForm->getReceive()->getId(),$title,$message,$url);
            return $this->redirectToRoute('fault_info',array('id' => $id));
        }

        return $this->redirectToRoute('fault_info',array('id' => $id));
    }

    /**
     * @Route("/fault/order/{id}/{action}", requirements={"id": "\d+"},defaults={"action": "set"},name="fault_order")
     *
     *
     */
    public function orderAction(Request $request,$id,$action)
    {
        $repairForm = $this->getDoctrine()->getManager()->getRepository('RepairBundle:RepairForm')->find($id);
        if (!$repairForm) {
            throw $this->createNotFoundException(
                'No repair form found for this id: '.$id
            );
        }
        $authChecker = $this->get('security.authorization_checker');
        if($action == "set")
        {
            if(false === $authChecker->isGranted('orderSet',$repairForm))
            {
                throw $this->createAccessDeniedException('Unauthorized access!');
            }
            $form = $this->createForm(new FaultOrderType(),new FaultOrder());
            $form->handleRequest($request);
            if($form->isValid())
            {
                $data = $form->getData();
                $order = $repairForm->getFaultInfo()->getFaultOrder();
                $order->setLeaderOrder($data->getLeaderOrder());
                $user = $this->getDoctrine()->getManager()->getRepository('UserBundle:User')->find($this->get('security.token_storage')->getToken()->getUser()->getId());
                $order->setUser($user);
                $em = $this->getDoctrine()->getManager();
                $em->persist($order);
                $em->flush();

                $id = intval($repairForm->getId());
                $url = $this->generateUrl(
                    'fault_info',
                    array('id' => $id)
                );
                $title = "领导已对您报修的故障作出批示";
                $message ="您所报修的故障已获领导——".$user->getName()." 的批示——“".$order->getLeaderOrder()."” 点击下方链接查看更多详细信息";
                $this->sendMessage($repairForm->getRepairTask()->getUser()->getId(),$title,$message,$url);

                if($repairForm->getReceive())
                {
                    $id = intval($repairForm->getId());
                    $url = $this->generateUrl(
                        'fault_info',
                        array('id' => $id)
                    );
                    $title = "领导已对您维修的工单作出批示";
                    $message ="您维修的工单已获领导——".$user->getName()." 的批示——“".$order->getLeaderOrder()."” 点击下方链接查看更多详细信息";
                    $this->sendMessage($repairForm->getReceive()->getId(),$title,$message,$url);
                }


                return $this->redirectToRoute('fault_info',array('id' => $id));
            }
            return $this->render('',array( 'form' => $form->createView()));
        }
        if($action == "create")
        {
            //验证是否有操作权限
            if(false === $authChecker->isGranted('orderCreate',$repairForm))
            {
                throw $this->createAccessDeniedException('Unauthorized access!');
            }
            $order = new FaultOrder();
            $order->setLeaderOrder('none');
            $faultInfo = $repairForm->getFaultInfo();
            $faultInfo->setFaultOrder($order);
            $repairForm->setFaultInfo($faultInfo);
            $em = $this->getDoctrine()->getManager();
            $em->persist($order);
            $em->persist($repairForm);
            $em->flush();


            return $this->redirectToRoute('fault_info',array('id' => $id));
        }
        throw $this->createNotFoundException('No this action: '.$action);
    }


    /**
     * @Route("/fault/{action}/{id}", requirements={"id": "\d+"},name="fault_action")
     *
     *
     *
     */
    public function editAction(Request $request,$action,$id)
    {

        $form = $this->createForm(new FaultReportFormType(),new RepairForm());
        $form->handleRequest($request);
        $repairForm = $this->getDoctrine()->getManager()->getRepository('RepairBundle:RepairForm')->find($id);
        if (!$repairForm) {
            throw $this->createNotFoundException(
                'No repair form found for this id: '.$id
            );
        }
        $authChecker = $this->get('security.authorization_checker');

        if(false === $authChecker->isGranted($action,$repairForm))
        {
            throw $this->createAccessDeniedException('Unauthorized access!');
        }

        $data = $form->getData();

        switch($action)
        {
            case 'receive':
                $condition = $this->getDoctrine()->getManager()->getRepository('RepairBundle:FormCondition')->find(2);
                $repairForm->setFormCondition($condition);
                $user = $this->getDoctrine()->getManager()->getRepository('UserBundle:User')->find($this->get('security.token_storage')->getToken()->getUser()->getId());
                $repairForm->setUser($user);
                $repairForm->setReceive($user);

                $em = $this->getDoctrine()->getManager();
                $em->persist($repairForm);
                $em->flush();

                $id = intval($repairForm->getId());
                $url = $this->generateUrl(
                    'fault_info',
                    array('id' => $id)
                );
                $title = "维修工单已被维修人员接收";
                $message ="您报修的故障——“".$repairForm->getFaultInfo()->getTitle()."”已自动生成维修工单，且已经被维修人员——".$user->getName()." 接收，点击下方链接查看更多详细信息";
                $this->sendMessage($repairForm->getRepairTask()->getUser()->getId(),$title,$message,$url);

                return $this->redirectToRoute('repair_homepage');
                break;
            case 'confirm':

                $user = $this->getDoctrine()->getManager()->getRepository('UserBundle:User')->find($this->get('security.token_storage')->getToken()->getUser()->getId());
                $repairForm->setUser($user);
                $condition = $this->getDoctrine()->getManager()->getRepository('RepairBundle:FormCondition')->find(4);
                $repairForm->setFormCondition($condition);
                $em = $this->getDoctrine()->getManager();
                $em->persist($repairForm);
                $em->flush();

                $id = intval($repairForm->getId());
                $url = $this->generateUrl(
                    'fault_info',
                    array('id' => $id)
                );
                $title = "维修工单已被报修人确认完成";
                $message ="您的维修工单——“".$repairForm->getFaultInfo()->getTitle()."”已被报修人——".$repairForm->getRepairTask()->getUser()->getName()."确认维修完成，点击下方链接查看更多详细信息";
                $this->sendMessage($repairForm->getReceive()->getId(),$title,$message,$url);

                return $this->redirectToRoute('fault_info',array('id' => $id));
                break;
            case 'reject':
                $preCondition = $repairForm->getFormCondition()->getId();
                $repairForm->setReceive(null);
                $repairForm->setRejectTimes($repairForm->getRejectTimes()+1);
                $user = $this->getDoctrine()->getManager()->getRepository('UserBundle:User')->find($this->get('security.token_storage')->getToken()->getUser()->getId());
                $repairForm->setUser($user);
                $condition = $this->getDoctrine()->getManager()->getRepository('RepairBundle:FormCondition')->find(1);
                $repairForm->setFormCondition($condition);
                $em = $this->getDoctrine()->getManager();
                $em->persist($repairForm);
                $em->flush();

                $id = intval($repairForm->getId());
                $url = $this->generateUrl(
                    'fault_info',
                    array('id' => $id)
                );
                if($preCondition == 3)
                {
                    $title = "维修工单已被报修人驳回";
                    $message ="您的维修工单——“".$repairForm->getFaultInfo()->getTitle()."”已被报修人——".$user->getName()." 驳回，点击下方链接前往查看详细信息";
                    $this->sendMessage($repairForm->getReceive()->getId(),$title,$message,$url);
                }
                if($preCondition == 2)
                {
                    $title = "维修人撤销维修";
                    $message ="您的维修工单——“".$repairForm->getFaultInfo()->getTitle()."”已被维修人——".$user->getName()." 退回，维修工单将变为待接单状态，等待其他维修人员接单维修。点击下方链接前往查看详细信息";
                    $this->sendMessage($repairForm->getRepairTask()->getUser()->getId(),$title,$message,$url);
                }


                return $this->redirectToRoute('fault_info',array('id' => $id));
                break;
            case 'cancel':

                $user = $this->getDoctrine()->getManager()->getRepository('UserBundle:User')->find($this->get('security.token_storage')->getToken()->getUser()->getId());
                $repairForm->setUser($user);
                $condition = $this->getDoctrine()->getManager()->getRepository('RepairBundle:FormCondition')->find(6);
                $repairForm->setFormCondition($condition);
                $em = $this->getDoctrine()->getManager();
                $em->persist($repairForm);
                $em->flush();

                $id = intval($repairForm->getId());
                $url = $this->generateUrl(
                    'fault_info',
                    array('id' => $id)
                );
                $title = "您已成功取消维修工单";
                $message ="您报修的故障——“".$repairForm->getFaultInfo()->getTitle()."”已成功取消，点击下方链接查看更多详细信息";
                $this->sendMessage($repairForm->getRepairTask()->getUser()->getId(),$title,$message,$url);

                return $this->redirectToRoute('fault_info',array('id' => $id));
                break;
            default:
                break;
        }

        if($form->isValid())
        {
            if($form->get('save')->isClicked())
            {
                $faultInfo = $repairForm->getFaultInfo();
                if($data->getFaultInfo()->getTitle())
                    $faultInfo->setTitle($data->getFaultInfo()->getTitle());
                if($data->getFaultInfo()->getReporterDescription())
                    $faultInfo->setReporterDescription($data->getFaultInfo()->getReporterDescription());
                if($data->getFaultInfo()->getWorkerDescription())
                    $faultInfo->setWorkerDescription($data->getFaultInfo()->getWorkerDescription());
                if($data->getFaultInfo()->getMaintenanceSchedule())
                    $faultInfo->setMaintenanceSchedule($data->getFaultInfo()->getMaintenanceSchedule());
                if($data->getFaultInfo()->getGroup())
                {
                    $group = $this->getDoctrine()->getManager()->getRepository('UserBundle:Group')->find($data->getFaultInfo()->getGroup()->getId());
                    $faultInfo->setGroup($group);
                }
                if($data->getFaultInfo()->getFaultType())
                {
                    $type = $this->getDoctrine()->getManager()->getRepository('RepairBundle:FaultType')->find($data->getFaultInfo()->getFaultType()->getId());
                    $faultInfo->setFaultType($type);
                }
                if($data->getFaultInfo()->getFaultPriority())
                {
                    $priority = $this->getDoctrine()->getManager()->getRepository('RepairBundle:FaultPriority')->find($data->getFaultInfo()->getFaultPriority()->getId());
                    $faultInfo->setFaultPriority($priority);
                }

                $repairForm->setFaultInfo($faultInfo);

                $repairForm->setCost($data->getCost());
                $user = $this->getDoctrine()->getManager()->getRepository('UserBundle:User')->find($this->get('security.token_storage')->getToken()->getUser()->getId());
                $repairForm->setUser($user);

                $em = $this->getDoctrine()->getManager();
                $em->persist($repairForm);
                $em->flush();



                return $this->redirectToRoute('fault_info',array('id' => $id));
            }
            else
            {
                $faultInfo = $repairForm->getFaultInfo();
                if($data->getFaultInfo()->getTitle())
                    $faultInfo->setTitle($data->getFaultInfo()->getTitle());
                if($data->getFaultInfo()->getReporterDescription())
                    $faultInfo->setReporterDescription($data->getFaultInfo()->getReporterDescription());
                if($data->getFaultInfo()->getWorkerDescription())
                    $faultInfo->setWorkerDescription($data->getFaultInfo()->getWorkerDescription());
                if($data->getFaultInfo()->getMaintenanceSchedule())
                    $faultInfo->setMaintenanceSchedule($data->getFaultInfo()->getMaintenanceSchedule());
                $repairForm->setFaultInfo($faultInfo);

                $repairForm->setCost($data->getCost());
                $user = $this->getDoctrine()->getManager()->getRepository('UserBundle:User')->find($this->get('security.token_storage')->getToken()->getUser()->getId());
                $repairForm->setUser($user);
                $condition = $this->getDoctrine()->getManager()->getRepository('RepairBundle:FormCondition')->find(3);
                $repairForm->setFormCondition($condition);
                $em = $this->getDoctrine()->getManager();
                $em->persist($repairForm);
                $em->flush();

                $id = intval($repairForm->getId());
                $url = $this->generateUrl(
                    'fault_info',
                    array('id' => $id)
                );
                $title = "维修工单需要您的确认";
                $message ="维修人员——".$repairForm->getReceive()->getName()."请您确认故障——“".$repairForm->getFaultInfo()->getTitle()."”是否已维修完成，点击下方链接前往确认页面";
                $this->sendMessage($repairForm->getRepairTask()->getUser()->getId(),$title,$message,$url);

                return $this->redirectToRoute('fault_info',array('id' => $id));
            }
        }
        return $this->redirectToRoute('fault_info',array('id' => $id));
    }


    public function sendMessage($uid,$title,$msg,$url)
    {
        $message = new RepairMessage();
        $user = $this->getDoctrine()->getRepository("UserBundle:User")->find($uid);
        $message->setUser($user);
        $message->setTitle($title);
        $message->setMessage($msg);
        $message->setUrl($url);
        $em = $this->getDoctrine()->getManager();
        $em->persist($message);
        $em->flush();


    }

}
