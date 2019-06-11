<?php


namespace App\Repository\Importacao;


use App\Models\StoreEndereco;
use App\Models\StoreProducts;

class ProdutosConsultas
{
   private $id_user;
   private $id_produto;
   private $id_local;
   private $valor;
   private $endereco;
   private $produtoName;
   private $produtoCodigoBarras;
   private $local;


    /**
     * @return mixed
     */
    public function getIdLocal()
    {
        return $this->id_local;
    }

    /**
     * @param mixed $id_local
     * @return ProdutosConsultas
     */
    public function setIdLocal($id_local)
    {
        $this->id_local = $id_local;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLocal()
    {
        return $this->local;
    }

    /**
     * @param mixed $local
     * @return ProdutosConsultas
     */
    public function setLocal($local)
    {
        $isConfig = StoreEndereco::where('local','=',$local);

        $id = null;

        if($isConfig->get()->count())
        {
            $id = $isConfig->first()->id;
        }else{
            $config = new StoreEndereco();
            $config->id_user     = $this->getIdUser();
            $config->local       = $local;
            $config->apelido     = $local;
            $config->endereco    = $this->getEndereco();
            $config->save();

            $id = $config->id;
        }

        $this->id_local = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getApelido()
    {
        return $this->apelido;
    }

    /**
     * @param mixed $apelido
     * @return ProdutosConsultas
     */
    public function setApelido($apelido)
    {
        $this->apelido = $apelido;
        return $this;
    }
   private $apelido;

    /**
     * @return mixed
     */
    public function getProdutoCodigoBarras()
    {
        return $this->produtoCodigoBarras;
    }

    /**
     * @param mixed $produtoCodigoBarras
     * @return ProdutosConsultas
     */
    public function setProdutoCodigoBarras($produtoCodigoBarras)
    {
        $this->produtoCodigoBarras = $produtoCodigoBarras;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdUser()
    {
        return $this->id_user;
    }

    /**
     * @param mixed $id_user
     * @return ProdutosConsultas
     */
    public function setIdUser($id_user)
    {
        $this->id_user = $id_user;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdProduto()
    {
        return $this->id_produto;
    }

    /**
     * @param mixed $id_produto
     * @return ProdutosConsultas
     */
    public function setIdProduto($id_produto)
    {
        $this->id_produto = $id_produto;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * @param mixed $valor
     * @return ProdutosConsultas
     */
    public function setValor($valor)
    {
        $valor = str_replace('R$ ','',$valor);
        $valor = str_replace(',','.',$valor);
        $this->valor = $valor;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProdutoName()
    {
        return $this->produtoName;
    }

    /**
     * @param mixed $produtoName
     * @return ProdutosConsultas
     */
    public function setProdutoName($produtoName)
    {
        $isProduto = StoreProducts::where('codigo_barra','=',$this->getProdutoCodigoBarras());

        $id = null;

        if($isProduto->get()->count())
        {
            $id = $isProduto->first()->id;
        }else{

            $storeProdutos = new StoreProducts();
            $storeProdutos->name            = $produtoName;
            $storeProdutos->codigo_barra  = $this->getProdutoCodigoBarras();
            $storeProdutos->save();

            $id = $storeProdutos->id;
        }
        $this->id_produto = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEndereco()
    {
        return $this->endereco;
    }

    /**
     * @param mixed $endereco
     * @return ProdutosConsultas
     */
    public function setEndereco($endereco)
    {
        $this->endereco = $endereco;
        return $this;
    }
}