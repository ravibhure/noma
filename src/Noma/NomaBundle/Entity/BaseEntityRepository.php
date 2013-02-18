<?php

namespace Noma\NomaBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

abstract class BaseEntityRepository extends EntityRepository
{
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
    protected function _getStdQueryBuilder(Array $data)
    {
        $limit = 25;
        $max_limit = 100;
        $page = 1;

        $q = $this->createQueryBuilder('e');

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
}
