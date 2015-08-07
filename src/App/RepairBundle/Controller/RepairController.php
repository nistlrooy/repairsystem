<?php

namespace App\RepairBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class RepairController extends Controller
{
    /**
     * @Route("/post")
     * @Template()
     */
    public function postAction()
    {


        return array(
                // ...
            );    }

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
