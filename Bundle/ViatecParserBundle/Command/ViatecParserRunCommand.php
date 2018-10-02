<?php

namespace Onixcat\Bundle\ViatecParserBundle\Command;

use Onixcat\Bundle\ViatecParserBundle\Helpers\FilesystemHelper;
use Onixcat\Component\Viatec\Entity\Category;
use Onixcat\Component\Viatec\ParserProcessor;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ViatecParserRunCommand extends ContainerAwareCommand
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('viatec:parser:run')
            ->addOption(
                'enable-cache',
                null,
                InputOption::VALUE_OPTIONAL,
                'Enable cache usage while writing to file',
                'redis'
            )
            ->setDescription('Parse products category, name, code, inStock and price from http://viatec.ua');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     *
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $container = $this->getContainer();
            $path = $container->getParameter('parser_viatec.files_root_dir');
            $codePrefix = $container->getParameter('parser_viatec.product_code_prefix');
            $processor = $container->get(ParserProcessor::class);
            $filesystemHelper = $this->getContainer()->get(FilesystemHelper::class);
            //TODO: write message with using translator
            $output->writeln('Подготовка к парсингу...');

            if ($input->getOption('enable-cache') == 'redis') {
                $processor->enableCache();
                $output->writeln('Использование кеширования redis!');
            }
            $output->writeln('Создание директори для файлов, если необходимо...');
            $filesystemHelper->createDirForParsedFiles($path);
            $output->writeln('Установка параметров первого листа...');
            $processor->prepareParsing();
            $output->writeln('Получение списка категорий...');
            $categories = $processor->parseCategories(
                $container->getParameter('parser_viatec.main_page_url'),
                $container->getParameter('parser_viatec.categories_url'),
                $container->getParameter('parser_viatec.categories_excludes')
            );
            $output->writeln('Найдено категорий - ' . $categories);

            $output->writeln('Получение продуктов...');
            for ($i = 1; $i <= $categories; $i++) {
                /** @var Category $category */
                $category = $processor->getCategory();
                if ($category) {
                    $output->writeln('Парсинг и сохранение продуктов для категории № ' . $i . ' ' . $category->getName());
                    $processor->executeParsing($category, $category->getUrl(), $i, $codePrefix);
                }
            }
            $file = $processor->saveToFile($path, 'xlsx');
            $output->writeln('Парсинг закончен!');
            $output->writeln('Полученую информацию можно посмотреть в файле ' . $file);

        } catch (\Exception $e) {
            $output->writeln($e->getMessage());
        }
    }
}
