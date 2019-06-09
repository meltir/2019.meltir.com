<?php

namespace App\Repository;

use App\Entity\YtCategories;
use App\Entity\YtChannels;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method YtChannels|null find($id, $lockMode = null, $lockVersion = null)
 * @method YtChannels|null findOneBy(array $criteria, array $orderBy = null)
 * @method YtChannels[]    findAll()
 * @method YtChannels[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class YtChannelsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, YtChannels::class);
    }


    /**
     * @param YtChannels $channel
     * @return bool
     */
    public function isChannelActive(YtChannels $channel) {
        if ($channel->getCategory()->getActive()) return true;
        return false;
    }

    /**
     * @param int $page
     * @param int $per_page
     * @param YtCategories|null $category
     * @return YtChannels[]
     */
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

    /**
     * @param int $per_page
     * @param YtCategories|null $category
     * @return int
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
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

    /**
     * @param int $startRow
     * @param int $per_page
     * @return YtChannels[]
     */
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

}
