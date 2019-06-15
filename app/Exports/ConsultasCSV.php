<?php


namespace App\Exports;


use App\Models\StoreConsultas;
use App\Models\StoreEndereco;
use App\Models\StoreFile;
use App\Models\StoreFilters;
use App\Models\StoreProducts;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;

class ConsultasCSV
{
    private $consulta;
    private $enderecos;
    private $filters;

    public function __construct()
    {
        $this->enderecos = StoreEndereco::all();
        $this->consulta = StoreConsultas::select(['id','id_endereco','id_produto','valor'])
            ->whereIn('id_endereco',$this->enderecos->pluck('id'))->get();
    }
    public function init()
    {
        $header = 'COD_BARRAS; PRODUTO; ';
        foreach ( $this->enderecos as $value){
            $header .= $value->local .';';
        }

        $rows = $header.PHP_EOL;

        $produtos = StoreProducts::all();

        foreach ($produtos as $produto)
        {

            if($produto->id !== $this->filters){

            }
            $rows .= $produto->codigo_barra . ";"
                .rtrim($produto->name,PHP_EOL) .";";

            foreach ( $this->enderecos as $value)
            {
                $rows .= $this->getValue($produto->id,$value->id) .";";
            }
            $rows .= PHP_EOL;
        }

        $fileName = (Uuid::uuid4())->toString();
        $storage = Storage::disk('local');
        $path = 'download/'.$fileName.'.csv';
        $storage->put($path, $rows);
        $this->savePath($path, $fileName);
    }

    private function getValue($idProduto, $idLocal)
    {
        $valor = $this->consulta->whereStrict('id_endereco',$idLocal)
            ->where('id_produto',$idProduto)->first();

        $value = "0";

        if($valor){
            $value = $valor->valor;
        }

        return $value;
    }

    private function savePath($path, $fileName){

        $savePathFile = new StoreFile();
        $savePathFile->file_name = $fileName.'.csv';
        $savePathFile->file_path = $path;
        $savePathFile->descricao = 'DOWNLOAD - IMPORTACAO';
        $savePathFile->data = date('Y-m-d');
        $savePathFile->save();
    }

}