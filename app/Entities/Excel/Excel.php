<?php

namespace App\Entities\Excel;

use Illuminate\Database\Eloquent\Collection;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Excel implements ExcelInterface
{
    private $spreadsheet;
    private $sheet;
    private $header;
    private $columns = [
        'A',
        'B',
        'C',
        'D',
        'E',
        'F',
        'G',
        'H',
        'I',
        'J',
        'K',
        'L',
        'M',
        'N',
        'O',
        'P',
        'Q',
        'R',
        'S',
        'T',
        'U',
        'V',
        'W',
        'X',
        'Y',
        'Z'
    ];
    private $currentRow = 1;
    private $data;
    private $filename;

    public function __construct(Collection $collection, array $header, string $filename = 'Spreadsheet')
    {

        $this->spreadsheet = new Spreadsheet();
        $this->sheet = $this->spreadsheet->getActiveSheet();
        $this->header = $header;
        $this->filename = $filename;
        $this->data = $collection;

        $this->build();
    }

    private function build(): void
    {
        $this->setSettings();
        $this->setHeader();
        $this->fill();
    }

    public function download(): void
    {

        $writer = new Xlsx($this->spreadsheet);

        $response = response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        });

        $response->setStatusCode(200);
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $this->filename . '.xlsx"');
        $response->send();
    }

    private function fill(): void
    {

        foreach ($this->data as $element) {

            foreach ($element->getAttributes() as $key => $value) {

                $index = array_search($key, array_keys($element->getAttributes()));
                $cellName = $this->columns[$index] . $this->currentRow;
                $this->sheet->setCellValue($cellName, $value);
            }

            $this->currentRow++;
        }
    }

    private function setHeader(): void
    {

        foreach ($this->header as $index => $columnTitle) {
            $cellName = $this->columns[$index] . $this->currentRow;
            $this->sheet->setCellValue($cellName, $columnTitle);
        }

        $this->currentRow++;
    }

    private function setSettings(): void
    {

        for ($i = 0; $i < count($this->header); $i++) {
            $this->spreadsheet->getActiveSheet()->getColumnDimension($this->columns[$i])->setAutoSize(true);
        }
    }


}
