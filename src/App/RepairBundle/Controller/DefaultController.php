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
     * 首页
     * @Route("/",name="homepage")
     * @Template()
     */
    public function indexAction()
    {

        if($this->get('security.authorization_checker')->isGranted('ROLE_REPAIR'))
        {
            return $this->redirectToRoute('repair_homepage');
        }
        else
        {
            return $this->redirectToRoute('default_homepage');
        }

    }

    /**
     *
     * @Route("/default/index",name="default_homepage")
     * @Template()
     */
    public function defaultIndexAction()
    {

        $form = $this->createForm(new FaultInfoType(),new FaultInfo());

        $repairForm = $this->getDoctrine()->getRepository('RepairBundle:RepairForm')->getRepairFormByCreater($this->get('security.token_storage')->getToken()->getUser()->getId(),1);

        return array(
            'repairForm' => $repairForm,
            'form' => $form->createView(),
        );
    }




    /**
     * @Route("/repair/index",name="repair_homepage")
     * @Template()
     */
    public function repairIndexAction()
    {
        $received = $this->getDoctrine()->getRepository('RepairBundle:RepairForm')->getRepairFormByReceiver($this->get('security.token_storage')->getToken()->getUser()->getId(),2);
        $notReceived =$this->getDoctrine()->getRepository('RepairBundle:RepairForm')->getRepairFormByNotReceived();
        return array(
            'received' => $received,
            'notReceived' => $notReceived
        );
    }


    /**
     * @Route("/default/history",name="reported_history")
     * @Template()
     *
     */
    public function MyReportedHistoryAction()
    {
        $history = $this->getDoctrine()->getRepository('RepairBundle:RepairForm')->getRepairFormByCreaterHistory($this->get('security.token_storage')->getToken()->getUser()->getId());
        return array(
            'history'=>$history
        );
    }

    /**
     * @Route("/repair/history",name="repaired_history")
     * @Template()
     *
     */
    public function MyRepairedHistoryAction()
    {
        $history = $this->getDoctrine()->getRepository('RepairBundle:RepairForm')->getRepairFormByReceiverHistory($this->get('security.token_storage')->getToken()->getUser()->getId());
        return array(
            'history'=>$history
        );
    }

}
