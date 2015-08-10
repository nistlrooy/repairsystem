<?php

namespace App\RepairBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use App\RepairBundle\Entity\RepairTask;
use App\RepairBundle\Entity\RepairFormGroup;
use App\RepairBundle\Entity\RepairForm;
use App\RepairBundle\Entity\FaultInfo;
use App\RepairBundle\Entity\FormCondition;
use App\UserBundle\Entity\User;
use App\RepairBundle\Form\Type\FaultReportFormType;
use App\RepairBundle\Form\Type\FaultInfoType;
use Symfony\Component\HttpFoundation\Request;


class RepairController extends Controller
{
    /**
     * @Route("/post",name="repair_report")
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
            var_dump($data);
            die;
            $repairForm = new RepairForm();
            //var_dump($data->getFaultType()->getId());
            //默认设置为一个空格
            if(!$data->getWorkerDescription())
                $data->setWorkerDescription(' ');
            if(!$data->getMaintenanceSchedule())
                $data->setMaintenanceSchedule(' ');
            //set datetime to now
            $repairForm->setLastUpdateTime(new \DateTime());

            //find id = 1 ,get the obj and update
            $condition = $this->getDoctrine()->getManager()->getRepository('RepairBundle:FormCondition')->find(1);
            $repairForm->setFormCondition($condition);

            //get current user id and update
            $user = $this->getDoctrine()->getManager()->getRepository('UserBundle:User')->find($this->get('security.token_storage')->getToken()->getUser()->getId());
            $repairForm->setUser($user);

            $em = $this->getDoctrine()->getManager();
            $em->persist($repairForm);
            $em->persist($data);

            $em->flush();



        }
        return array(
            'form' => $form->createView(),
            );
    }

    /**
     * @Route("/receive")
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
