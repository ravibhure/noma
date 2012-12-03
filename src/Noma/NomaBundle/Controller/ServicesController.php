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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Noma\NomaBundle\Entity\NodeProp;
use Noma\NomaBundle\Entity\NodePropDef;

class ServicesController extends Controller
{
    public function indexAction(Request $request)
    {
        $nodepropdef = $this->getDoctrine()
            ->getRepository('NomaNomaBundle:NodePropDef')
            ->findOneByName('service');

        if (!$nodepropdef) {
            $n = new NodePropDef();
            $n->setName('service');

            $em = $this->getDoctrine()->getManager();
            $em->persist($n);
            $em->flush();

            $nodepropdef = $n;
        }

        $np = new NodeProp();
        $np->setNodePropDef($nodepropdef);
        $form = $this->createFormBuilder($np)
            ->add('content', 'text', array('label' => 'Service'))
            ->getForm();

        if ($request->isMethod('POST')) {
            $form->bind($request);
            if ($form->isValid()) {
                $s = new NodeProp();
                $s->setNodePropDef($nodepropdef);
                $s->setContent($form->get('content')->getData());

                $em = $this->getDoctrine()->getManager();
                $em->persist($s);
                $em->flush();
            }
        }

        $nodeprops = $nodepropdef->getNodeProps();

        return $this->render('NomaNomaBundle:Services:index.html.twig', array(
                'nodepropdef' => $nodepropdef,
                'nodeprops' => $nodeprops,
                'form' => $form->createView()
            ));
    }
}
