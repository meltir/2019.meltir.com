<?php

namespace App\Repository;

use App\Entity\GoogleTokenStorage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method GoogleTokenStorage|null find($id, $lockMode = null, $lockVersion = null)
 * @method GoogleTokenStorage|null findOneBy(array $criteria, array $orderBy = null)
 * @method GoogleTokenStorage[]    findAll()
 * @method GoogleTokenStorage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GoogleTokenStorageRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GoogleTokenStorage::class);
    }

    public function getLastToken() {
        $query = $this->createQueryBuilder('n');
        $query->addOrderBy('n.id','DESC');
        return $query->getQuery()->getOneOrNullResult();
    }

    // /**
    //  * @return GoogleTokenStorage[] Returns an array of GoogleTokenStorage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GoogleTokenStorage
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
