<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\BlogPost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BlogPost>
 *
 * @method BlogPost|null find($id, $lockMode = null, $lockVersion = null)
 * @method BlogPost|null findOneBy(array $criteria, array $orderBy = null)
 * @method BlogPost[]    findAll()
 * @method BlogPost[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlogPostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BlogPost::class);
    }

    public function delete(BlogPost $blogPost): void
    {
        $this->_em->remove($blogPost);
        $this->_em->flush();
    }

    /**
     * @param array<string, mixed>       $criteria
     * @param array<string, string>|null $orderBy
     */
    public function findByQuery(array $criteria = [], array $orderBy = null, int $limit = null, int $offset = null): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('bp')
            ->orderBy('bp.createdAt', 'DESC');

        if (!empty($criteria)) {
            foreach ($criteria as $field => $value) {
                $queryBuilder->andWhere("bp.$field = :$field")
                    ->setParameter($field, $value);
            }
        }

        if (!empty($orderBy)) {
            foreach ($orderBy as $field => $direction) {
                $queryBuilder->addOrderBy("bp.$field", $direction);
            }
        }

        if (null !== $limit) {
            $queryBuilder->setMaxResults($limit);
        }

        if (null !== $offset) {
            $queryBuilder->setFirstResult($offset);
        }

        return $queryBuilder;
    }

    /**
     * @param array<string, mixed>       $criteria
     * @param array<string, string>|null $orderBy
     *
     * @return float|int|mixed|string|null
     *
     * @throws NonUniqueResultException
     */
    public function findOneByQuery(array $criteria, array $orderBy = null): mixed
    {
        return $this->findByQuery($criteria, $orderBy)->getQuery()->getOneOrNullResult();
    }

    public function save(BlogPost $blogPost): void
    {
        $this->_em->persist($blogPost);
        $this->_em->flush();
    }

    //    /**
    //     * @return BlogPost[] Returns an array of BlogPost objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('b.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?BlogPost
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
