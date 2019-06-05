<?php

namespace App\Repository;

use App\Entity\YtCategories;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method YtCategories|null find($id, $lockMode = null, $lockVersion = null)
 * @method YtCategories|null findOneBy(array $criteria, array $orderBy = null)
 * @method YtCategories[]    findAll()
 * @method YtCategories[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class YtCategoriesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, YtCategories::class);
    }


    public function getActiveCategories() {
        return $this->createQueryBuilder('n')
            ->andWhere('n.active = true')
            ->orderBy('n.cat_order')
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return YtCategories[] Returns an array of YtCategories objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('y')
            ->andWhere('y.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('y.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?YtCategories
    {
        return $this->createQueryBuilder('y')
            ->andWhere('y.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
