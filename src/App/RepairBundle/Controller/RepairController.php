<?php

namespace App\RepairBundle\Controller;


use App\RepairBundle\Form\Type\FaultReportFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use App\RepairBundle\Entity\RepairTask;
use App\RepairBundle\Entity\RepairFormGroup;
use App\RepairBundle\Entity\RepairForm;
use App\RepairBundle\Entity\FaultInfo;
use App\RepairBundle\Form\Type\FaultInfoType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;


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
     * @Route("/fault/info/{id}",name="fault_info")
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
     * @Route("/fault/receive/{id}",name="fault_receive")
     * @Template()
     */
    public function receiveAction($id)
    {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_REPAIR'))
        {
            throw new HttpException(403,'Repair Person Only');
        }
        $repairForm = $this->getDoctrine()->getManager()->getRepository('RepairBundle:RepairForm')->find($id);
        if (!$repairForm) {
            throw $this->createNotFoundException(
                'No repair form found for this id: '.$id
            );
        }
        if($repairForm->getFormCondition()->getId() == 1)
        {
            $condition = $this->getDoctrine()->getManager()->getRepository('RepairBundle:FormCondition')->find(2);
            $repairForm->setFormCondition($condition);
            $user = $this->getDoctrine()->getManager()->getRepository('UserBundle:User')->find($this->get('security.token_storage')->getToken()->getUser()->getId());
            $repairForm->setUser($user);
            $repairForm->setReceive($user);
            $repairForm->setLastUpdateTime(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->persist($repairForm);
            $em->flush();
            return $this->redirectToRoute('fault_show');
        }
        else{
            throw new HttpException(422,"This repair Form has been received.");
        }
    }

    /**
     * @Route("/fault/confirm/{id}",name="fault_confirm")
     * @Template()
     */
    public function confirmAction(Request $request,$id)
    {
        $form = $this->createForm(new FaultReportFormType(),new RepairForm());
        $form->handleRequest($request);
        $data = $form->getData();

        if ($form->isValid()) {

            $repairForm = $this->getDoctrine()->getManager()->getRepository('RepairBundle:RepairForm')->find($id);

            if($repairForm->getReceive()->getId() != $this->get('security.token_storage')->getToken()->getUser()->getId())
            {
                throw new HttpException(403,"Only receiver can confirm the repair form");
            }
            $faultInfo = $repairForm->getFaultInfo();
            $faultInfo->setWorkerDescription($data->getFaultInfo()->getWorkerDescription());
            $faultInfo->setMaintenanceSchedule($data->getFaultInfo()->getMaintenanceSchedule());
            $repairForm->setFaultInfo($faultInfo);
            $repairForm->setLastUpdateTime(new \DateTime());

            $user = $this->getDoctrine()->getManager()->getRepository('UserBundle:User')->find($this->get('security.token_storage')->getToken()->getUser()->getId());
            $repairForm->setUser($user);
            if($form->get('done')->isClicked())
            {
                $condition = $this->getDoctrine()->getManager()->getRepository('RepairBundle:FormCondition')->find(3);
                $repairForm->setFormCondition($condition);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($repairForm);
            $em->flush();


        }



        return array(
                // ...
            );    }



}
