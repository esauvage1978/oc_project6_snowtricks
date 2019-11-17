<?php

namespace App\Twig;

use App\Entity\Trick;
use App\Security\TrickVoter;
use Doctrine\Common\Collections\Collection;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Symfony\Component\Security\Core\Security;

class CanCRUDTrickExtension extends AbstractExtension
{
    /**
     * @var Security
     */
    private $security;

    /**
     * @var TrickVoter
     */
    private $trickVoter;

    public function __construct(Security $security, TrickVoter $trickVoter)
    {
        $this->security = $security;
        $this->trickVoter=$trickVoter;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('canUpdateTrick', [$this, 'canUpdateTrick']),
            new TwigFilter('canDeleteTrick', [$this, 'canDeleteTrick']),
        ];
    }

    public function canUpdateTrick(Trick $trick)
    {
        return
            $this->trickVoter::ACCESS_GRANTED === $this->trickVoter->vote($this->security->getToken(),$trick,[$this->trickVoter::UPDATE]);
    }

    public function canDeleteTrick(Trick $trick)
    {
        return
            $this->trickVoter::ACCESS_GRANTED === $this->trickVoter->vote($this->security->getToken(),$trick,[$this->trickVoter::DELETE]);
    }

}
