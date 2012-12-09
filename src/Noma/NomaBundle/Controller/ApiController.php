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

class ApiController extends Controller
{
    protected function _getNodes($data)
    {
        $limit = 25;
        $max_limit = 100;
        $page = 1;

        $nodes = $this->getDoctrine()
            ->getRepository('NomaNomaBundle:Node');

        $q = $nodes->createQueryBuilder('n');
        $q->leftJoin('n.nodeprops', 'p');
        $q->select(array('n', 'p'));

        $q->where('1 = 1');

        // get nodes which have this nodeprop
        if (isset($data['nodeprop'])) {
            $q->andWhere('p.id = :nodeprop');
            $q->setParameter('nodeprop', $data['nodeprop']);
        }

        // exclude nodes having this nodeprop
        if (isset($data['exclude_nodeprop'])) {
            $q2 = $nodes->createQueryBuilder('n2');
            $q2->select(array('n2.id'));
            $q2->leftJoin('n2.nodeprops', 'p2');
            $q2->where('p2.id = :exclude_nodeprop');
            $q2->getDQL();
           
            $q->andWhere($q->expr()->notIn('n.id', $q2->getDQL()));
            $q->setParameter('exclude_nodeprop', $data['exclude_nodeprop']);
        }

        // clone querybuilder to count the nr of total rows
        $r = clone($q);
        $query = $r->select('count(n.id)')->getQuery();
        $total = $query->getSingleScalarResult();

        $q->addOrderBy('n.name', 'ASC');

        if (isset($data['page_limit'])) {
            $limit = abs($data['page_limit']);

            if ($limit > $max_limit) {
                $limit = $max_limit;
            }
        }

        if (isset($data['page'])) {
            $page = abs($data['page']);
        }

        $q->setFirstResult(($page - 1) * $limit);
        $q->setMaxResults($limit);

        $query = $q->getQuery();

        $array_result = $query->getArrayResult();

        return array(
            'nodes_total' => $total,
            'nodes' => $array_result
        );
    }

    protected function _getNodeProps($data)
    {
        $limit = 25;
        $max_limit = 100;
        $page = 1;

        $nodes = $this->getDoctrine()
            ->getRepository('NomaNomaBundle:NodeProp');

        $q = $nodes->createQueryBuilder('p');
        $q->leftJoin('p.nodepropdef', 'd');
        $q->leftJoin('p.nodes', 'n');
        $q->select(array('p', 'd', 'n'));
        
        if (isset($data['nodepropdef'])) {
            $q->andWhere('d.id = :nodepropdef');
            $q->setParameter('nodepropdef', $data['nodepropdef']);
        }

        if (isset($data['nodepropdefname'])) {
            $q->andWhere('d.name = :nodepropdefname');
            $q->setParameter('nodepropdefname', $data['nodepropdefname']);
        }

        if (isset($data['node'])) {
            $q->andWhere('n.id = :node');
            $q->setParameter('node', $data['node']);
        }

        $r = clone($q);
        $query = $r->select('count(n.id)')->getQuery();
        $total = $query->getSingleScalarResult();

        $q->addOrderBy('p.content', 'ASC');

        if (isset($data['page_limit'])) {
            $limit = abs($data['page_limit']);

            if ($limit > $max_limit) {
                $limit = $max_limit;
            }
        }

        if (isset($data['page'])) {
            $page = abs($data['page']);
        }

        $q->setFirstResult(($page - 1) * $limit);
        $q->setMaxResults($limit);

        $query = $q->getQuery();

        $array_result = $query->getArrayResult();

        return array(
            'nodeprops_total' => $total,
            'nodeprops' => $array_result
        );
    }

    public function jsonGetNodesAction()
    {
        $request = $this->getRequest();

        $form = $this->createFormBuilder()
            ->add('page_limit', 'integer')
            ->add('page', 'integer')
            ->add('nodeprop', 'integer')
            ->add('exclude_nodeprop', 'integer')
            ->getForm();

        if ($request->isMethod('POST')) {
            $form->bind($_POST);
        } elseif ($request->isMethod('GET')) {
            $form->bind($request->query->all());
        }

        $data = $form->getData();

        return new Response(json_encode($this->_getNodes($data)));
    }

    /**
     * Retrieve node properties
     *
     * @return Response
     */
    public function jsonGetNodePropsAction()
    {
        $request = $this->getRequest();

        $form = $this->createFormBuilder()
            ->add('page_limit', 'integer')
            ->add('page', 'integer')
            ->add('node', 'integer')
            ->add('nodepropdef', 'integer')
            ->add('nodepropdefname', 'text')
            ->getForm();

        if ($request->isMethod('POST')) {
            $form->bind($_POST);
        } elseif ($request->isMethod('GET')) {
            $form->bind($request->query->all());
        }

        $data = $form->getData();

        return new Response(json_encode($this->_getNodeProps($data)));
    }

    public function jsonNodeAddNodepropAction()
    {
        $request = $this->getRequest();

        $form = $this->createFormBuilder()
            ->add('nodeprop', 'integer')
            ->add('node', 'integer')
            ->getForm();

        if ($request->isMethod('POST')) {
            $form->bind($_POST);
        } elseif ($request->isMethod('GET')) {
            $form->bind($request->query->all());
        }

        $data = $form->getData();

        foreach (array_keys($data) as $key) {
            if (empty($data[$key])) {
                return new Response(json_encode(array('result' => 'ERROR', 'errormsg' => 'missing required argument: ' . $key)));
            }
        }

        $em = $this->getDoctrine()->getManager();

        $node = $em->getRepository('NomaNomaBundle:Node')
            ->find($data['node']);
   
        if (!$node) {
            return new Response(json_encode(array('result' => 'ERROR', 'errormsg' => 'no such node')));
        }

        $nodeprop = $em->getRepository('NomaNomaBundle:NodeProp')
            ->find($data['nodeprop']);
   
        if (!$nodeprop) {
            return new Response(json_encode(array('result' => 'ERROR', 'errormsg' => 'no such nodeprop')));
        }

        $nodeprop->addNode($node);
        $em->flush();

        return new Response(json_encode(array('result' => 'OK')));
    } 

    public function jsonNodeRemoveNodepropAction()
    {
        $request = $this->getRequest();

        $form = $this->createFormBuilder()
            ->add('nodeprop', 'integer')
            ->add('node', 'integer')
            ->getForm();

        if ($request->isMethod('POST')) {
            $form->bind($_POST);
        } elseif ($request->isMethod('GET')) {
            $form->bind($request->query->all());
        }

        $data = $form->getData();

        foreach (array_keys($data) as $key) {
            if (empty($data[$key])) {
                return new Response(json_encode(array('result' => 'ERROR', 'errormsg' => 'missing required argument: ' . $key)));
            }
        }

        $em = $this->getDoctrine()->getManager();

        $node = $em->getRepository('NomaNomaBundle:Node')
            ->find($data['node']);
   
        if (!$node) {
            return new Response(json_encode(array('result' => 'ERROR', 'errormsg' => 'no such node')));
        }

        $nodeprop = $em->getRepository('NomaNomaBundle:NodeProp')
            ->find($data['nodeprop']);
   
        if (!$nodeprop) {
            return new Response(json_encode(array('result' => 'ERROR', 'errormsg' => 'no such nodeprop')));
        }

        $nodeprop->removeNode($node);
        $em->flush();

        return new Response(json_encode(array('result' => 'OK')));
    } 
}
