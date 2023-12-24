<?php

namespace App\Repository;

use App\Entity\CookieDescription;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CookieDescription>
 *
 * @method CookieDescription|null find($id, $lockMode = null, $lockVersion = null)
 * @method CookieDescription|null findOneBy(array $criteria, array $orderBy = null)
 * @method CookieDescription[]    findAll()
 * @method CookieDescription[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CookieDescriptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CookieDescription::class);
    }

//    /**
//     * @return CookieDescription[] Returns an array of CookieDescription objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CookieDescription
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
