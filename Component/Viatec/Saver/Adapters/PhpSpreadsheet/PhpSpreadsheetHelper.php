<?php

namespace Onixcat\Component\Viatec\Saver\Adapters\PhpSpreadsheet;

use Doctrine\Common\Collections\Collection;
use Onixcat\Component\Viatec\Entity\Product;
use Onixcat\Component\Viatec\Saver\Adapters\Exception\WriterNotFoundException;
use Onixcat\Component\Viatec\Saver\Adapters\SaverHelperInterface;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\IWriter;

class PhpSpreadsheetHelper implements SaverHelperInterface
{

    /**
     * @var Spreadsheet
     */
    private $spreadsheet;

    /**
     * @var array
     */
    private $excelFileHeader;

    /**
     * @var int
     */
    private $ceilRow;


    /**
     * PhpSpreadsheetHelper constructor.
     * @param Spreadsheet $spreadsheet
     * @param array $excelFileHeader
     */
    public function __construct(Spreadsheet $spreadsheet, array $excelFileHeader)
    {
        $this->spreadsheet = $spreadsheet;

        $this->excelFileHeader = $excelFileHeader;
    }

    /**
     * @param string $path
     * @param string $fileExtension
     * @return string
     * @throws WriterNotFoundException
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function save(string $path, string $fileExtension): string
    {
        $class = "\PhpOffice\PhpSpreadsheet\Writer\\" . ucfirst($fileExtension);
        if (!class_exists($class)) {
            throw new WriterNotFoundException('Class for save file not found');
        }
        $date = new \DateTime();
        $fileName = 'viatec_' . $date->format('d-m-Y_H-i-s') . '.' . trim($fileExtension, ' .');
        $file = $path . '/' . $fileName;

        /**@var IWriter $writer */
        $writer = new $class($this->spreadsheet);
        $writer->save($file);

        return $fileName;
    }

    public function createFirstSheet()
    {
        //TODO: maybe set value from request param
        $firstSheet = $this->spreadsheet->createSheet(0);
        $firstSheet->setTitle('Sheet0');
        $firstSheet->getColumnDimension('A')->setWidth(40);
        $firstSheet->setCellValue('A1', 'Currency y.e. value must be set only to A2 !');
        $firstSheet->setCellValue('A2', 28);
    }

    /**
     * Write products to sheet
     *
     * @param Collection $products
     * @param Worksheet $sheet
     */
    public function writeProducts(Collection $products, Worksheet $sheet)
    {
        foreach ($products as $product) {
            /** @var Product $product */
            $sheet->setCellValue('A' . $this->ceilRow, $product->getCode());
            $sheet->setCellValue('C' . $this->ceilRow, $product->getName());
            $sheet->setCellValueExplicit('D' . $this->ceilRow, '=ROUND(sheet0!A2*F' . $this->ceilRow . ', 0)', DataType::TYPE_FORMULA);
            $sheet->setCellValue('E' . $this->ceilRow, $product->getInStock());
            $sheet->setCellValue('F' . $this->ceilRow, $product->getRetailPrice());
            $this->ceilRow++;
        }
    }

    /**
     * Prepare writing products to sheet
     *
     * @param $categoryName
     * @param $categoryIndex
     * @return Worksheet
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function prepareWrite(string $categoryName, int $categoryIndex): Worksheet
    {
        $title = mb_strimwidth($categoryIndex . $categoryName, 0, 31);
        if ($this->spreadsheet->getActiveSheetIndex() == $categoryIndex) {
            $activeSheet = $this->spreadsheet->getActiveSheet();
            if (empty($activeSheet->getCell('A1')->getValue())) {
                $activeSheet->setTitle($title);
                $activeSheet->setCellValue('A1', $categoryName);
                $this->setSheetHeader($activeSheet);
            }
            return $activeSheet;
        }

        $newSheet = $this->spreadsheet->createSheet($categoryIndex);
        $newSheet->setTitle($title);
        $newSheet->setCellValue('A1', $categoryName);
        $this->setSheetHeader($newSheet);
        $this->spreadsheet->setActiveSheetIndex($categoryIndex);

        return $newSheet;
    }

    /**
     * Set excel file header
     *
     * @param Worksheet $sheet
     */
    public function setSheetHeader(Worksheet $sheet)
    {
        foreach ($this->excelFileHeader as $ceil => $value) {
            $sheet->setCellValue($ceil, $value);
        }
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(64);
        $sheet->getColumnDimension('D')->setWidth(14);
        $sheet->getColumnDimension('E')->setWidth(14);
        $sheet->getColumnDimension('F')->setWidth(14);

        return $sheet;
    }

    /**
     * @param int $ceilStartRow
     */
    public function setCeilRow(int $ceilRow): void
    {
        $this->ceilRow = $ceilRow;
    }
}
