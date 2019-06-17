<?php
/**
 * Created by PhpStorm.
 * User: JPaulo
 * Date: 18/06/2018
 * Time: 15:54
 */

namespace App\Repository;

use App\Models\StoreConsultas;
use App\Models\StoreEndereco;
use App\Models\StoreProducts;
use App\Repository\Importacao\ProdutosConsultas;

abstract class Strategy
{
    public $heard;
    public $methods;
    public $error;
    public $dtNow;
    public $hrNow;
    public $user_id;
    /** @var $svConsulta ProdutosConsultas */
    public $svConsulta;

    public function __construct()
    {
        StoreConsultas::truncate();
        StoreEndereco::truncate();
        StoreProducts::truncate();
    }

    abstract public function execute();

    public function add()
    {
        $this->insertConsulta();
    }


    private function insertConsulta()
    {

        $newConsulta = new StoreConsultas();
        $newConsulta->id_user       = $this->user_id;
        $newConsulta->id_produto    = $this->svConsulta->getIdProduto();
        $newConsulta->id_endereco   = $this->svConsulta->getIdLocal();
        $newConsulta->valor         = $this->svConsulta->getValor();
        $newConsulta->dateEntrada   = $this->dtNow;
        $newConsulta->horaEntrada   = $this->hrNow;
        $newConsulta->save();

    }
}