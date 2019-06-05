<?php

namespace App\Repository;

use App\Entity\PagePost;
use Gedmo\Sortable\Entity\Repository\SortableRepository;

/**
 * @method PagePost|null find($id, $lockMode = null, $lockVersion = null)
 * @method PagePost|null findOneBy(array $criteria, array $orderBy = null)
 * @method PagePost[]    findAll()
 * @method PagePost[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PagePostRepository extends SortableRepository
{
    /**
     * @param $page string
     * @return PagePost[]
     */
    public function findForThisPage($page) {
        return $this->getBySortableGroupsQueryBuilder(['page'=>$page])
            ->andWhere('n.active = true')
            ->andWhere('n.parent_post is null')
//            ->addOrderBy('n.post_order')
            ->addOrderBy('n.id')
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return PagePost[] Returns an array of PagePost objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PagePost
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
