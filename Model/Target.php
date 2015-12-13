<?php

namespace Galmi\XacmlBundle\Model;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Galmi\XacmlBundle\Model\Target\TargetType;

class Target
{
    /**
     * @var Collection
     */
    protected $matches;

    /**
     * @var TargetType
     */
    protected $type;

    /**
     * @return Collection
     */
    public function getMatches()
    {
        return $this->matches ?: $this->matches = new ArrayCollection();
    }

    /**
     * @param Match $match
     * @return $this
     */
    public function addMatch(Match $match)
    {
        if (!$this->getMatches()->contains($match)) {
            $this->getMatches()->add($match);
        }

        return $this;
    }

    /**
     * @param Match $match
     * @return $this
     */
    public function removeMatch(Match $match)
    {
        if ($this->getMatches()->contains($match)) {
            $this->getMatches()->remove($match);
        }

        return $this;
    }

    /**
     * @return TargetType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param TargetType $type
     * @return $this
     */
    public function setType(TargetType $type)
    {
        $this->type = $type;

        return $this;
    }

    public function evaluate()
    {
        return $this->getType()->evaluate($this->getMatches());
    }
}