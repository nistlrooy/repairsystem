<?php

namespace App\RepairBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\RepairBundle\Entity\FaultInfo;
use App\RepairBundle\Entity\RepairForm;
use App\RepairBundle\Form\Type\FaultInfoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/",name="homepage")
     * @Template()
     */
    public function indexAction()
    {
        $form = $this->createForm(new FaultInfoType(),new FaultInfo());

        $repairForm = $this->getDoctrine()->getRepository('RepairBundle:RepairForm')->getRepairForms($this->get('security.token_storage')->getToken()->getUser()->getId(),1);

        return array(
            'repairForm' => $repairForm,
            'form' => $form->createView(),
        );
    }
}
