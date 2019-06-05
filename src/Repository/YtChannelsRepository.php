<?php

namespace App\Repository;

use App\Entity\YtCategories;
use App\Entity\YtChannels;
use App\Entity\YtVideos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Google_Client;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Google_Service_YouTube;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 * @method YtChannels|null find($id, $lockMode = null, $lockVersion = null)
 * @method YtChannels|null findOneBy(array $criteria, array $orderBy = null)
 * @method YtChannels[]    findAll()
 * @method YtChannels[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class YtChannelsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry, Stopwatch $stopwatch, LoggerInterface $logger)
    {
        parent::__construct($registry, YtChannels::class);
        $this->stopwatch = $stopwatch;
        $this->logger = $logger;
    }


    /**
     * @var Stopwatch
     */
    protected $stopwatch;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    public function isChannelActive(YtChannels $channel) {
        if ($channel->getCategory()->getActive()) return true;
        return false;
    }

    public function getChannelPage(int $page=1,int $per_page=10, YtCategories $category = null) {
        $query = $this->createQueryBuilder('n')
            ->addOrderBy('n.category')
            ->join('n.category', 'c')
            ->andWhere('c.active = true')
            ->setMaxResults($per_page)
            ->setFirstResult(1);
        if ($page>1) $query->setFirstResult(($page-1)*$per_page+1);
        if ($category) $query->andWhere('n.category = :cat')
            ->setParameter('cat',$category);
        return $query->getQuery()->getResult();
    }

    public function countPages(int $per_page, YtCategories $category = null) {
        $query = $this->createQueryBuilder('n')
            ->select('COUNT(n.id)')
            ->join('n.category', 'c')
            ->andWhere('c.active = true');
        if ($category) $query->andWhere('n.category = :cat')
            ->setParameter('cat',$category);
        $full_count = $query
            ->getQuery()
            ->getSingleScalarResult();
        return floor($full_count/$per_page);
    }

    public function findActiveChannels(int $startRow=0, int $per_page=0) {
        $query = $this->createQueryBuilder('n')
            ->join('n.category','c')
            ->andWhere('c.active = true');
        if ($startRow !=0 && $per_page!=0) {
            $query->setFirstResult($startRow);
            $query->setMaxResults($per_page);
        }

        return $query
            ->getQuery()
            ->getResult();
    }



    // /**
    //  * @return YtChannels[] Returns an array of YtChannels objects
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
    public function findOneBySomeField($value): ?YtChannels
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
