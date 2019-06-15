<?php

namespace App\Repository\Importacao;

use App\Models\StoreFilters;
use App\Repository\ImportacaoQuerySefaz;
use App\Repository\Strategy;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportarExcelProdutos extends Strategy
{
    private $spreadsheet, $fullPath, $filters;

    public function __construct($fullPath)
    {
        $this->fullPath  = $fullPath;
        $this->filters = StoreFilters::all();
        parent::__construct();
    }

    public function execute()
    {
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
    }

    private function start(){

        $this->dtNow = date('Y-m-d');
        $this->hrNow = date('H:i:s');

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

        return '';
    }
}