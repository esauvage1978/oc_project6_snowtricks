<?php

namespace App\Repository;

use App\Entity\Comment;
use App\Entity\Trick;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function commentsForTrickPaginator($page, $limit, Trick $trick)
    {
        $commentAlias = "c";
        $trickAlias = 't';

        $qb = $this->_em->createQueryBuilder($commentAlias);
        $qb->select($commentAlias)
            ->from('App\Entity\Comment', $commentAlias)
            ->leftJoin($commentAlias. '.trick', $trickAlias)
            ->where($trickAlias.'.id =:id')
            ->orderBy($commentAlias.'.createdAt', 'DESC')
            ->setParameter('id', $trick->getId())
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        return new Paginator($qb);
    }
}
