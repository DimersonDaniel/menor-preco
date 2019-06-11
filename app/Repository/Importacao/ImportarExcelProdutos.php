<?php

namespace App\Repository\Importacao;

use App\Repository\QuerySefaz;
use App\Repository\Strategy;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportarExcelProdutos extends Strategy
{
    private $spreadsheet, $fullPath;

    public function __construct($fullPath)
    {
        $this->fullPath  = $fullPath;
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
            $rows = (new QuerySefaz())->queryProduto($dados['B'],1);
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