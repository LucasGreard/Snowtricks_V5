<?php

namespace App\Repository;

use App\Entity\Comments;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Comments|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comments|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comments[]    findAll()
 * @method Comments[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comments::class);
    }
    public function showCommentPagination($offset, $figure_id)
    {
        if ($offset !== 0) {
            $offset = $offset * 5;
        }
        return $this->createQueryBuilder('c')
            ->where('c.Figure = ?1')
            ->orderBy('c.Created_at', 'DESC')
            ->setParameter(1, $figure_id)
            ->setMaxResults(5)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
    }

    public function countComment($figure_id)
    {
        return $this->createQueryBuilder('c')
            ->select('COUNT(c)')
            ->where('c.Figure = ?1')
            ->setParameter(1, $figure_id)
            ->getQuery()
            ->getSingleScalarResult();
    }

    // /**
    //  * @return Comments[] Returns an array of Comments objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Comments
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
