<?php

namespace Onixcat\Component\Viatec\Connect\Adapters;

/**
 * Interface ParserAdapterInterface
 */
interface ConnectAdapterInterface
{
    /**
     * @return HelperInterface
     */
    public function build(): HelperInterface;
}
