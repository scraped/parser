<?php

namespace Onixcat\Component\Viatec\Parser\Adapters\Crawler;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Onixcat\Component\Viatec\Entity\Category;
use Onixcat\Component\Viatec\Entity\Product;
use Onixcat\Component\Viatec\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class CrawlerParser
 */
class CrawlerParser implements ParserInterface
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * @var ArrayCollection
     */
    private $categories;

    /**
     * CrawlerParser constructor.
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
        //TODO: create collection validation
        $this->categories = new ArrayCollection();
    }

    /**
     * Parse URL of categories from html page
     *
     * @param string $catalogPage
     * @return ParserInterface
     */
    public function parseCategoriesUrls(string $catalogPage, string $mainPageUrl, array $excludes): ParserInterface
    {
        $this->crawler->add($catalogPage);

        // TODO: add filter parameters from config file
        $this->crawler->filter('div.catalog_group_item')->each(
            function (Crawler $node) use ($mainPageUrl, $excludes) {
                $mainCategoryName = $node->filter('td > h2 > a')->text();
                if (!in_array($mainCategoryName, $excludes)) {
                    $node->filter('div > a')->each(function (Crawler $node) use ($mainCategoryName, $mainPageUrl) {
                        $category = new Category();
                        $categoryName = $mainCategoryName . '_' . $node->text();
                        $category->setName($categoryName);
                        $category->setUrl($mainPageUrl . trim($node->attr('href')));
                        $this->categories->add($category);
                    });
                }
            }
        );
        $this->crawler->clear();
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    /**
     * Check if a pagination exists
     *
     * @param $page
     * @return bool
     */
    public function isPagination(string $page)
    {
        $this->crawler->add($page);
        // TODO: add filter parameters from config file
        $el = $this->crawler->filter('td.paginator strong')->parents()->nextAll();
        $this->crawler->clear();
        if ($el->nodeName() == 'a') {
            return trim($el->attr('href'));
        }
        return '';
    }

    /**
     * Parse products prices from html page
     *
     * @param string $productPage
     * @return ParserInterface
     */
    public function parseProducts(string $productPage, Category $category, string $codePrefix): ParserInterface
    {
        $this->crawler->add($productPage);

        // TODO: add filter CssSelector from config file
        $this->crawler->filter('div.catalog_list_item')->each(
            function (Crawler $node) use ($category, $codePrefix) {
                $product = new Product();
                $product->setCode($this->extractCode($node->filter('a.image > center')->text(), $codePrefix));
                $product->setName($node->filter('h2 > a')->text());
                if ($node->filter('div.price font')->count()) {
                    $product->setRetailPrice($this->filterPrice($node->filter('div.price font')->text()));
                } else {
                    $product->setRetailPrice($this->filterPrice($node->filter('div.price')->text()));
                }
                $product->setInStock($this->transformToInStock($node->filter('div.stock')->attr('title')));
                $category->addProduct($product);
            }
        );
        $this->crawler->clear();

        return $this;
    }

    /**
     * Filter if price not a number
     *
     * @param string $price
     * @return int|string
     */
    public function filterPrice(string $price)
    {
        if ($price == 'ценууточняйте'){
            return 0;
        }
        return $price;
    }

    /**
     * Extract product code
     *
     * @param string $code
     * @return string
     */
    public function extractCode(string $parsedCode, $codePrefix): string
    {
        return trim(str_ireplace($codePrefix, '', $parsedCode));
    }

    /**
     * Transform product exists status
     *
     * @param string $stock
     * @return int
     */
    public function transformToInStock(string $stock): int
    {
        if (mb_stripos(trim($stock), 'Товар отсутствует') !== false) {
            return 0;
        }
        return 1;
    }
}
