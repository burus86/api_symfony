<?php

namespace App\Repository;

use Doctrine\ORM\QueryBuilder;

/**
 * Trait BaseEntityRepositoryTrait
 * @package App\Repository
 */
trait BaseEntityRepositoryTrait
{
    /**
     * @param QueryBuilder $qBuilder
     * @param string $field
     * @param string|null $order
     * @return QueryBuilder
     */
    public function setOrderByField(QueryBuilder $qBuilder, string $field, ?string $order = 'ASC'): QueryBuilder
    {
        return $qBuilder->orderBy($field, $order);
    }

    /**
     * @param QueryBuilder $qBuilder
     * @param string $field
     * @return QueryBuilder
     */
    public function addFilterByFieldNotNull(QueryBuilder $qBuilder, string $field): QueryBuilder
    {
        $qBuilder->andWhere($qBuilder->expr()->isNotNull($field));

        return $qBuilder;
    }

    /**
     * @param QueryBuilder $qBuilder
     * @param string $field
     * @param string $value
     * @return QueryBuilder
     */
    public function addFilterByField(QueryBuilder $qBuilder, string $field, string $value): QueryBuilder
    {
        list(, $parameter) = explode('.', $field);
        $qBuilder
            ->andWhere($qBuilder->expr()->eq($field, ":{$parameter}"))
            ->setParameter($parameter, $value)
        ;

        return $qBuilder;
    }

    /**
     * @param QueryBuilder $qBuilder
     * @param string $field
     * @param string $value
     * @return QueryBuilder
     */
    public function addFilterByFieldAndNotNull(QueryBuilder $qBuilder, string $field, string $value): QueryBuilder
    {
        $this->addFilterByFieldNotNull($qBuilder, $field);
        $this->addFilterByField($qBuilder, $field, $value);

        return $qBuilder;
    }
}
