<?php

namespace Galmi\Xacml\Policy;


use Doctrine\Common\Collections\Collection;

interface CombiningAlghoritm
{
    public function evaluate(Collection $rules);
}