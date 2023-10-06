<?php

namespace App\Repository;

use App\Entity\Presse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Presse>
 *
 * @method Presse|null find($id, $lockMode = null, $lockVersion = null)
 * @method Presse|null findOneBy(array $criteria, array $orderBy = null)
 * @method Presse[]    findAll()
 * @method Presse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PresseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Presse::class);
    }

//    /**
//     * @return Presse[] Returns an array of Presse objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Presse
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
