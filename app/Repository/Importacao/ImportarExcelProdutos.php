<?php

namespace App\Repository\Importacao;

use App\Exports\ConsultasCSV;
use App\Models\JobsQueue;
use App\Models\JobsRegistro;
use App\Models\StoreFilters;
use App\Repository\ImportacaoQuerySefaz;
use App\Repository\Strategy;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportarExcelProdutos extends Strategy
{
    private $spreadsheet, $fullPath, $filters, $idqueue, $idResgistroQueue, $fileName, $id_user;

    public function __construct($fullPath, $fileName, $id_user)
    {
        $this->fullPath     = $fullPath;
        $this->fileName      = $fileName;
        $this->id_user      = $id_user;
        $this->filters      = StoreFilters::all();
        parent::__construct();
    }

    public function execute()
    {
       $this->startQueue();

        try{
            $storage = Storage::disk('local');
            $filePath = $storage->path($this->fullPath);

            if (!$storage->exists($this->fullPath))
            {
                throw new \Exception($this->fullPath.' -> Arquivo não encontrato!');
            }

            $spreadsheet = IOFactory::load($filePath);
            $this->spreadsheet = $spreadsheet->getActiveSheet()->toArray(null,true,true,true);

            unset($this->spreadsheet[1]);
            $this->start();
        }catch (\Exception $e){
            logger('ImportarExcelProdutos.php');
            logger($e->getTraceAsString());
            $this->faillqueue();
        }

    }

    private function start(){

        $this->dtNow = date('Y-m-d');
        $this->hrNow = date('H:i:s');
        $this->user_id = $this->id_user;
        foreach($this->spreadsheet as $dados)
        {
            $rows = (new ImportacaoQuerySefaz())
                ->setCodigoBarra($dados['B'])
                ->setFiltros($this->filters)
                ->setPage(1)
                ->execute();

            if($rows){
                try{
                    foreach ($rows as $item){
                        $this->svConsulta = new ProdutosConsultas();
                        $this->svConsulta->setIdUser(1);
                        $this->svConsulta->setEndereco($item[1]);
                        $this->svConsulta->setLocal($item[0]);
                        $this->svConsulta->setProdutoCodigoBarras($item[4]);
                        $this->svConsulta->setProdutoName($item[3]);
                        $this->svConsulta->setValor($item[2]);
                        $this->add();
                    }
                }catch (\Exception $e){
                    logger($e->getMessage());
                    continue;

                }
            }
        }

        (new ConsultasCSV())
            ->setIdQueue($this->idqueue)
            ->init();

        $this->finishQueue();

    }

    private function startQueue()
    {
        $jobs = new JobsQueue();
        $jobs->file_name = $this->fileName;
        $jobs->descricao = 'importacao';
        $jobs->path      =  '';
        $jobs->data      =  date('Y-m-d');
        $jobs->save();

        $this->idqueue = $jobs->id;

        $registro = new JobsRegistro();
        $registro->id_queue     =  $this->idqueue;
        $registro->id_situacao  =  1;
        $registro->name         =  'importacao-preco';
        $registro->save();

        $this->idResgistroQueue = $registro->id;
    }

    private function finishQueue()
     {
         $registro = JobsRegistro::find($this->idResgistroQueue);
         $registro->id_situacao  =  2;
         $registro->save();
     }

    private function faillqueue()
    {
        $registro = JobsRegistro::find($this->idResgistroQueue);
        $registro->id_situacao  =  3;
        $registro->save();
    }
}