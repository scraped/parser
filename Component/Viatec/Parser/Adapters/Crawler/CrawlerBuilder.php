<?php

namespace Onixcat\Component\Viatec\Parser\Adapters\Crawler;


use Onixcat\Component\Viatec\Parser\Adapters\ParserAdapterInterface;
use Onixcat\Component\Viatec\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class GuzzleBuilder
 */
class CrawlerBuilder implements ParserAdapterInterface
{
    /**
     * @inheritdoc
     */
    public function build(): ParserInterface
    {
        return new CrawlerParser(new Crawler());
    }
}
