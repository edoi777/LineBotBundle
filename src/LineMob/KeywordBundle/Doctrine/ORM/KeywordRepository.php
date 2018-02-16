<?php

declare(strict_types=1);

namespace LineMob\KeywordBundle\Doctrine\ORM;

use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class KeywordRepository extends EntityRepository implements KeywordRepositoryInterface
{
    /**
     * @return QueryBuilder
     */
    public function createListQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('o')
            ->leftJoin('o.additionalKeywords', 'additionalKeyword')
            ->groupBy('o.id')
            ;
    }
}
