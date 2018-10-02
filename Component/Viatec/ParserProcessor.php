<?php

namespace Onixcat\Component\Viatec;

use Doctrine\Common\Collections\ArrayCollection;
use Onixcat\Component\Viatec\Cache\CacheAdapterInterface;
use Onixcat\Component\Viatec\Connect\Adapters\Guzzle\GuzzleHelper;
use Onixcat\Component\Viatec\Connect\Adapters\HelperInterface;
use Onixcat\Component\Viatec\Entity\Category;
use Onixcat\Component\Viatec\Entity\CategoryInterface;
use Onixcat\Component\Viatec\Parser\Adapters\Crawler\CrawlerParser;
use Onixcat\Component\Viatec\Parser\ParserInterface;
use Onixcat\Component\Viatec\Saver\Adapters\PhpSpreadsheet\PhpSpreadsheetHelper;
use Onixcat\Component\Viatec\Saver\Adapters\SaverHelperInterface;
use PhpOffice\PhpSpreadsheet\Settings;


class ParserProcessor implements ParserProcessorInterface
{
    /**
     * @var CrawlerParser
     */
    private $parser;

    /**
     * @var GuzzleHelper
     */
    private $guzzleHelper;

    /**
     * @var ArrayCollection
     */
    private $categories;

    /**
     * @var PhpSpreadsheetHelper
     */
    private $saverHelper;

    /**
     * @var CacheAdapterInterface
     */
    private $cacheAdapter;

    /**
     * @var int
     */
    protected $startRow;


    /**
     * Parser constructor.
     * @param HelperInterface $guzzleHelper
     * @param ParserProcessorInterface $parser
     * @param SaverHelperInterface $saverHelper
     * @param int $startRow
     * @param null|CacheAdapterInterface $cacheAdapter
     */
    public function __construct(
        HelperInterface $guzzleHelper,
        ParserInterface $parser,
        SaverHelperInterface $saverHelper,
        CacheAdapterInterface $cacheAdapter,
        int $startRow)
    {
        $this->parser = $parser;
        $this->guzzleHelper = $guzzleHelper;
        $this->saverHelper = $saverHelper;
        $this->cacheAdapter = $cacheAdapter;

        $this->startRow = $startRow;

        $this->saverHelper->setCeilRow($startRow);
    }

    /**
     * @return int
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function parseCategories(string $mainUrl, string $catalogUrl, array $excludes = []): ?int
    {
        $this->categories = $this->parser->parseCategoriesUrls($this->getHtml($catalogUrl), $mainUrl, $excludes)
            ->getCategories();

        return count($this->categories);
    }

    /**
     * @param string $url
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getHtml(string $url): string
    {
        return $this->guzzleHelper->getPageWithAuth($url)->getHtml();
    }

    /**
     * Prepare for parsing creating first sheet with some content
     */
    public function prepareParsing()
    {
        $this->saverHelper->createFirstSheet();
    }

    /**
     * @param Category $category
     * @param $url
     * @param $index
     * @param $file
     * @param int $page
     * @return $this
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function executeParsing(CategoryInterface $category, string $categoryUrl, int $index, string $codePrefix): void
    {
        $html = $this->getHtml($categoryUrl);

        $paginationUrl = $this->parser->isPagination($html);

        $this->parser->parseProducts($html, $category, $codePrefix);

        $sheet = $this->saverHelper->prepareWrite($category->getName(), $index);

        $this->saverHelper->writeProducts($category->getProducts(), $sheet);

        if (!empty($paginationUrl)) {
            $category->clearProducts();
            $url = $categoryUrl . $paginationUrl;
            $this->executeParsing($category, $url, $index, $codePrefix);
        }
        $this->saverHelper->setCeilRow($this->startRow);
        $this->categories->removeElement($category);
    }

    /**
     * @param string $path
     * @param string $fileExtension
     * @return string
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function saveToFile(string $path, string $fileExtension)
    {
        return $this->saverHelper->save($path, $fileExtension);
    }

    /**
     * @return Category
     */
    public function getCategory(): CategoryInterface
    {
        return $this->categories->first();
    }

    /**
     * Enabled cache for spreadsheet
     */
    public function enableCache() :void
    {
        Settings::setCache($this->cacheAdapter->buildCache());
    }
}
