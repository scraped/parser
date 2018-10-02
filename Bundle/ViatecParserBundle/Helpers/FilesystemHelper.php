<?php


namespace Onixcat\Bundle\ViatecParserBundle\Helpers;

use Onixcat\Bundle\ViatecParserBundle\Exception\DirectoryNotFoundException;
use Onixcat\Bundle\ViatecParserBundle\Exception\FailedCreateDirException;
use Onixcat\Bundle\ViatecParserBundle\Exception\FailedToChmodFileException;
use Onixcat\Bundle\ViatecParserBundle\Exception\FileNotFoundException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class FilesystemHelper
{

    /**
     * @var Finder
     */
    private $finder;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var string
     */
    private $filesRootDir;

    /**
     * FilesystemHelper constructor.
     * @param Finder $finder
     * @param Filesystem $filesystem
     */
    public function __construct(Finder $finder, Filesystem $filesystem, string $filesRootDir)
    {
        $this->finder = $finder;
        $this->filesystem = $filesystem;
        $this->filesRootDir = $filesRootDir;
    }

    /**
     * Get list of parsed files
     *
     * @return array
     */
    public function getFilesList()
    {
        $filesList = [];

        try {
            $files = $this->finder->files()->in($this->filesRootDir)->sort(function (\SplFileInfo $a, \SplFileInfo $b) {
                return strcmp($b->getMTime(), $a->getMTime());
            });
        } catch (\InvalidArgumentException $e) {
            throw new DirectoryNotFoundException($e->getMessage());
        }

        if ($files->count() == 0) {
            throw new FileNotFoundException('Files not found');
        }

        foreach ($files as $file) {
            /** @var $file SplFileInfo */
            $filesList[] =
                [
                    'file_name' => $file->getRelativePathname(),
                ];
        }
        return $filesList;
    }

    /**
     * Create directory for files if necessary
     *
     * @param $path
     * @param int $mode
     */
    public function createDirForParsedFiles($path, $mode = 0777)
    {
        try {
            $this->filesystem->mkdir($path, $mode);
        } catch (\RuntimeException $e) {
            throw new FailedCreateDirException($e->getMessage());
        }
    }

    /**
     * Set permissions to created file, by default mode is 777
     *
     * @param string $pathToFile
     * @param int $mode
     */
    public function setPermissionToFile(string $pathToFile, $mode = 0777)
    {
        try {
            $this->filesystem->chmod($pathToFile, $mode);
        } catch (\RuntimeException $e) {
            throw new FailedToChmodFileException($e->getMessage());
        }
    }

    /**
     * Delete file from directory
     *
     * @param string $fileName
     * @return bool
     */
    public function deleteFile(string $fileName)
    {
        $file = $this->filesRootDir . '/' . $fileName;
        if (file_exists($file)) {
            $this->filesystem->remove($file);
            return true;
        }
        throw new FileNotFoundException('File' . $fileName . 'not found!');
    }

    /**
     * Download file from directory
     *
     * @param string $fileName
     * @return string
     */
    public function downloadFile(string $fileName): string
    {
        $file = $this->filesRootDir . '/' . $fileName;

        if (file_exists($file)) {
            return $file;
        }
        throw new FileNotFoundException('File' . $fileName . 'not found!');
    }

}
