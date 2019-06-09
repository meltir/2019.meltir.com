<?php

namespace App\Repository;

use App\Entity\YtChannels;
use App\Entity\YtVideos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 * @method YtVideos|null find($id, $lockMode = null, $lockVersion = null)
 * @method YtVideos|null findOneBy(array $criteria, array $orderBy = null)
 * @method YtVideos[]    findAll()
 * @method YtVideos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class YtVideosRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, YtVideos::class);
    }

    /**
     * @param YtChannels $channel
     * @param int $count
     * @param int $page
     * @return Query
     */
    public function getLatestVideosQuery(YtChannels $channel, $count = 10, $page = 1) {
        $query = $this->createQueryBuilder('n')
            ->andWhere('n.channel = :chan')
            ->setParameter('chan',$channel)
            ->orderBy('n.date_published','DESC')
            ->setMaxResults($count);
        if ($page>1) $query->setFirstResult($page*$count);
        return $query->getQuery();
    }
}
