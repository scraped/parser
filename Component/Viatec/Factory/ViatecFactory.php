<?php

namespace Onixcat\Component\Viatec\Factory;

use Onixcat\Component\Viatec\Cache\Adapters\Redis\RedisCacheBuilder;
use Onixcat\Component\Viatec\Cache\CacheAdapterInterface;
use Onixcat\Component\Viatec\Connect\Adapters\Guzzle\GuzzleBuilder;
use Onixcat\Component\Viatec\Connect\Adapters\HelperInterface;
use Onixcat\Component\Viatec\Parser\Adapters\Crawler\CrawlerBuilder;
use Onixcat\Component\Viatec\Parser\ParserInterface;
use Onixcat\Component\Viatec\Saver\Adapters\PhpSpreadsheet\PhpSpreadsheetBuilder;
use Onixcat\Component\Viatec\Saver\Adapters\SaverHelperInterface;

class ViatecFactory implements FactoryInterface
{

    /**
     * @inheritdoc
     */
    public function createConnectHelper($uri, $login, $password, $needle): HelperInterface
    {
        return (new GuzzleBuilder($uri, $login, $password, $needle))->build();
    }

    /**
     * @inheritdoc
     */
    public function createParser(): ParserInterface
    {
        return (new CrawlerBuilder())->build();
    }

    /**
     * @inheritdoc
     */
    public function createSaver($excelFileHeader) : SaverHelperInterface
    {
        return (new PhpSpreadsheetBuilder($excelFileHeader))->build();
    }

    /**
     * @inheritdoc
     */
    public function createCacheBuilder($host, $port = 6379): CacheAdapterInterface
    {
        return new RedisCacheBuilder($host, $port);
    }
}
