<?php


namespace App\Repository\ListarProdutos;

use App\Models\StoreConsultas;
use App\Models\StoreEndereco;
use App\Models\StoreProducts;

class ProdutosManagerFilters
{
    private $request;
    private $endereco;
    private $consulta;
    public function __construct($request)
    {
        $this->request = $request;
        $this->endereco = StoreEndereco::all();
        $this->consulta = StoreConsultas::select(['id','id_endereco','id_produto','valor'])->get();
    }

    public function listar()
    {
        $pesquisa =  $this->createPesquisaProduto();

        $header = $this->createHeader();

        return ['headers' => $header, 'desserts' => $pesquisa];
    }

    private function pesquisa()
    {
        if(empty($this->request->search)){
            return null;
        }

        $produtos = StoreProducts::whereIn('id',$this->consulta->pluck('id_produto'))
        ->where('name','LIKE', '%'.$this->request->search.'%')->get();

        if($produtos->count() > 0)
        {
            return $produtos;
        }
        $produtos = StoreProducts::whereIn('id',$this->consulta->pluck('id_produto'))
       ->where('codigo_barra','=', $this->request->search)->get();

        if($produtos->count() > 0)
        {
            return $produtos;
        }

    }

    private function createHeader(){

        $header = [
            ['text' => 'ID', 'value' => 'id'],
            ['text' => 'COD. BARRAS', 'value' => 'COD_BARRAS'],
            ['text' =>  'PRODUTO', 'value' => 'PRODUTO']
        ];
        foreach (  $this->endereco as $value){

            $header[] = ['text' => $value->local, 'value' => $value->local];
        }

        return $header;
    }

    private function createPesquisaProduto(){

        $produtos = $this->pesquisa();
        $matriz = [];

        if($produtos == null){
            return $matriz;
        }

        foreach ($produtos as $produto){
            $dados = [
                'id'            => $produto->id,
                'codigo_barra'  => $produto->codigo_barra,
                'name'          => $produto->name,
            ];
            foreach ( $this->endereco as $value){
                $valor = $this->consulta->whereStrict('id_endereco',$value->id)
                    ->whereStrict('id_produto',$produto->id)
                    ->first();

                $preco = "0";

                if($valor){
                    $preco = $valor->valor;
                    $idEndereco = $value->id;
                }
                $dados['company'][] = ['valor' => $preco , 'id' => $idEndereco ];
            }
            $matriz[] = $dados;
        }
        return $matriz;
    }
}