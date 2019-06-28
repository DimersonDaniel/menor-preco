<?php

namespace App\Repository\ConsultaRapida;

use App\Helpers\StatusResponse;
use App\Repository\QuerySefaz;

class BuscarProduto
{
    private $header;
    private $data;
    private $dataRos;

    private $blackList;

    public function init($codigo_barra)
    {
        try{
            $query = new QuerySefaz();
            $query->setCodigoBarra($codigo_barra)
                ->setPage(1);

            $this->data[]   = $query->execute();

            $this->monthedArrayOneRow();

            for ($page = 2; $page <= $query->totalPages; $page++)
            {
                if($page === 10){
                    break;
                }
                $this->data[] = $query->setPage($page)->execute();
            }

            if(count($this->data) > 1)
            {
                $this->monthedArrayMultRows();
            }else{
                $this->monthedArrayOneRow();
            }

            if(!$this->dataRos){
                return StatusResponse::errors(
                    '422',
                    'Nao Encotrado',
                    'Não foi possivel locaizar este produto!'
                );
            }

            return ['headers' => $this->header, 'rows' => collect($this->dataRos) ];
        }catch (\Exception $e)
        {
            return StatusResponse::errors(
                '422',
                'Nao Encotrado',
                'Não foi possivel locaizar este produto!'
            );
        }
    }

    private function monthedArrayMultRows()
    {
        $this->header = [
            ['text' => 'PRODUTO',   'value' => 'produto' ],
            ['text' => 'EMPRESA',   'value' => 'empresa' ],
            ['text' => 'VALOR',      'value' => 'valor'    ]
        ];

        foreach ($this->data as $key => $rows){

            foreach ( $rows as $row){

                if(in_array( $row[0], $this->blackList)){
                    continue;
                }

                $this->blackList[]  = $row[0];

                $valor = 0;

                if($row[2]){
                    $valor = str_replace('R$ ','',$row[2]);
                    $valor = str_replace(',','.',$valor);
                }


                $this->dataRos[] = [
                    'codigo_barra'  =>  $row[4],
                    'name'          =>  $row[3],
                    'company'       =>  $row[0],
                    'valor'         =>  $valor,
                    'endereco'      =>  $row[1],
                ];

            }

        }
    }
    private function monthedArrayOneRow()
    {
        $this->header = [
            ['text' => 'PRODUTO',   'value' => 'produto' ],
            ['text' => 'EMPRESA',   'value' => 'empresa' ],
            ['text' => 'VALOR',     'value' => 'valor'   ]
        ];

        foreach ($this->data as $key => $rows){
            foreach ( $rows as $row){

                if(in_array( $row[0], $this->blackList)){
                    continue;
                }

                $this->blackList[]  = $row[0];

                $valor = 0;

                if($row[2]){
                    $valor = str_replace('R$ ','',$row[2]);
                    $valor = str_replace(',','.',$valor);
                   // $valor = $row[2];
                }

                $this->dataRos[] = [
                    'codigo_barra'  =>  $row[4],
                    'name'          =>  $row[3],
                    'company'       =>  $row[0],
                    'valor'         =>  $valor,
                    'endereco'      =>  $row[1],
                ];

            }
        }
    }

    private function monthedArray()
    {
        $data       = [];

        $this->header = [
            ['text' => 'COD. BARRAS',   'value' => 'COD_BARRAS' ],
            ['text' => 'PRODUTO',       'value' => 'PRODUTO'    ]
        ];

        $this->blackList = [];

        foreach ($this->data as $key => $rows){

            foreach ( $rows as $row){

                if(in_array( $row[0], $this->blackList)){
                    continue;
                }

                $this->header[]     = ['text' => $row[0], 'value' => $row[0]];
                $this->blackList[]  = $row[0];

                $valor = 0;

                if($row[2]){
                    $valor = str_replace('R$ ','',$row[2]);
                    $valor = str_replace(',','.',$valor);
                }

                $valores[] = ['valor' => $valor, 'endereco' => $row[1]] ;

                $data = [
                    'codigo_barra'  =>  $row[4],
                    'name'          =>  $row[3],
                    'valores'       =>  $this->valoresWithEndereco($valores),
                ];

            }

        }

        return $data;
    }

    private function valoresWithEndereco($valores){

        $newValores     = [];
        $colorGreen     = '#29df00';
        $colorRed       = '#ec503e';

        foreach ($valores as $valor){

             $minValue = min($valores);

              if($minValue == $valor){
                  $color = $colorGreen;
              }else{
                  $color = $colorRed;
              }

            $newValores[] = [
                'company' => $valor['company'],
                'endereco' => $valor['endereco'],
                'valor' =>     $valor['valor'],
                'color' =>     '#000000',
            ];
        }

        return $newValores;
    }
}