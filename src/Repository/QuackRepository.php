<?php

namespace App\Repository;

use App\Entity\Quack;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Quack|null find($id, $lockMode = null, $lockVersion = null)
 * @method Quack|null findOneBy(array $criteria, array $orderBy = null)
 * @method Quack[]    findAll()
 * @method Quack[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuackRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Quack::class);
    }

    // /**
    //  * @return Quack[] Returns an array of Quack objects
    //  */

    public function findBySearchBar($value)
    {
        return $this->createQueryBuilder('quack')
            ->join('quack.author', 'duckduck')
            ->addSelect('duckduck')
            ->orWhere('duckduck.duckname LIKE :val')
            ->orWhere('quack.tags LIKE :val')
            ->orWhere('quack.content LIKE :val')
            ->setParameter('val', "%" . $value . "%")
            ->orderBy('quack.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    public function findByTag($tag)
    {
        return $this->createQueryBuilder('quack')
            ->orWhere('quack.tags LIKE :val')
            ->setParameter('val', "%" . $tag . "%")
            ->orderBy('quack.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }



//    public function findOneBySomeField($value): ?Quack
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

}
