<?php

namespace App\Repository;

use App\Entity\Notequestion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Notequestion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Notequestion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Notequestion[]    findAll()
 * @method Notequestion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NotequestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Notequestion::class);
    }

    // /**
    //  * @return Notequestion[] Returns an array of Notequestion objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Notequestion
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
