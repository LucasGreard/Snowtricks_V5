<?php

namespace App\Repository;

use App\Entity\UserNewPW;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserNewPW|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserNewPW|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserNewPW[]    findAll()
 * @method UserNewPW[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserNewPWRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserNewPW::class);
    }

    public function findUserId($token)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT IDENTITY (u.user)
            FROM App\Entity\UserNewPW u
            WHERE u._token = :token'
        )
            ->setParameter('token', $token);
        return $query->getResult();
    }
    // /**
    //  * @return UserNewPW[] Returns an array of UserNewPW objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserNewPW
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
