<?php

namespace App\Repository;

use App\Entity\Vacance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Vacance>
 *
 * @method Vacance|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vacance|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vacance[]    findAll()
 * @method Vacance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VacanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vacance::class);
    }

//    /**
//     * @return Vacance[] Returns an array of Vacance objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('v.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Vacance
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

}
