<?php

namespace App\RepairBundle\Controller;


use App\RepairBundle\Entity\FormComment;
use App\RepairBundle\Form\Type\FaultReportFormType;
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
        if ($form->isValid()) {
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
            $repairForm->setLastUpdateTime(new \DateTime());
            $repairTask->setCreateTime(new \Datetime());
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
            return $this->render('@Repair/Repair/postDone.html.twig');
        }
        return array(
            'form' => $form->createView(),
            );
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
     * @Route("/fault/info/{id}",name="fault_info",requirements={"id": "\d+"})
     * @Template()
     *
     * 每个故障的信息
     */
    public function infoAction($id)
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


        $form = $this->createForm(new FaultReportFormType(),$repairForm);
        return array(
            'repairForm' => $repairForm,
            'orderIsNull' => $orderIsNull,
            'receiveIsNull' =>$receiveIsNull,
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
        $comment = new FormComment();
        $form = $this->createFormBuilder($comment)
            ->add('comment','text')
            ->getForm();
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
            $comment->setComment($data->getComment());
            $comment->setRepairForm($repairForm);
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();
            return $this->redirectToRoute('fault_info',array('id' => $id));
        }
        return $this->render('@Repair/Repair/info.html.twig');
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
        if($action == "set")
        {

        }
        if($action == "create")
        {
            $order = $repairForm->getFaultInfo()->getFaultOrder();
            //没有上报过才能上报
            if($order)
            {
                throw $this->createAccessDeniedException('The order has been created');
            }
            $authChecker = $this->get('security.authorization_checker');
            //验证是否有操作权限
            if(false === $authChecker->isGranted('orderCreate',$repairForm))
            {
                throw $this->createAccessDeniedException('Unauthorized access!');
            }
            $order = new FaultOrder();
            $faultInfo = $repairForm->getFaultInfo();
            $faultInfo->setFaultOrder($order);
            $repairForm->setFaultInfo($faultInfo);
            $em = $this->getDoctrine()->getManager();
            $em->persist($order);
            $em->persist($repairForm);
            $em->flush();


            return $this->render('');
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
        if($form->isValid())
        {
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
                    $repairForm->setLastUpdateTime(new \DateTime());
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($repairForm);
                    $em->flush();
                    return $this->redirectToRoute('fault_info',array('id' => $id));
                    break;
                case 'edit':
                    $faultInfo = $repairForm->getFaultInfo();

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
                    $repairForm->setLastUpdateTime(new \DateTime());

                    $user = $this->getDoctrine()->getManager()->getRepository('UserBundle:User')->find($this->get('security.token_storage')->getToken()->getUser()->getId());
                    $repairForm->setUser($user);

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($repairForm);
                    $em->flush();

                    return $this->redirectToRoute('fault_info',array('id' => $id));
                    break;
                case 'submit':
                    $faultInfo = $repairForm->getFaultInfo();

                    if($data->getFaultInfo()->getReporterDescription())
                        $faultInfo->setReporterDescription($data->getFaultInfo()->getReporterDescription());
                    if($data->getFaultInfo()->getWorkerDescription())
                        $faultInfo->setWorkerDescription($data->getFaultInfo()->getWorkerDescription());
                    if($data->getFaultInfo()->getMaintenanceSchedule())
                        $faultInfo->setMaintenanceSchedule($data->getFaultInfo()->getMaintenanceSchedule());
                    $repairForm->setFaultInfo($faultInfo);
                    $repairForm->setLastUpdateTime(new \DateTime());

                    $user = $this->getDoctrine()->getManager()->getRepository('UserBundle:User')->find($this->get('security.token_storage')->getToken()->getUser()->getId());
                    $repairForm->setUser($user);
                    $condition = $this->getDoctrine()->getManager()->getRepository('RepairBundle:FormCondition')->find(3);
                    $repairForm->setFormCondition($condition);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($repairForm);
                    $em->flush();

                    return $this->redirectToRoute('fault_info',array('id' => $id));
                    break;
                case 'confirm':
                    $repairForm->setLastUpdateTime(new \DateTime());
                    $user = $this->getDoctrine()->getManager()->getRepository('UserBundle:User')->find($this->get('security.token_storage')->getToken()->getUser()->getId());
                    $repairForm->setUser($user);
                    $condition = $this->getDoctrine()->getManager()->getRepository('RepairBundle:FormCondition')->find(4);
                    $repairForm->setFormCondition($condition);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($repairForm);
                    $em->flush();

                    return $this->redirectToRoute('fault_info',array('id' => $id));
                    break;
                case 'reject':
                    $repairForm->setLastUpdateTime(new \DateTime());
                    $user = $this->getDoctrine()->getManager()->getRepository('UserBundle:User')->find($this->get('security.token_storage')->getToken()->getUser()->getId());
                    $repairForm->setUser($user);
                    $condition = $this->getDoctrine()->getManager()->getRepository('RepairBundle:FormCondition')->find(1);
                    $repairForm->setFormCondition($condition);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($repairForm);
                    $em->flush();

                    return $this->redirectToRoute('fault_info',array('id' => $id));
                    break;
                case 'cancel':
                    $repairForm->setLastUpdateTime(new \DateTime());
                    $user = $this->getDoctrine()->getManager()->getRepository('UserBundle:User')->find($this->get('security.token_storage')->getToken()->getUser()->getId());
                    $repairForm->setUser($user);
                    $condition = $this->getDoctrine()->getManager()->getRepository('RepairBundle:FormCondition')->find(6);
                    $repairForm->setFormCondition($condition);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($repairForm);
                    $em->flush();
                default:
                    throw $this->createNotFoundException(
                        'No this action: '.$action
                    );
                    break;


            }


        }
        return $this->render('@Repair/Repair/info.html.twig');

    }
}
