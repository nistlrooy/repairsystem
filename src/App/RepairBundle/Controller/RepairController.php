<?php

namespace App\RepairBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use App\RepairBundle\Entity\RepairTask;
use App\RepairBundle\Entity\RepairFormGroup;
use App\RepairBundle\Entity\RepairForm;
use App\RepairBundle\Form\Type\FaultReportFormType;
use Symfony\Component\HttpFoundation\Request;


class RepairController extends Controller
{
    /**
     * @Route("/post",name="repair_report")
     * @Template()
     */
    public function postAction(Request $request)
    {



        $form = $this->createForm(new FaultReportFormType(), new RepairForm());
        $form->handleRequest($request);

        if ($form->isValid()) {
            // the validation passed, do something with the $author object
            $data = $form->getData();
            $repairForm = new RepairForm();
            $repairForm->getFaultInfo()->setReporterDescription($data['reporterDescription']);
            $repairForm->getFaultInfo()->setLocationId($data['location']);
            $repairForm->getFaultInfo()->setPriorityId($data['priority']);
            $repairForm->getFaultInfo()->setTypeId($data['faultType']);

            $em = $this->getDoctrine()->getManager();
            $em->persist($repairForm);
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
