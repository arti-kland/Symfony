<?php

namespace App\Repository;

use App\Entity\DuckDuck;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;

/**
 * @method DuckDuck|null find($id, $lockMode = null, $lockVersion = null)
 * @method DuckDuck|null findOneBy(array $criteria, array $orderBy = null)
 * @method DuckDuck[]    findAll()
 * @method DuckDuck[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DuckDuckRepository extends ServiceEntityRepository implements UserLoaderInterface
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DuckDuck::class);
    }

    // /**
    //  * @return DuckDuck[] Returns an array of DuckDuck objects
    //  */

    public function loadUserByUsername($usernameOrEmail)
    {
        return $this->createQueryBuilder('u')
            ->where('u.duckname = :query OR u.email = :query')
            ->setParameter('query', $usernameOrEmail)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?DuckDuck
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
