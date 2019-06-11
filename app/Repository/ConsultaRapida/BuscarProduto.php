<?php


namespace App\Repository\ConsultaRapida;


use App\Repository\QuerySefaz;

class BuscarProduto
{
    private $header;
    private $rows;

    private $blackList;

    public function init($codigo_barra)
    {
        $this->rows = (new QuerySefaz())->queryProduto($codigo_barra,1);
        $row =   $this->monthedArray();

        return ['headers' => $this->header, 'rows' => [collect($row)] ];
    }

    private function monthedArray()
    {
        $data       = [];

        $this->header = [
            ['text' => 'COD. BARRAS',   'value' => 'COD_BARRAS' ],
            ['text' => 'PRODUTO',       'value' => 'PRODUTO'    ]
        ];
        $this->blackList = [];
        foreach ($this->rows as $key => $row){

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
                'endereco' => $valor['endereco'],
                'valor' =>     $valor['valor'],
                'color' =>     $color,
            ];
        }

        return $newValores;
    }
}