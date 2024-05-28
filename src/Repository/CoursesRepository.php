<?php

namespace App\Repository;

use App\Entity\Courses;
use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Courses>
 *
 * @method Courses|null find($id, $lockMode = null, $lockVersion = null)
 * @method Courses|null findOneBy(array $criteria, array $orderBy = null)
 * @method Courses[]    findAll()
 * @method Courses[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CoursesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Courses::class);
    }

    public function findEventsByUser(Users $user)
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.teacher', 't')
            ->leftJoin('c.studentClasses', 'sc')
            ->leftJoin('sc.students', 's')
            ->leftJoin('c.students', 'u') // Ajout de la jointure avec la relation ManyToMany users
            ->andWhere('t = :user OR s = :user OR u = :user') // Ajout de la condition pour vérifier la relation avec users
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }


//    /**
//     * @return Courses[] Returns an array of Courses objects
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

//    public function findOneBySomeField($value): ?Courses
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
