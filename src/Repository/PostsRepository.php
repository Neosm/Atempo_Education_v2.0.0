<?php

namespace App\Repository;

use App\Entity\Posts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Posts>
 *
 * @method Posts|null find($id, $lockMode = null, $lockVersion = null)
 * @method Posts|null findOneBy(array $criteria, array $orderBy = null)
 * @method Posts[]    findAll()
 * @method Posts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Posts::class);
    }

    public function getPaginatedArticles($page, $limit, $filters = null){
        $query = $this->createQueryBuilder('a')
            ->where('a.is_active = 1');

        // On filtre les données
        if($filters != null){
            $query->leftJoin('a.categories', 'c');
            $query->andWhere('c.id IN(:cats)')
                ->setParameter(':cats', $filters);
            if($catParent = $filters){
                $query->orWhere('c.parent IN(:cats)')
                    ->setParameter(':cats', $filters);
            }
        }

        $query->orderBy('a.id', 'DESC')
            ->setFirstResult(($page * $limit) - $limit)
            ->setMaxResults($limit)
        ;
        return $query->getQuery()->getResult();
        }

    public function getTotalArticles($filters = null){
        $query = $this->createQueryBuilder('a')
            ->select('COUNT(a)')
            ->where('a.is_active = 1')
            ;

        if($filters != null){
            $query->leftJoin('a.categories', 'c');
            $query->andWhere('c.id IN(:cats)')
                ->setParameter(':cats', $filters);
            if($catParent = $filters){
                $query->orWhere('c.parent IN(:cats)')
                    ->setParameter(':cats', $filters);
            }
        }

        return $query->getQuery()->getSingleScalarResult();
    }

//    /**
//     * @return Posts[] Returns an array of Posts objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Posts
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
