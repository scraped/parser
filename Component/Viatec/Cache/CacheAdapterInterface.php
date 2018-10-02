<?php

namespace Onixcat\Component\Viatec\Cache;

use Psr\SimpleCache\CacheInterface;

interface CacheAdapterInterface
{
    public function buildCache() : CacheInterface;
}