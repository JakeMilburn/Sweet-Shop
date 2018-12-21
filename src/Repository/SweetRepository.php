<?php

namespace App\Repository;

use App\Entity\Sweet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Sweet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sweet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sweet[]    findAll()
 * @method Sweet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SweetRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Sweet::class);
    }

//    /**
//     * @return Media[] Returns an array of Media objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Media
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
