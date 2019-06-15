<?php


namespace App\Repository\ConsultaRapida;


use App\Repository\QuerySefaz;

class BuscarProduto
{
    private $header;
    private $data;

    private $blackList;

    public function init($codigo_barra)
    {
        $query = new QuerySefaz();
        $query->setCodigoBarra($codigo_barra)
                ->setPage(1);

        $this->data[]   = $query->execute();

        for ($page = 2; $page <= $query->totalPages; $page++)
        {
            $this->data[] = $query->setPage($page)
                ->execute();
            $row = $this->monthedArrayTs();
        }

        return ['headers' => $this->header, 'rows' => [collect($row)] ];
    }

    private function monthedArrayTs()
    {
        $data       = [];

        $this->header = [
            ['text' => 'PRODUTO',   'value' => 'PRODUTO' ],
            ['text' => 'EMPRESA',   'value' => 'EMPRESA' ],
            ['text' => 'VALOR',       'value' => 'VALOR'    ]
        ];

        $this->blackList = [];

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

                $valores[] = ['company' => $row[0], 'valor' => $valor, 'endereco' => $row[1]] ;

                $data = [
                    'codigo_barra'  =>  $row[4],
                    'name'          =>  $row[3],
                    'endereco'      =>  $row[2],
                    'valores'       =>  $this->valoresWithEndereco($valores),
                ];

            }

        }

        return $data;
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

//             $minValue = min($valores);

//              if($minValue == $valor){
//                  $color = $colorGreen;
//              }else{
//                  $color = $colorRed;
//              }

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