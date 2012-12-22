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
    /**
     * Get the GET or POST data
     *
     * Determine if it's a POST or GET request, then parse and return data
     *
     * @param Request $request
     *
     * @return Array request data
     */
    protected function _getRequestData($form_fields)
    {
        $request = $this->getRequest();

        $fb = $this->createFormBuilder();
        foreach ($form_fields as $field) {
            $fb->add($field[0], $field[1]);
        }

        $form = $fb->getForm();

        if ($request->isMethod('POST')) {
            $request_data = $request;
        } else {
            $request_data = $request->query->all();
        }

        $form->bind($request_data);

        return $form->getData();
    }

    /**
     * Create QueryBuilder with some standard options
     *
     * This function creates a new QueryBuilder based on the given entity and
     * data array
     *
     * @param String $entity main entity to initialize QueryBuilder
     * @param Array $data array with desired page nr and items per page limit
     *
     * @return Array querybuilder, first_result and limit
     */
    protected function _getStdQueryBuilder($entity, $data)
    {
        $limit = 25;
        $max_limit = 100;
        $page = 1;

        $ci = $this->getDoctrine()
            ->getRepository('NomaNomaBundle:' . $entity);

        $q = $ci->createQueryBuilder('e');

        $q->where('1 = 1');

        if (isset($data['page_limit'])) {
            $limit = intval(abs($data['page_limit']));

            if ($limit > $max_limit) {
                $limit = $max_limit;
            }

            if ($limit < 1) {
                $limit = 1;
            }
        }

        if (isset($data['page'])) {
            $page = intval(abs($data['page']));
        }

        if ($page < 1) {
            $page = 1;
        }

        $first_result = ($page - 1) * $limit;

        return (array(
            'q' => $q,
            'first_result' => $first_result,
            'limit' => $limit
        ));
    }

    /**
     * Count the number of records based on the given QueryBuilder
     *
     * @param QueryBuilder $q
     *
     * @return integer number of records
     */
    protected function _getResultCount($q)
    {
        $r = clone($q);
        $r->select('count(e.id)');
        $query = $r->getQuery();
        return $query->getSingleScalarResult();
    }

    /**
     * Retrieve array of nodes
     *
     * @param Array $data array of filters
     *
     * @return Array
     */
    protected function _getNodes($data)
    {
        $qb = $this->_getStdQueryBuilder('Node', $data);

        $q = $qb['q'];

        $q->leftJoin('e.nodeprops', 'p');

        $q->where('1 = 1');

        // get nodes which have this nodeprop
        if (isset($data['nodeprop'])) {
            $q->andWhere('p.id = :nodeprop');
            $q->setParameter('nodeprop', $data['nodeprop']);
        }

        // exclude nodes having this nodeprop
        if (isset($data['exclude_nodeprop'])) {
            $ci = $this->getDoctrine()->getRepository('NomaNomaBundle:Node');
            $q2 = $ci->createQueryBuilder('n2');
            $q2->select(array('n2.id'));
            $q2->leftJoin('n2.nodeprops', 'p2');
            $q2->where('p2.id = :exclude_nodeprop');
            $q2->getDQL();

            $q->andWhere($q->expr()->notIn('e.id', $q2->getDQL()));
            $q->setParameter('exclude_nodeprop', $data['exclude_nodeprop']);
        }

        $total = $this->_getResultCount($q);

        $q->select(array('e.id', 'e.name', 'e.ip', 'e.active',
            'e.created', 'e.updated', 'count(p.id) as propcount'));
        $q->groupBy('e');
        $q->addOrderBy('e.name', 'ASC');

        $q->setFirstResult($qb['first_result']);
        $q->setMaxResults($qb['limit']);

        $query = $q->getQuery();

        $array_result = $query->getArrayResult();

        return array(
            'nodes_total' => $total,
            'nodes' => $array_result
        );
    }

    /**
     * Retrieve array of node properties
     *
     * @param Array $data array of filters
     *
     * @return Array
     */
    protected function _getNodeProps($data)
    {
        $qb = $this->_getStdQueryBuilder('NodeProp', $data);

        $q = $qb['q'];

        $q->leftJoin('e.nodepropdef', 'd');
        $q->leftJoin('e.nodes', 'n');

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

        // exclude nodeprops having this node
        if (isset($data['exclude_node'])) {
            $ci = $this->getDoctrine()->getRepository('NomaNomaBundle:NodeProp');
            $q2 = $ci->createQueryBuilder('n2');
            $q2->select(array('n2.id'));
            $q2->leftJoin('n2.nodes', 'p2');
            $q2->where('p2.id = :exclude_node');
            $q2->getDQL();

            $q->andWhere($q->expr()->notIn('e.id', $q2->getDQL()));
            $q->setParameter('exclude_node', $data['exclude_node']);
        }

        $total = $this->_getResultCount($q);

        $q->select(array('e.id', 'd.id as nodepropdef_id', 'd.name as nodepropdef_name', 'e.content', 'e.created', 'e.updated', 'count(n.id) as nodecount'));
        $q->groupBy('e');
        $q->addOrderBy('e.content', 'ASC');

        $q->setFirstResult($qb['first_result']);
        $q->setMaxResults($qb['limit']);

        $query = $q->getQuery();

        $array_result = $query->getArrayResult();

        return array(
            'nodeprops_total' => $total,
            'nodeprops' => $array_result
        );
    }

    /**
     * Add or remove nodeprop to/from node
     *
     * @return Response
     */
    protected function _adjustNodeprop($action)
    {
        $data = $this->_getRequestData(Array(
            Array('nodeprop', 'integer'),
            Array('node', 'integer')
        ));

        foreach (array_keys($data) as $key) {
            if (empty($data[$key])) {
                return new Response(json_encode(array('result' => 'ERROR',
                    'errormsg' => 'missing required argument: ' . $key)));
            }
        }

        $em = $this->getDoctrine()->getManager();

        $node = $em->getRepository('NomaNomaBundle:Node')
            ->find($data['node']);

        if (!$node) {
            return new Response(json_encode(array('result' => 'ERROR',
                'errormsg' => 'no such node')));
        }

        $nodeprop = $em->getRepository('NomaNomaBundle:NodeProp')
            ->find($data['nodeprop']);

        if (!$nodeprop) {
            return new Response(json_encode(array('result' => 'ERROR',
                'errormsg' => 'no such nodeprop')));
        }

        if ($action == "remove") {
            $nodeprop->removeNode($node);
        } else {
            $nodeprop->addNode($node);
        }

        $em->flush();

        return new Response(json_encode(array('result' => 'OK')));
    }

    /**
     * Retrieve json encoded array of nodes
     *
     * @return Response
     */
    public function jsonGetNodesAction()
    {
        $data = $this->_getRequestData(Array(
            Array('page_limit', 'integer'),
            Array('page', 'integer'),
            Array('nodeprop', 'integer'),
            Array('exclude_nodeprop', 'integer')
        ));

        return new Response(json_encode($this->_getNodes($data)));
    }

    /**
     * Retrieve json encoded array of node properties
     *
     * @return Response
     */
    public function jsonGetNodePropsAction()
    {
        $data = $this->_getRequestData(Array(
            Array('page_limit', 'integer'),
            Array('page', 'integer'),
            Array('node', 'integer'),
            Array('nodepropdef', 'integer'),
            Array('nodepropdefname', 'text'),
            Array('exclude_node', 'integer')
        ));

        return new Response(json_encode($this->_getNodeProps($data)));
    }

    /**
     * Add nodeprop to node
     *
     * @return Response
     */
    public function jsonNodeAddNodepropAction()
    {
        return $this->_adjustNodeprop('add');
    }

    /**
     * Remove nodeprop from node
     *
     * @return Response
     */
    public function jsonNodeRemoveNodepropAction()
    {
        return $this->_adjustNodeprop('remove');
    }
}
