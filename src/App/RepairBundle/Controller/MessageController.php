<?php

namespace App\RepairBundle\Controller;

use App\RepairBundle\Entity\RepairMessage;
use App\RepairBundle\Form\Type\RepairMessageType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;

class MessageController extends Controller
{

    const PAGE = 8;/*limit per page*/

    /**
     * @Route("/message/index",name="personal_message")
     * @Template()
     *
     */
    public function indexAction()
    {
        $message = $this->getDoctrine()->getRepository('RepairBundle:RepairMessage')->getMyMessage($this->get('security.token_storage')->getToken()->getUser()->getId());

        $authChecker = $this->get('security.authorization_checker');

        if(false === $authChecker->isGranted('view',$message[0]))
        {
            throw $this->createAccessDeniedException('Unauthorized access!');
        }

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $message,
            $this->get('request')->query->get('page', 1)/*page number*/,
            self::PAGE/*limit per page*/
        );
        $msgForm = $this->createForm(new RepairMessageType(),new RepairMessage());
        return array(
            'message' => $msgForm->createView(),
            'pagination' => $pagination,
        );
    }


    /**
     * @Route("/message/{id}",requirements={"id":"\d+"},name="message_view")
     *
     * @param $id
     * @return JsonResponse
     * @throws \Exception
     */
    public function viewAction($id)
    {
        $message = $this->getDoctrine()->getManager()->getRepository('RepairBundle:RepairMessage')->find($id);
        if (!$message) {
            throw $this->createNotFoundException(
                'No Message found for this id: '.$id
            );
        }
        $authChecker = $this->get('security.authorization_checker');

        if(false === $authChecker->isGranted('view',$message))
        {
            throw $this->createAccessDeniedException('Unauthorized access!');
        }

        $message->setIsRead(true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($message);
        $em->flush();

        $response = new JsonResponse();
        $response->setData(array(
            'status' => 200
        ));

        return $response;
    }



    /**
     * @Route("/message/delete/{id}",requirements={"id":"\d+"},name="message_delete")
     *
     *
     */
    public function deleteAction($id)
    {
        $message = $this->getDoctrine()->getManager()->getRepository('RepairBundle:RepairMessage')->find($id);
        if (!$message) {
            throw $this->createNotFoundException(
                'No Message found for this id: '.$id
            );
        }
        $authChecker = $this->get('security.authorization_checker');

        if(false === $authChecker->isGranted('delete',$message))
        {
            throw $this->createAccessDeniedException('Unauthorized access!');
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($message);
        $em->flush();

        $response = new JsonResponse();
        $response->setData(array(
            'status' => 200
        ));

        return $response;
    }



}
