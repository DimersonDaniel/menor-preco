<?php


namespace App\Exports;


use App\Models\JobsQueue;
use App\Models\StoreConsultas;
use App\Models\StoreEndereco;
use App\Models\StoreProducts;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;

class ConsultasCSV
{
    private $consulta;
    private $enderecos;
    private $idQueue;

    /**
     * @return mixed
     */
    public function getIdQueue()
    {
        return $this->idQueue;
    }

    /**
     * @param mixed $idQueue
     * @return ConsultasCSV
     */
    public function setIdQueue($idQueue)
    {
        $this->idQueue = $idQueue;
        return $this;
    }

    public function __construct()
    {
        $this->enderecos = StoreEndereco::all();
        $this->consulta = StoreConsultas::select(['id','id_endereco','id_produto','valor'])->get();
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
            $rows .= $produto->codigo_barra . ";"
                .rtrim($produto->name,PHP_EOL) .";";

            foreach ( $this->enderecos as $value)
            {
                $rows .= $this->getValue($produto->id, $value->id) .";";
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

        logger('update => '.  $this->getIdQueue());

        $jobs = JobsQueue::find($this->getIdQueue());
        $jobs->path      =  $path;
        $jobs->save();

    }

}