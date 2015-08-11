<?php

namespace App\RepairBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use App\RepairBundle\Entity\RepairTask;
use App\RepairBundle\Entity\RepairFormGroup;
use App\RepairBundle\Entity\RepairForm;
use App\RepairBundle\Entity\FaultInfo;
use App\RepairBundle\Form\Type\FaultInfoType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\IsNull;


class RepairController extends Controller
{
    /**
     * @Route("/fault/post",name="fault_report")
     * @Template()
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
            //默认设置为一个空格
            if(!$data->getWorkerDescription())
                $data->setWorkerDescription(' ');
            if(!$data->getMaintenanceSchedule())
                $data->setMaintenanceSchedule(' ');

            //set datetime to now
            $repairForm->setLastUpdateTime(new \DateTime());
            $repairTask->setCreateTime(new \Datetime());

            //find id = 1 ,get the obj and update
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
     * @Route("/fault/show/{id}")
     * @Template()
     */
    public function showAction($id)
    {
        $repairForm = $this->getDoctrine()->getManager()->getRepository('RepairBundle:RepairForm')->find($id);
        if (!$repairForm) {
            throw $this->createNotFoundException(
                'No repair form found for this id: '.$id
            );
        }

        $orderIsNull = false;
       if($repairForm->getfaultInfo()->getFaultOrder() == Null);
        {
            $orderIsNull=true;

        }

        return array(
            'repairForm'=>$repairForm,
            'orderIsNull'=>$orderIsNull
        );
    }


    /**
     * @Route("/fault/receive/{id}")
     * @Template()
     */
    public function receiveAction()
    {








        return array(
                // ...
            );    }

    /**
     * @Route("/confirm")
     * @Template()
     */
    public function confirmAction()
    {
        return array(
                // ...
            );    }

}
