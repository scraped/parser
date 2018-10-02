<?php

namespace Onixcat\Component\Viatec\Tests\Parser;


use Onixcat\Component\Viatec\Entity\Category;
use Onixcat\Component\Viatec\Parser\Adapters\Crawler\CrawlerParser;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DomCrawler\Crawler;


class CrawlerParserTest extends TestCase
{
    /**
     * @var CrawlerParser
     */
    private $testObject;

    protected function setUp()
    {
        $crawler = new Crawler();
        $this->testObject = new CrawlerParser($crawler);
    }

    public function testParseCatalogUrls()
    {
        $html = '
                   <div id="catalog_group"> 
                       <table class="container">
                        <tbody>
                            <tr>
                                <td class="right">
                                    <div class="catalog_group_item">
                                        <table>
                                            <tbody>
                                                <tr>
                                                    <td><a href="/catalog/sale"><img src="/images/cat_sale.jpg" alt="~Распродажа"></a></td>
                                                    <td>
                                                        <h2><a href="/catalog/sale" style="color:red;">Распродажа</a>&nbsp;<span>(55)</span></h2>
                                                        <div style="margin-left:20px;">
                                                            <a href="/catalog/sale/monitors-sale">Телевизоры LCD</a>&nbsp;<span>(1)</span> <br>
                                                            <a href="/catalog/sale/camera">Видеокамеры</a>&nbsp;<span>(23)</span> <br> 
                                                            <a href="/catalog/sale/ip">IP оборудование </a>&nbsp;<span>(5)</span> <br> 
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="catalog_group_item">
                                        <table>
                                            <tbody>
                                            <tr>
                                                <td><a href="/catalog/analog"><img src="/images/cat_camera.jpg" alt="Аналоговые системы видеонаблюдения"></a></td>
                                                <td>
                                                    <h2><a href="/catalog/analog">Аналоговые системы видеонаблюдения</a>&nbsp;<span>(58)</span></h2>
                                                    <div style="margin-left:20px;">
                                                        <a href="/catalog/analog/eco">Цифровые видеорегистраторы (DVR)</a>&nbsp;<span>(14)</span> <br> 
                                                        <a href="/catalog/analog/hybrid">Гибридные видеорегистраторы (HDVR)</a>&nbsp;<span>(1)</span><br>
                                                        <a href="/catalog/analog/speedome">Роботизированные PTZ (Speed Dome)</a>&nbsp;<span>(6)</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                   </table>
               </div>';

        $this->assertInstanceOf('Onixcat\Component\Viatec\Parser\ParserInterface', $this->testObject->parseCategoriesUrls($html, '', ['Распродажа']));
    }

    /**
     * @dataProvider paginationProvider
     */
    public function testIsPagination($expected, $html)
    {
        $this->assertInternalType('string', $this->testObject->isPagination($html));
        $this->assertSame($expected, $this->testObject->isPagination($html));
    }


    public function paginationProvider()
    {
        return
            [
                [
                    '?sort=1&page=2',
                    '<tr>
                    <td colspan="2" class="paginator">
                        <a href="?sort=1&amp;page=all">Показать все</a>
                        <span>Первая</span>
                        <span>Предыдущая</span>
                        <span><strong>1</strong></span>
                        <a href="?sort=1&amp;page=2">2</a><a href="?sort=1&amp;page=3">3</a>
                        <a href="?sort=1&amp;page=2">Следующая</a>
                        <a href="?sort=1&amp;page=3">Последняя(3)</a>
                    </td>
                 </tr>'
                ],
                [
                    '',
                    '<tr>
                    <td colspan="2" class="paginator">
                        <a href="?sort=1&amp;page=all">Показать все</a>
                        <a href="?sort=1&amp;page=1">Первая</a>
                        <a href="?sort=1&amp;page=2">Предыдущая</a>
                        <a href="?sort=1&amp;page=1">1</a>
                        <a href="?sort=1&amp;page=2">2</a>
                        <span><strong>3</strong></span>
                        <span>Следующая</span>
                        <span>Последняя(3)</span>
                    </td>
                </tr>'
                ]
            ];
    }

