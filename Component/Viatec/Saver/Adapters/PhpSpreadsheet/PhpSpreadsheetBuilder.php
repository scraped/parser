<?php

namespace Onixcat\Component\Viatec\Saver\Adapters\PhpSpreadsheet;


use Onixcat\Component\Viatec\Saver\Adapters\SaverAdapterInterface;
use Onixcat\Component\Viatec\Saver\Adapters\SaverHelperInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class PhpSpreadsheetBuilder implements SaverAdapterInterface
{
    /**
     * @var array
     */
    private $excelFileHeader;

    /**
     * PhpSpreadsheetBuilder constructor.
     * @param array $excelFileHeader
     */
    public function __construct(array $excelFileHeader)
    {
        $this->excelFileHeader = $excelFileHeader;
    }

    /**
     * @inheritdoc
     */
    public function build(): SaverHelperInterface
    {
        return new PhpSpreadsheetHelper(new Spreadsheet(), $this->excelFileHeader);
    }
}
