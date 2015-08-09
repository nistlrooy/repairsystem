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
            // the validation passed, do something with the $author object


            $data = $form->getData();

            $repairForm = new RepairForm();
            //var_dump($data->getFaultType()->getId());

            if(!$data->getWorkerDescription())
                $data->setWorkerDescription(' ');
            if(!$data->getMaintenanceSchedule())
                $data->setMaintenanceSchedule(' ');

            $repairForm->setLastUpdateTime(new \DateTime());

            $condition = new FormCondition();
            $condition->getId(1);//待改，应改为getById，在repository里面添加相应函数
            $repairForm->setFormCondition($condition);

            $user = new User();
            $user->getId($this->get('security.token_storage')->getToken()->getUser()->getId());//同上
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
