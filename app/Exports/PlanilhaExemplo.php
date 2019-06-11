<?php


namespace App\Exports;


use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PlanilhaExemplo implements WithHeadings, ShouldAutoSize, WithEvents
{
    public function headings(): array
    {
        return [
            'PRODUTO',
            'CODIGO DE BARRAS',
        ];
    }
    public function registerEvents(): array
    {
        return [
        ];
    }
}