<?php

namespace App\Repository;

use App\Entity\Rooms;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Rooms>
 *
 * @method Rooms|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rooms|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rooms[]    findAll()
 * @method Rooms[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoomsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rooms::class);
    }

    public function createFilteredQuery(array $materialIdsArray)
    {
        $queryBuilder = $this->createQueryBuilder('r');

        if (!empty($materialIdsArray)) {
            foreach ($materialIdsArray as $index => $materialId) {
                $queryBuilder->andWhere('r.equipments LIKE :materialId' . $index)
                    ->setParameter('materialId' . $index, '%' . $materialId . '%');
            }
        }

        return $queryBuilder;
    }

    public function findReservedRooms($start, $end): array
    {
        $qb = $this->createQueryBuilder('r')
            ->leftJoin('r.courses', 'course')
            ->leftJoin('r.events', 'event')
            ->leftJoin('r.evaluations', 'evaluation')
            ->andWhere(
                '(:start >= course.start AND :start < DATE_ADD(course.start, course.duration, \'MINUTE\')) OR ' .
                '(:end > course.start AND :end <= DATE_ADD(course.start, course.duration, \'MINUTE\')) OR ' .
                '(:start < course.start AND :end > DATE_ADD(course.start, course.duration, \'MINUTE\')) OR ' .
                '(:start >= event.start AND :start < event.end) OR ' .
                '(:end > event.start AND :end <= event.end) OR ' .
                '(:start < event.start AND :end > event.end) OR ' .
                '(:start >= evaluation.start AND :start < DATE_ADD(evaluation.start, evaluation.duration, \'MINUTE\')) OR ' .
                '(:end > evaluation.start AND :end <= DATE_ADD(evaluation.start, evaluation.duration, \'MINUTE\')) OR ' .
                '(:start < evaluation.start AND :end > DATE_ADD(evaluation.start, evaluation.duration, \'MINUTE\'))'
            )
            ->setParameter('start', new \DateTime($start))
            ->setParameter('end', new \DateTime($end));

        $reservedRooms = $qb->getQuery()->getResult();

        return $reservedRooms;
    }



//    /**
//     * @return Rooms[] Returns an array of Rooms objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Rooms
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
