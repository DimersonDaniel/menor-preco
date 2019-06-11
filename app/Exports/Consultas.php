<?php


namespace App\Exports;

use App\Models\StoreEndereco;
use App\Models\StoreConsultas;
use App\Models\StoreProducts;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class Consultas implements FromCollection, WithHeadings, WithColumnFormatting, WithMapping,ShouldAutoSize, WithEvents
{
    /**
     *
     * @param string $consulta
     */

    private $consulta;
    private $enderecos;

    public function __construct()
    {
        $this->enderecos = StoreEndereco::where('active','=',1)->get();
        $this->consulta = StoreConsultas::select(['id','id_endereco','id_produto','valor'])
            ->whereIn('id_endereco',$this->enderecos->pluck('id'))->get();
    }

    public function headings(): array
    {

        $header = [
            'COD_BARRAS',
            'PRODUTO',
        ];
        foreach ( $this->enderecos as $value){
            $header[] = $value->local;
        }
        return $header;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $produtos = StoreProducts::all();
        return $produtos;
    }

    public function map($cell): array
    {
        $dados = [
            $cell->condigo_barrra,
            $cell->name,
        ];

        foreach ( $this->enderecos as $value){

            $dados[] =  $this->getValue($cell->id,$value->id);
        }
        return $dados;
    }

    private function getValue($idProduto, $idLocal)
    {
        $valor = StoreConsultas::whereIn('id',  $this->enderecos->pluck('id')->toArray())
            ->where('id_endereco','=',$idLocal)
            ->where('id_produto','=',$idProduto)->first();

        $value = "0";

        if($valor){
            $value = $valor->valor;
        }

        return $value;
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