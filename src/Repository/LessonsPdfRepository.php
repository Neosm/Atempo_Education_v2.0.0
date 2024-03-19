<?php

namespace App\Repository;

use App\Entity\LessonsPdf;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LessonsPdf>
 *
 * @method LessonsPdf|null find($id, $lockMode = null, $lockVersion = null)
 * @method LessonsPdf|null findOneBy(array $criteria, array $orderBy = null)
 * @method LessonsPdf[]    findAll()
 * @method LessonsPdf[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LessonsPdfRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LessonsPdf::class);
    }

//    /**
//     * @return LessonsPdf[] Returns an array of LessonsPdf objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?LessonsPdf
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
