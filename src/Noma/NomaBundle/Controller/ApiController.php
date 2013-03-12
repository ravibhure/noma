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
     * Return an Success message in a standardized way
     *
     * @param String $msg optional message
     *
     * @return Response encoded response
     */
    protected function _ok($msg = 'Operation Successful')
    {
        return $this->_response(array(
            'result' => 'OK',
            'errormsg' => $msg));
    }

    /**
     * Abstraction for returning responses in a standardized way
     *
     * @param Array $data Array of data to be encoded
     *
     * @return Response encoded response
     */
    protected function _response($data)
    {
        return new Response(json_encode($data));
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

        $msg = 'Assigning nodeprop successful';

        foreach (array_keys($data) as $key) {
            if (empty($data[$key])) {
                throw $this->createNotFoundException('Missing argument: ' . $key);
            }
        }

        $em = $this->getDoctrine()->getManager();

        $node = $em->getRepository('NomaNomaBundle:Node')
            ->findOneById($data['node']);

        if (!isset($node)) {
            throw $this->createNotFoundException('No such node');
        }

        $nodeprop = $em->getRepository('NomaNomaBundle:NodeProp')
            ->findOneById($data['nodeprop']);

        if (!isset($nodeprop)) {
            throw $this->createNotFoundException('No such node property');
        }

        if ($action == "remove") {
            $nodeprop->removeNode($node);
            $msg = 'Unassigning nodeprop successful';
        } else {
            $nodeprop->addNode($node);
        }

        $em->flush();

        return $this->_ok($msg);
    }

    /**
     * Retrieve array of nodes
     *
     * @return Response
     */
    public function getNodesAction()
    {
        $data = $this->_getRequestData(Array(
            Array('page_limit', 'integer'),
            Array('page', 'integer'),
            Array('nodeprop', 'integer'),
            Array('exclude_nodeprop', 'integer')
        ));

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('NomaNomaBundle:Node');

        return $this->_response($repo->getNodes($data));
    }

    /**
     * Retrieve array of node properties
     *
     * @return Response
     */
    public function getNodePropsAction()
    {
        $data = $this->_getRequestData(Array(
            Array('page_limit', 'integer'),
            Array('page', 'integer'),
            Array('node', 'integer'),
            Array('nodepropdef', 'integer'),
            Array('nodepropdefname', 'text'),
            Array('exclude_node', 'integer')
        ));

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('NomaNomaBundle:NodeProp');

        return $this->_response($repo->getNodeProps($data));
    }

    /**
     * Add nodeprop to node
     *
     * @return Response
     */
    public function nodeAddNodepropAction()
    {
        return $this->_adjustNodeprop('add');
    }

    /**
     * Remove nodeprop from node
     *
     * @return Response
     */
    public function nodeRemoveNodepropAction()
    {
        return $this->_adjustNodeprop('remove');
    }
}
