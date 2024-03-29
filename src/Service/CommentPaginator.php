<?php

namespace App\Service;

use App\Entity\Trick;
use App\Repository\CommentRepository;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class CommentPaginator
{
    /**
     * @var ParameterBagInterface
     */
    private $params;

    /**
     * @var CommentRepository
     */
    private $commentRepository;

    /**
     * @var Trick
     */
    private $trick;

    private $nbrComments;
    private $nbrCommentLimitShow;

    public function __construct(
        ParameterBagInterface $params,
        CommentRepository $commentRepository,
        Trick $trick
    )
    {
        $this->params = $params;
        $this->commentRepository = $commentRepository;
        $this->trick = $trick;

        $this->calculNbrComments();
        $this->calculNbrCommentLimitShow();
    }

    public function getCommentsForPage($page)
    {
        return $this->commentRepository->commentsForTrickPaginator(
             $page,
             $this->nbrCommentLimitShow,
             $this->trick);
    }

    private function calculNbrComments()
    {
        $this->nbrComments=$this->commentRepository->count(['trick' => $this->trick->getId()]);
    }
    private function calculNbrCommentLimitShow()
    {
        $this->nbrCommentLimitShow=$this->params->get('comment.limitShow');
    }

    public function getNbrSheets()
    {
        return ceil($this->nbrComments / $this->nbrCommentLimitShow);
    }

    public function checkPage($page)
    {
        $nbrPages=$this->getNbrSheets();
        if (is_null($page) || $page < 1) {
           return 1;
        } else if ($page > $nbrPages ) {
            $page = $nbrPages;
        }

        return $page;
    }
}
