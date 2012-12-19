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

class NodePropDefsController extends Controller
{
    public function indexAction(Request $request)
    {
        $db = $this->getDoctrine()
            ->getRepository('NomaNomaBundle:NodePropDef');
        $query = $db->createQueryBuilder('p')
            ->where('p.name != :name')
            ->setParameter('name', 'service')
            ->andWhere('p.active = :active')
            ->setParameter('active', '1')
            ->getQuery();

		$activenodepropdef = $query->getResult();

        $np = new NodePropDef();
        $form = $this->createFormBuilder($np)
            ->add('name', 'text', array('label' => 'nodepropdef'))
            ->add('single', 'checkbox', array('label' => 'single', 'required' => false))
            ->add('active', 'checkbox', array('label' => 'active', 'required' => false))
            ->getForm();
        
        if ($request->isMethod('POST')) {
            $form->bind($request);
            if ($form->isValid()) {
                $s = new NodePropDef();
                $s->setName($form->get('name')->getData());
                $s->setSingle($form->get('single')->getData());
                $s->setActive($form->get('active')->getData());

                $em = $this->getDoctrine()->getManager();
                $em->persist($s);
                $em->flush();
            }
        }

        return $this->render('NomaNomaBundle:NodePropDefs:index.html.twig', array(
                'activenodepropdef' => $activenodepropdef,
                'form' => $form->createView()));
       
    }

    public function getInactiveAction()
    {
        $db = $this->getDoctrine()
            ->getRepository('NomaNomaBundle:NodePropDef');
        $query = $db->createQueryBuilder('p')
            ->where('p.name != :name')
            ->setParameter('name', 'service')
            ->andWhere('p.active = :active')
            ->setParameter('active', '0')
            ->getQuery();

        $inactivenodepropdef = $query->getResult();

        return $this->render('NomaNomaBundle:NodePropDefs:inactivenodepropdef.html.twig', array(
            'inactivenodepropdef' => $inactivenodepropdef));
  
    }
}
