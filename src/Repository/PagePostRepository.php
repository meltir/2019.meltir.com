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
            ->addOrderBy('n.id')
            ->getQuery()
            ->getResult();
    }

}
