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


    /**
     * @Route("/statistics",name="statistics")
     * @Template()
     */
    public function statisticsAction()
    {
        if(!$this->isGranted('ROLE_REPAIR_ADMIN'))
        {
            throw $this->createAccessDeniedException('Unauthorized access!');
        }
        //获取各个类型数量
        $type = $this->getDoctrine()->getRepository('RepairBundle:RepairForm')->getRepairFormNumberOfAllType();
        $numberOfType = 0;
        foreach($type as $arr)
        {
            $numberOfType = $arr[0][1]+$numberOfType;
        }
        //获取各状态统计
        $status = $this->getDoctrine()->getRepository('RepairBundle:RepairForm')->getRepairFormNumberOfAllStatus();
        //获取所在地统计
        $group = $this->getDoctrine()->getRepository('RepairBundle:RepairForm')->getRepairFormNumberOfAllGroup();
        $numberOfGroup = 0;
        foreach($group as $arr)
        {
            $numberOfGroup = $numberOfGroup + $arr;
        }

        $cost = $this->getDoctrine()->getRepository('RepairBundle:RepairForm')->getRepairFormCost();

        $repair =$this->getDoctrine()->getRepository('RepairBundle:RepairForm')->getRepairTop();

        $guess = $this->getDoctrine()->getRepository('RepairBundle:RepairForm')->guess();
        $numberOfGuess = 0;
        foreach($guess as $key=>$value)
        {
            $numberOfGuess = $numberOfGuess + $value['number'];
        }

        return array(
            'numberOfType' => $numberOfType,
            'numberOfGroup' => $numberOfGroup,
            'numberOfGuess' => $numberOfGuess,
            'repairFormNumberOfAllGroup' =>$group,
            'repairFormNumberOfAllStatus' => $status,
            'repairFormNumberOfAllType' => $type,
            'repairFormCost' => $cost,
            'repairPerson' => $repair,
            'guess'=>$guess

        );
    }


    /**
     * @Route("/supplier",name="supplier")
     * @Template()
     * @return array
     */
    public function supplierIndexAction()
    {
        $supplier = $this->getDoctrine()->getRepository('RepairBundle:Supplier')->getSuppliers();
        return array(
            'supplier'=>$supplier
        );
    }



}
