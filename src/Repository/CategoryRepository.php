<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class CategoryRepository
 * @package App\Repository
 */
class CategoryRepository extends ServiceEntityRepository
{
    use BaseEntityRepositoryTrait;

    /**
     * CategoryRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * @param string $name
     * @return Category|null
     * @throws NonUniqueResultException
     */
    public function findOneByName(string $name): ?Category
    {
        $queryBuilder = $this->createQueryBuilder('c');
        return $this->addFilterByName($queryBuilder, $name)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param string $name
     * @return QueryBuilder
     */
    private function addFilterByName(QueryBuilder $queryBuilder, string $name): QueryBuilder
    {
        return $this->addFilterByField($queryBuilder, $field = 'c.name', $value = $name);
    }
}
