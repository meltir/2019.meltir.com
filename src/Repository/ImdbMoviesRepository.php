<?php

namespace App\Repository;

use App\Entity\ImdbMovies;
use App\Meltir\imdb;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;


/**
 * @method ImdbMovies|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImdbMovies|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImdbMovies[]    findAll()
 * @method ImdbMovies[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImdbMoviesRepository extends ServiceEntityRepository
{
    private $imdb = null;

    public function __construct(RegistryInterface $registry, imdb $imdb)
    {
        $this->imdb = $imdb;
        parent::__construct($registry, ImdbMovies::class);
    }

    /**
     * @param int $perPage
     * @return int
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countPages(int $perPage = 10):int {
        $count = $this->createQueryBuilder('n')
            ->select('COUNT(n.id)')
            ->getQuery()
            ->getSingleScalarResult();
        return floor($count/$perPage);
    }

    /**
     * @param int $page
     * @param int $perPage
     * @return ImdbMovies[]
     */
    public function getMovies(int $page=1, int $perPage = 10) {
        $query = $this->createQueryBuilder('n');
        $query->addOrderBy('n.my_rating','DESC');
        $query->addOrderBy('n.year','DESC');

        if ($page>1) {
            $query->setFirstResult(($page-1)*$perPage);
        }
        $query->setMaxResults($perPage);
        return $query->getQuery()->getResult();

    }

    // /**
    //  * @return ImdbMovies[] Returns an array of ImdbMovies objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ImdbMovies
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
