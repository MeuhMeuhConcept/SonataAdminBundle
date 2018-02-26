<?php

namespace MMC\SonataAdminBundle\Datagrid;

use Doctrine\ORM\Query;

class PagerForDTO extends PagerForGroupByQuery
{
    public function getResults($hydrationMode = Query::HYDRATE_OBJECT)
    {
        $res = $this->getQuery()->execute([], $hydrationMode);

        $results = [];
        foreach ($res as $data) {
            if (is_array($data) && isset($data['object'])) {
                $results[] = $data['object'];
            } else {
                return $res;
            }
        }

        return $results;
    }

    protected function retrieveObject($offset)
    {
        $queryForRetrieve = clone $this->getQuery();
        $queryForRetrieve
            ->setFirstResult($offset - 1)
            ->setMaxResults(1);

        $results = $queryForRetrieve->execute();

        $data = $results[0];

        if (is_array($data) && isset($data['object'])) {
            return $data['object'];
        }

        return $data;
    }
}
