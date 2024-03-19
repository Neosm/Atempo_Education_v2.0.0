<?php

namespace App\Repository;

use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<Users>
 *
 * @implements PasswordUpgraderInterface<Users>
 *
 * @method Users|null find($id, $lockMode = null, $lockVersion = null)
 * @method Users|null findOneBy(array $criteria, array $orderBy = null)
 * @method Users[]    findAll()
 * @method Users[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsersRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Users::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof Users) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }


    public function findAllStudentByEcole($ecole)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.roles LIKE :val')
            ->andWhere('u.school = :ecole')
            ->setParameter('val', '%["ROLE_STUDENT"]%')
            ->setParameter('ecole', $ecole)
            ->getQuery()
            ->getResult();
    }

    public function findByrolesStudent($events)
    {
        $userEvents = array_map(function($event) {
            return $event->getId();
        }, $events);

        return $this->createQueryBuilder('s')
            ->leftJoin('s.courses', 'e')
            ->andWhere('e.id IN (:val)')
            ->setParameter('val', $userEvents)
            ->andWhere('s.roles LIKE :role')
            ->setParameter('role', '%["ROLE_STUDENT"]%')
            ->getQuery()
            ->getResult();
    }

    public function findByrolesTeacher($events)
    {
        $userEvents = array_map(function($event) {
            return $event->getId();
        }, $events);

        return $this->createQueryBuilder('u')
            ->leftJoin('u.coursesteacher', 'e')
            ->andWhere('e.id IN (:val)')
            ->setParameter('val', $userEvents)
            ->andWhere('u.roles LIKE :role')
            ->setParameter('role', '%["ROLE_TEACHER"]%')
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Users[] Returns an array of Users objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Users
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
