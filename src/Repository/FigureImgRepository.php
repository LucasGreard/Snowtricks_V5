<?php

namespace App\Repository;

use App\Entity\FigureImg;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FigureImg|null find($id, $lockMode = null, $lockVersion = null)
 * @method FigureImg|null findOneBy(array $criteria, array $orderBy = null)
 * @method FigureImg[]    findAll()
 * @method FigureImg[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FigureImgRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FigureImg::class);
    }

    // /**
    //  * @return FigureImg[] Returns an array of FigureImg objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FigureImg
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