    public function testParseProducts()
    {
        $html = '<div id="catalog_list">
                    <div class="catalog_list_item">
                        <a class="image" href="/product/3000h4c4plus">
                            <img class="vtip" title="<img src=\'/prod_img/h3000c4.jpg\' alt=\'\' />" src="/prod_img/113/h3000c4.jpg" alt="3000H4C+4 4-канальная плата видеозахвата ILDVR">
                            <center style="color:grey; font-size:9px; line-height:9px;">Код: 01185-01734</center>
                        </a>
                        <div class="desc">
                            <h2><a class="href" href="/product/3000h4c4plus">4-канальная плата видеозахвата ILDVR 3000H4C+4</a></h2>
                
                            <p>Плата видеозахвата с аппаратной компрессией видеосигнала H.264. 4 канала видео, 4 канала аудио, запись:
                                25 к/с на канал, разрешение записи: CIF/2CIF/4CIF, Скорость передачи 32 Kbp - 2048 Kbp, Dual-stream, PTZ
                                Control, Network Connection, Windows Xp / Windows 7 / Windows 2003, 2008 (32 bit and 64 bit)</p>
                        </div>
                        <div>
                            <div class="stock col20" title="Товар в ограниченном количестве"></div>
                            <div class="price">177.87<br><font color="#AAAAAA">254.10</font><br>
                                <input value="1" type="text"><img class="small_cart" src="/blank.gif" alt="Купить 3000H4C+4" title="Добавить в корзину" id="n1185">
                            </div>
                        </div>
                        <div class="clear_float"></div>
                    </div>
                    <div class="separator"></div>
                    <!-- next product -->
                    <div class="catalog_list_item">
                        <a class="image" href="/product/ildvr-3000h4d4">
                            <img class="vtip" title="<img src=\'/prod_img/3000D4.jpg\' alt=\'\' />" src="/prod_img/113/3000D4.jpg" alt="3000H4D4 4-канальная плата декомпрессии ILDVR">
                            <center style="color:grey; font-size:9px; line-height:9px;">Код: 00372-00598</center>
                        </a>
                        <div class="desc">
                            <h2><a class="href" href="/product/ildvr-3000h4d4">4-канальная плата декомпрессии ILDVR 3000H4D4</a></h2>
                
                            <p>Плата декомпрессии (Матричный декодер), H.264, 4хD1, воспроизведение: 25 к/с на канал; выходы: видео
                                4хBNC, аудио 4хBNC, Network Connection, Windows NT / 2000 / XP / 2003 / VISTA / 7</p>
                        </div>
                        <div>
                            <div class="stock col10" title="Товар отсутствует на складе">4&nbsp;шт</div>
                            <div class="price">431.20<br><font color="#AAAAAA">616.00</font><br>
                                <input value="1" type="text">
                                <img class="small_cart" src="/blank.gif" alt="Купить 3000H4D4" title="Добавить в корзину" id="n372">
                            </div>
                        </div>
                        <div class="clear_float"></div>
                    </div>
                </div>';

        $category = new Category();

        $this->assertInstanceOf('Onixcat\Component\Viatec\Parser\ParserInterface', $this->testObject->parseProducts($html, $category, 'Код:'));
    }

    /**
     * @param $expected
     * @param $stock
     * @dataProvider providerTestTransformToInStock
     */
    public function testTransformToInStock($expected, $stock)
    {
        $this->assertSame($expected, $this->testObject->transformToInStock($stock));
    }

    /**
     * @return array
     */
    public function providerTestTransformToInStock()
    {
        return
            [
                [1, 'Товар в наличии'],
                [1, 'Товар в ограниченном количестве'],
                [1, 'Товар заканчивается (8 шт)'],
                [0, 'Товар отсутствует на складе']
            ];
    }

    /**
     * @dataProvider filterPriceProvider
     * @param $expected
     * @param $price
     */
    public function testFilterPrice($expected, $price)
    {
        $this->assertSame($expected, $this->testObject->filterPrice($price));
    }

    /**
     * @return array
     */
    public function filterPriceProvider()
    {
        return
            [
                [0, 'ценууточняйте'],
                ['100', '100']
            ];
    }
}
