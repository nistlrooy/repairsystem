<?php

namespace App\RepairBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\RepairBundle\Entity\FaultInfo;
use App\RepairBundle\Entity\RepairForm;
use App\RepairBundle\Form\Type\FaultInfoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\BrowserKit\Request;

class DefaultController extends Controller
{


    const PAGE = 8;/*limit per page*/

    /**
     * 首页
     * @Route("/",name="homepage")
     * @Template()
     */
    public function indexAction()
    {

        if($this->get('security.authorization_checker')->isGranted('ROLE_LEADER'))
        {
            return $this->redirectToRoute('leader_order');
        }
        else{
            if($this->get('security.authorization_checker')->isGranted('ROLE_REPAIR'))
            {
                return $this->redirectToRoute('repair_homepage');
            }
            else
            {
                return $this->redirectToRoute('default_homepage');
            }
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

        $repairForm = $this->getDoctrine()->getRepository('RepairBundle:RepairForm')->getRepairFormByCreater($this->get('security.token_storage')->getToken()->getUser()->getId(),0,4,$this->get('request')->get('sort'),$this->get('request')->get('direction'));


        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $repairForm,
            $this->get('request')->query->get('page', 1)/*page number*/,
            self::PAGE/*limit per page*/
        );
        return array(

            'pagination' => $pagination,
            'form' => $form->createView(),
        );
    }




    /**
     * @Route("/repair/index",name="repair_homepage")
     * @Template()
     */
    public function repairIndexAction()
    {
        $received = $this->getDoctrine()->getRepository('RepairBundle:RepairForm')->getRepairFormByReceiver($this->get('security.token_storage')->getToken()->getUser()->getId(),2,$this->get('request')->get('sort'),$this->get('request')->get('direction'));
        $notReceived =$this->getDoctrine()->getRepository('RepairBundle:RepairForm')->getRepairFormByNotReceived($this->get('request')->get('sort2'),$this->get('request')->get('direction2'));

        $paginator  = $this->get('knp_paginator');

        $pagename1 = 'page1'; // Set custom page variable name
        $page1 = $this->get('request')->query->get($pagename1, 1); // Get custom page variable
        $pagination1 = $paginator->paginate(
            $received,
            $page1,
            self::PAGE,
            array('pageParameterName' => $pagename1)
)       ;

        $pagename2 = 'page2'; // Set another custom page variable name
        $page2 = $this->get('request')->query->get($pagename2, 1); // Get another custom page variable
        $pagination2 = $paginator->paginate(
            $notReceived,
            $page2,
            self::PAGE,
            array('pageParameterName' => $pagename2,'sortFieldParameterName'=> 'sort2','sortDirectionParameterName' => 'direction2')
)       ;

        return array(
            'received' => $pagination1,
            'notReceived' => $pagination2
        );
    }


    /**
     * @Route("/default/history",name="reported_history")
     * @Template()
     *
     */
    public function myReportedHistoryAction()
    {
        $history = $this->getDoctrine()->getRepository('RepairBundle:RepairForm')->getRepairFormByCreaterHistory($this->get('security.token_storage')->getToken()->getUser()->getId(),$this->get('request')->get('sort'),$this->get('request')->get('direction'));
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $history,
            $this->get('request')->query->get('page', 1)/*page number*/,
            self::PAGE/*limit per page*/
        );
        return array(
            'pagination' => $pagination,
        );
    }

    /**
     * @Route("/repair/history",name="repaired_history")
     * @Template()
     *
     */
    public function myRepairedHistoryAction()
    {
        $history = $this->getDoctrine()->getRepository('RepairBundle:RepairForm')->getRepairFormByReceiverHistory($this->get('security.token_storage')->getToken()->getUser()->getId(),$this->get('request')->get('sort'),$this->get('request')->get('direction'));
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $history,
            $this->get('request')->query->get('page', 1)/*page number*/,
            self::PAGE/*limit per page*/
        );
        return array(
            'pagination' => $pagination,
        );
    }


    /**
     * @Route("/leader/order",name="leader_order")
     * @Template()
     */
    public function orderIndexAction()
    {
        $order = $this->getDoctrine()->getRepository('RepairBundle:RepairForm')->getRepairFormByNeedToOrder($this->get('request')->get('sort'),$this->get('request')->get('direction'));
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $order,
            $this->get('request')->query->get('page', 1)/*page number*/,
            self::PAGE/*limit per page*/
        );
        return array(
            'pagination' => $pagination,
        );
    }





}
