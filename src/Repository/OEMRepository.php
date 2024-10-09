<?php

namespace App\Repository;

use App\Entity\OEM;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OEM>
 *
 * @method OEM|null find($id, $lockMode = null, $lockVersion = null)
 * @method OEM|null findOneBy(array $criteria, array $orderBy = null)
 * @method OEM[]    findAll()
 * @method OEM[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OEMRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OEM::class);
    }

//    /**
//     * @return OEM[] Returns an array of OEM objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?OEM
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
