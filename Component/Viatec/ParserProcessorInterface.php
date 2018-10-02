<?php

namespace Onixcat\Component\Viatec;


use Onixcat\Component\Viatec\Entity\CategoryInterface;

interface ParserProcessorInterface
{
    public function executeParsing(CategoryInterface $category, string $categoryUrl, int $index, string $codePrefix): void;
}
