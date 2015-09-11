<?php
    namespace App\RepairBundle\EventListener;

    use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

    class PreContentViewListener
    {
        public function onPreContentView(GetResponseForExceptionEvent $event)
        {


            // Send the modified response object to the event
            $event->setResponse($response);
        }
    }