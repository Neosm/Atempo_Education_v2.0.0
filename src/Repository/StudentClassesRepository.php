<?php

namespace App\Repository;

use App\Entity\StudentClasses;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<StudentClasses>
 *
 * @method StudentClasses|null find($id, $lockMode = null, $lockVersion = null)
 * @method StudentClasses|null findOneBy(array $criteria, array $orderBy = null)
 * @method StudentClasses[]    findAll()
 * @method StudentClasses[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudentClassesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StudentClasses::class);
    }

//    /**
//     * @return StudentClasses[] Returns an array of StudentClasses objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?StudentClasses
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
