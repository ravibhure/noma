<?php
namespace Noma\NomaBundle\Entity;

class NodeRepository extends BaseEntityRepository
{
    /**
     * Retrieve array of nodes
     *
     * @param Array $data array of filters
     *
     * @return Array
     */
    public function getNodes($data)
    {
        $qb = $this->_getStdQueryBuilder($data);

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
}
