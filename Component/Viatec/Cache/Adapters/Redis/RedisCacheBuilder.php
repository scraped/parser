<?php

namespace Onixcat\Component\Viatec\Cache\Adapters\Redis;

use Cache\Adapter\Redis\RedisCachePool;
use Cache\Bridge\SimpleCache\SimpleCacheBridge;
use Onixcat\Component\Viatec\Cache\CacheAdapterInterface;
use Psr\SimpleCache\CacheInterface;

class RedisCacheBuilder implements CacheAdapterInterface
{
    /**
     * @var string
     */
    private $host;

    /**
     * @var int
     */
    private $port;

    /**
     * RedisCacheBuilder constructor.
     * @param string $host
     * @param int $port
     */
    public function __construct(string $host, int $port)
    {
        $this->host = $host;
        $this->port = $port;
    }

    /**
     * @inheritdoc
     */
    public function buildCache(): CacheInterface
    {
        $client = new \Redis();
        $client->connect($this->host, $this->port);
        $pool = new RedisCachePool($client);

        return new SimpleCacheBridge($pool);
    }

}
