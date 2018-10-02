<?php

namespace Onixcat\Bundle\ViatecParserBundle\Tests\Helpers;

use Onixcat\Bundle\ViatecParserBundle\Exception\FileNotFoundException;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Onixcat\Bundle\ViatecParserBundle\Helpers\FilesystemHelper;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class FilesystemHelperTest extends WebTestCase
{
    /**
     * @var FilesystemHelper
     */
    private $filesystemHelperTest;

    /**
     * @var MockObject|Finder
     */
    private $finder;

    /**
     * @var MockObject
     */
    private $filesystem;

    /**
     * @var MockObject
     */
    private $parameterBagStub;

    protected function setUp()/* The :void return type declaration that should be here would cause a BC issue */
    {
        $this->finder = new Finder();
        $this->filesystem = new Filesystem();
        $this->parameterBagStub = $this->getMockBuilder(ParameterBagInterface::class)->getMock();

        $this->filesystemHelperTest = new FilesystemHelper($this->finder, $this->filesystem, $this->parameterBagStub);
    }

    public function testGetFilesList()
    {
        $this->parameterBagStub->method('get')
            ->willReturn('/var/www/html/var/tmp/parser/viatec');

        $this->assertInternalType('array', $this->filesystemHelperTest->getFilesList());
    }

    /**
     * @expectedException \Onixcat\Bundle\ViatecParserBundle\Exception\FileNotFoundException
     */
    public function testGetFilesListFilesNotFoundException()
    {
        $this->parameterBagStub->method('get')
            ->willReturn('/var/www/html/var/tmp/parser/viatec');
        $finder = $this->getMockBuilder(Finder::class)
            ->getMock();
        $finder->method('files')
            ->willReturn($finder);
        $finder->method('in')
            ->willReturn($finder);
        $finder->method('count')
            ->willReturn(0);
        $this->filesystemHelperTest = new FilesystemHelper($finder, $this->filesystem, $this->parameterBagStub);
        $this->filesystemHelperTest->getFilesList();
    }

    /**
     * @expectedException \Onixcat\Bundle\ViatecParserBundle\Exception\DirectoryNotFoundException
     */
    public function testGetFilesListDirectoryNotFoundException()
    {
        $this->parameterBagStub->method('get')
            ->willReturn('/var/www/html/var/tmp/parser/viatec/err_dir');

        $this->filesystemHelperTest->getFilesList();
    }

    /**
     * @expectedException \Onixcat\Bundle\ViatecParserBundle\Exception\FailedCreateDirException
     */
    public function testCreateDirForParsedFilesFailedCreateDirException()
    {
        $this->filesystemHelperTest->createDirForParsedFiles('');
    }

    /**
     * @expectedException \Onixcat\Bundle\ViatecParserBundle\Exception\FailedToChmodFileException
     */
    public function testSetPermissionToFile()
    {
        $this->filesystemHelperTest->setPermissionToFile('');
    }

    /**
     * @expectedException \Onixcat\Bundle\ViatecParserBundle\Exception\FileNotFoundException
     */
    public function testDownloadFileException()
    {
        $this->parameterBagStub->method('get')
            ->willReturn('/var/www/html/var/tmp/parser/viatec/');

        $this->filesystemHelperTest->downloadFile('testException');
    }

    /**
     * @dataProvider getFileProvider
     * @param $expected
     * @param $file
     */
    public function testDownloadFile($expected, $file)
    {
        $this->parameterBagStub->method('get')
            ->willReturn('');
        $this->assertInternalType($expected, $this->filesystemHelperTest->downloadFile($file));
    }

    public function getFileProvider()
    {
        return
            [
                ['string', __DIR__ . '/../Fixtures/testExcelFile.xlsx']
            ];
    }
}
