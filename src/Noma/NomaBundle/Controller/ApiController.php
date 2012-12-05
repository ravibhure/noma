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

        if (isset($data['nodeprop'])) {
            $q->andWhere('p.id = :nodeprop');
            $q->setParameter('nodeprop', $data['nodeprop']);
        }

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

    public function jsonGetNodesAction()
    {
        $request = $this->getRequest();

        $form = $this->createFormBuilder()
            ->add('page_limit', 'integer')
            ->add('page', 'integer')
            ->add('nodeprop', 'integer')
            ->getForm();

        if ($request->isMethod('POST')) {
            $form->bind($_POST);
        } elseif ($request->isMethod('GET')) {
            $form->bind($request->query->all());
        }

        $data = $form->getData();

        return new Response(json_encode($this->_getNodes($data)));
    }

}
