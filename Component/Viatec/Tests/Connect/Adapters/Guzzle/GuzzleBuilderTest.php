<?php


namespace Onixcat\Component\Viatec\Connect\Adapters\Guzzle;

use PHPUnit\Framework\TestCase;

class GuzzleBuilderTest extends TestCase
{
    /**
     * @var GuzzleBuilder
     */
    private $testObject;

    protected function setUp()/* The :void return type declaration that should be here would cause a BC issue */
    {
        $this->testObject = new GuzzleBuilder('', '', '', '');
    }

    public function testBuild()
    {
        $this->assertInstanceOf('Onixcat\Component\Viatec\Connect\Adapters\HelperInterface', $this->testObject->build());
    }
}
