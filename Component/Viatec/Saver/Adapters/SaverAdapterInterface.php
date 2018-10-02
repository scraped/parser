<?php

namespace Onixcat\Component\Viatec\Saver\Adapters;


interface SaverAdapterInterface
{
    /**
     * @return SaverHelperInterface
     */
    public function build() : SaverHelperInterface;
}
