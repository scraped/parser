<?php

namespace Onixcat\Component\Viatec\Parser\Adapters;

use Onixcat\Component\Viatec\Parser\ParserInterface;

/**
 * Interface ParserAdapterInterface
 */
interface ParserAdapterInterface
{
    /**
     * @return ParserInterface
     */
    public function build(): ParserInterface;
}
