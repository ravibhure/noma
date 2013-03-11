<?php
namespace Noma\NomaBundle\Entity;

class NodePropRepository extends BaseEntityRepository
{
    public function getNodeProps($data)
    {
        $qb = $this->_getStdQueryBuilder($data);

        $q = $qb['q'];

        $q->leftJoin('e.nodepropdef', 'd');
        $q->leftJoin('e.nodes', 'n');
        $q->where('1 = 1');

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
            $q2 = $this->createQueryBuilder('n2');
            $q2->select(array('n2.id'));
            $q2->leftJoin('n2.nodes', 'p2');
            $q2->where('p2.id = :exclude_node');
            $q2->getDQL();

            $q->andWhere($q->expr()->notIn('e.id', $q2->getDQL()));
            $q->setParameter('exclude_node', $data['exclude_node']);
        }

        $total = $this->_getResultCount($q);

        $q->select(array('e.id', 'd.id as nodepropdef_id', 'd.name as nodepropdef_name',
            'e.content', 'e.created', 'e.updated', 'count(n.id) as nodecount'));
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
}
