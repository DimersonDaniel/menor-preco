<?php


namespace App\Exports;


use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeExport;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class Produtos implements FromCollection, WithHeadings, WithColumnFormatting, WithMapping,ShouldAutoSize, WithEvents
{
    /**
     *
     * @param string $consulta
     */

    private $consulta;

    public function __construct($consulta)
    {
        $this->consulta = $consulta;
    }

    public function headings(): array
    {
        return [
            'LOCAL',
            'ENDERECO',
            'VALOR',
            'PRODUTO',

        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->consulta;
    }

    public function map($cell): array
    {
        return $cell;
    }

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [

        ];
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event)
            {
                $cellRange = 'A1:W1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(12);
                $event->sheet->getDelegate()->getStyle('A1:W1')->getFont()->setBold(true);
            },
        ];
    }
}