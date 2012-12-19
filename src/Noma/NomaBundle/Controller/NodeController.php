<?php
/**
 * Noma
 *
 * LICENSE
 *
 * This source file is subject to the GPLv3 license that is bundled
 * with this package in the file doc/GPLv3.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @copyright  Copyright (c) 2012 Jochem Kossen <jochem@jkossen.nl>
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt GPLv3
 */

namespace Noma\NomaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Noma\NomaBundle\Entity\Node;

class NodeController extends Controller
{
    public function indexAction(Request $request)
    {
        $nodes = $this->getDoctrine()
            ->getRepository('NomaNomaBundle:Node')
            ->findAll();

        if (!$nodes) {
            throw $this->createNotFoundException('No nodes found.');
        }

        $node = new Node();
        $form = $this->createFormBuilder($node)
            ->add('name', 'text', array('label' => 'hostname'))
            ->add('ip', 'text', array('label' => 'ip address'))
            ->getForm();
        
        if ($request->isMethod('POST')) {
            $form->bind($request);
            if ($form->isValid()) {
                $n = new Node();
                $n->setName($form->get('name')->getData());
                $n->setIp($form->get('ip')->getData());
                $n->setStatus('0');
                $em = $this->getDoctrine()->getManager();
                $em->persist($n);
                $em->flush();
            }
        }
        
        return $this->render('NomaNomaBundle:Node:index.html.twig',
            array('nodes' => $nodes, 'form' => $form->createView()));
    }
}
