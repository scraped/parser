<?php

namespace Onixcat\Component\Viatec\Parser;

use Doctrine\Common\Collections\Collection;

interface ParserInterface
{
    /**
     * Return collection of objects with parsed contents
     *
     * @return Collection
     */
    public function getCategories() : Collection;
}
