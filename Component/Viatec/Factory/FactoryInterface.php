<?php

namespace Onixcat\Component\Viatec\Factory;


use Onixcat\Component\Viatec\Cache\CacheAdapterInterface;
use Onixcat\Component\Viatec\Connect\Adapters\HelperInterface;
use Onixcat\Component\Viatec\Parser\ParserInterface;
use Onixcat\Component\Viatec\Saver\Adapters\SaverHelperInterface;

interface FactoryInterface
{
    /**
     * Create connect helper
     *
     * @param $uri
     * @param $login
     * @param $password
     * @param $needle
     * @return HelperInterface
     */
    public function createConnectHelper(string $uri, string $login, string $password, string $needle): HelperInterface;

    /**
     * Create parser
     *
     * @return ParserInterface
     */
    public function createParser(): ParserInterface;

    /**
     * Create PhpSpreadsheet Helper
     *
     * @param array $excelFileHeader
     * @return SaverHelperInterface
     */
    public function createSaver(array $excelFileHeader): SaverHelperInterface;

    /**
     * Create Redis cache builder
     *
     * @param $host
     * @param int $port
     * @return CacheAdapterInterface
     */
    public function createCacheBuilder($host, $port = 6379): CacheAdapterInterface;
}
