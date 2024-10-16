<?php

namespace App\Repository;

use App\Entity\Manufacture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Manufacture>
 *
 * @method Manufacture|null find($id, $lockMode = null, $lockVersion = null)
 * @method Manufacture|null findOneBy(array $criteria, array $orderBy = null)
 * @method Manufacture[]    findAll()
 * @method Manufacture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ManufactureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Manufacture::class);
    }

//    /**
//     * @return Manufacture[] Returns an array of Manufacture objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Manufacture
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
