<?php


namespace Onixcat\Component\Viatec\Tests\Connect\Adapters\Guzzle;

use Onixcat\Component\Viatec\Connect\Adapters\Guzzle\GuzzleHelper;
use Onixcat\Component\Viatec\Connect\Adapters\HelperInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;


class GuzzleHelperTest extends TestCase
{
    /**
     * @var MockObject
     */
    private $streamInterfaceStub;

    /**
     * @var MockObject
     */
    private $responseInterfaceStub;
    /**
     * @var GuzzleHelper
     */
    private $testObject;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {

        $clientStub = $this->getMockBuilder('GuzzleHttp\ClientInterface')->getMock();
        $cookieJarStub = $this->getMockBuilder('GuzzleHttp\Cookie\CookieJarInterface')->getMock();

        $this->streamInterfaceStub = $this->getMockBuilder('Psr\Http\Message\StreamInterface')->getMock();

        $this->responseInterfaceStub = $this->getMockBuilder('Psr\Http\Message\ResponseInterface')->getMock();
        $this->responseInterfaceStub
            ->method('getBody')
            ->willReturn($this->streamInterfaceStub);

        $clientStub->method('request')
            ->willReturn($this->responseInterfaceStub);

        $this->testObject = new GuzzleHelper($clientStub, $cookieJarStub, '', '', '', 'Личный кабинет');
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testAuth()
    {
        $this->assertInstanceOf('Psr\Http\Message\ResponseInterface', $this->testObject->auth());
    }

    /**
     * @expectedException \Onixcat\Component\Viatec\Connect\Exception\AuthException
     */
    public function testAuthLoginException()
    {
        $this->testObject->setAttemptsAmount(0);
        $this->testObject->auth();
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @dataProvider getContentsProvider
     */
    public function testIsLogin($expected, $content)
    {
        $this->assertSame($expected, $this->testObject->isAuth($content));
    }

    public function testGetPageWithAuth()
    {
        $this->streamInterfaceStub->method('getContents')
            ->willReturn('test Личный кабинет');
        $this->assertInstanceOf(HelperInterface::class, $this->testObject->getPageWithAuth('http://testurl'));
    }

    public function getContentsProvider()
    {
        return [
            [true, 'test Личный кабинет'],
            [false, 'ичный кабинет']
        ];

    }

}
