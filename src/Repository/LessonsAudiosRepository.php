<?php

namespace App\Repository;

use App\Entity\LessonsAudios;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LessonsAudios>
 *
 * @method LessonsAudios|null find($id, $lockMode = null, $lockVersion = null)
 * @method LessonsAudios|null findOneBy(array $criteria, array $orderBy = null)
 * @method LessonsAudios[]    findAll()
 * @method LessonsAudios[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LessonsAudiosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LessonsAudios::class);
    }

//    /**
//     * @return LessonsAudios[] Returns an array of LessonsAudios objects
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

//    public function findOneBySomeField($value): ?LessonsAudios
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
