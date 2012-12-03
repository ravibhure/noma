<?php

namespace Noma\NomaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $nodes = $this->getDoctrine()
            ->getRepository('NomaNomaBundle:Node')
            ->findAll();

        if (!$nodes) {
            throw $this->createNotFoundException('No nodes found.');
        }

        return $this->render('NomaNomaBundle:Default:index.html.twig', array('nodes' => $nodes));
    }
}
