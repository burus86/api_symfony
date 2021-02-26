<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class ProductRepository
 * @package App\Repository
 */
class ProductRepository extends ServiceEntityRepository
{
    use BaseEntityRepositoryTrait;

    /**
     * ProductRepository constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @param string $field
     * @param string|null $order
     * @return QueryBuilder
     */
    public function findAllSortedByField(string $field, ?string $order = 'ASC'): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('p');
        return $this->setOrderByField($queryBuilder, $field, $order);
    }

    /**
     * @param bool|null $featured
     * @return Product[]
     */
    public function findAllByFeatured(?bool $featured = true): array
    {
        $queryBuilder = $this->findAllSortedByField($field = 'p.name');
        return $this->addFilterByFeatured($queryBuilder, $featured)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param bool|null $featured
     * @return QueryBuilder
     */
    private function addFilterByFeatured(QueryBuilder $queryBuilder, ?bool $featured = true): QueryBuilder
    {
        return $this->addFilterByField($queryBuilder, $field = 'p.featured', $value = $featured);
    }
}
