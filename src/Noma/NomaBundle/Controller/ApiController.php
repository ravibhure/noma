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
            $request_data = $request->request->all();
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
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('NomaNomaBundle:Node');
        return $repo->getNodes($data);
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
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('NomaNomaBundle:NodeProp');
        return $repo->getNodeProps($data);

    }

    protected function _jsonError($msg)
    {
        return $this->_jsonResponse(array(
            'result' => 'ERROR',
            'errormsg' => $msg));
    }

    protected function _jsonResponse($data)
    {
        return new Response(json_encode($data));
    }

    protected function _xmlResponse($data)
    {
        $xml = new SimpleXMLElement('<root/>');
        array_walk_recursive($data, array ($xml, 'addChild'));
        return new Response($xml->asXML());
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
                return $this->_jsonError('missing required argument: ' . $key);
            }
        }

        $em = $this->getDoctrine()->getManager();

        $node = $em->getRepository('NomaNomaBundle:Node')
            ->find($data['node']);

        if (!$node) {
            return $this->_jsonError('no such node');
        }

        $nodeprop = $em->getRepository('NomaNomaBundle:NodeProp')
            ->find($data['nodeprop']);

        if (!$nodeprop) {
            return $this->_jsonError('no such nodeprop');
        }

        if ($action == "remove") {
            $nodeprop->removeNode($node);
        } else {
            $nodeprop->addNode($node);
        }

        $em->flush();

        return (array('result' => 'OK'));
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

        return $this->_jsonResponse($this->_getNodes($data));
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

        return $this->_jsonResponse($this->_getNodeProps($data));
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
