<?php

namespace App\Repository;

use App\Entity\Evaluations;
use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Evaluations>
 *
 * @method Evaluations|null find($id, $lockMode = null, $lockVersion = null)
 * @method Evaluations|null findOneBy(array $criteria, array $orderBy = null)
 * @method Evaluations[]    findAll()
 * @method Evaluations[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EvaluationsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Evaluations::class);
    }

    public function findEventsByUser(Users $user)
    {
        return $this->createQueryBuilder('e')
            ->leftJoin('e.teacher', 't')
            ->leftJoin('e.studentclasses', 'sc')
            ->leftJoin('sc.students', 's')
            ->leftJoin('e.students', 'u') // Ajout de la jointure avec la relation ManyToMany users
            ->andWhere('t = :user OR s = :user OR u = :user') // Ajout de la condition pour vérifier la relation avec users
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Evaluations[] Returns an array of Evaluations objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Evaluations
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
