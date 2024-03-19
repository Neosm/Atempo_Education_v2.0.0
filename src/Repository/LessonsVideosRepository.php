<?php

namespace App\Repository;

use App\Entity\LessonsVideos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LessonsVideos>
 *
 * @method LessonsVideos|null find($id, $lockMode = null, $lockVersion = null)
 * @method LessonsVideos|null findOneBy(array $criteria, array $orderBy = null)
 * @method LessonsVideos[]    findAll()
 * @method LessonsVideos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LessonsVideosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LessonsVideos::class);
    }

//    /**
//     * @return LessonsVideos[] Returns an array of LessonsVideos objects
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

//    public function findOneBySomeField($value): ?LessonsVideos
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
