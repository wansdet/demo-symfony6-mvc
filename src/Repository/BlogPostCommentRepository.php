<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\BlogPostComment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BlogPostComment>
 *
 * @method BlogPostComment|null find($id, $lockMode = null, $lockVersion = null)
 * @method BlogPostComment|null findOneBy(array $criteria, array $orderBy = null)
 * @method BlogPostComment[]    findAll()
 * @method BlogPostComment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlogPostCommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BlogPostComment::class);
    }

    public function delete(BlogPostComment $blogPostComment): void
    {
        $this->_em->remove($blogPostComment);
        $this->_em->flush();
    }

    /**
     * @param array<string, mixed>       $criteria
     * @param array<string, string>|null $orderBy
     */
    public function findByQuery(array $criteria = [], array $orderBy = null, int $limit = null, int $offset = null): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('bpc')
            ->orderBy('bpc.createdAt', 'DESC');

        if (!empty($criteria)) {
            foreach ($criteria as $field => $value) {
                $queryBuilder->andWhere("bpc.$field = :$field")
                    ->setParameter($field, $value);
            }
        }

        if (!empty($orderBy)) {
            foreach ($orderBy as $field => $direction) {
                $queryBuilder->addOrderBy("bpc.$field", $direction);
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

    public function save(BlogPostComment $blogPostComment): void
    {
        $this->_em->persist($blogPostComment);
        $this->_em->flush();
    }

    //    /**
    //     * @return BlogComment[] Returns an array of BlogComment objects
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

    //    public function findOneBySomeField($value): ?BlogComment
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
