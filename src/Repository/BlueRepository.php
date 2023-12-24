<?php

namespace App\Repository;

use App\Entity\Blue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Blue>
 *
 * @method Blue|null find($id, $lockMode = null, $lockVersion = null)
 * @method Blue|null findOneBy(array $criteria, array $orderBy = null)
 * @method Blue[]    findAll()
 * @method Blue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Blue::class);
    }

//    /**
//     * @return Blue[] Returns an array of Blue objects
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

//    public function findOneBySomeField($value): ?Blue
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
