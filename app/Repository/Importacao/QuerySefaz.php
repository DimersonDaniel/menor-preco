<?php


namespace App\Repository;

use App\Models\StoreEndereco;
use DOMDocument;
use DOMXPath;

class QuerySefaz
{

    public $totalPages = 1;

    public function execute($codigoProduto){

        for ($page = 1; $page <= 10; $page++) {
            $this->queryProduto($codigoProduto, $page);
        }

    }

    public function queryProduto( $codigoProduto, $page)
    {
        $ch = new DefaultCurl();
        $ch =  $ch->connect("https://buscapreco.sefaz.am.gov.br/item/grupo/page/".$page);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Accept: text/html, application/xhtml+xml, application/xml;q=0.9", */*;q=0.8',
            'Referer: http://wanet01.netservicos.com.br/net1/',
            'Accept-Language: pt-BR, pt;q=0.8, en-US;q=0.5, en;q=0.3',
            'Accept-Encoding: gzip, deflate,br',
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64; rv:52.0) Gecko/20100101 Firefox/52.0',
            'Host: buscapreco.sefaz.am.gov.br',
            'Cookie: _ga=GA1.4.1671029631.1557320452; _gid=GA1.4.1137086119.1558361567; _gat=1; JSESSIONID='. $this->generateKey(32),
        ));
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS,  'termoCdGtin='.$codigoProduto.'&descricaoProd='.$codigoProduto.'&action=');
        curl_setopt($ch, CURLOPT_POST, 1);
        $output = curl_exec($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($output, 0, $header_size);
        curl_close($ch);

       $this->getPages($output);

       // return$this->ultimaCompra($output);

        return $this->produtos($output, $codigoProduto);
    }

    private function ultimaCompra($content)
    {
        $html_dom = new DOMDocument();
        @$html_dom->loadHTML($content);
        $divs = $html_dom->getElementsByTagName('p');
        $data = [];
        $count = 0;
        foreach ( $divs as $key => $div)
        {
            $attr = $div->getAttribute('class');

            if ($attr == 'tb-valor-10') {
                foreach (preg_split("/((\r?\n)|(\r\n?))/", $div->textContent) as $line)
                {

                    if (trim($line) == "") {
                        continue;
                    }

                    if (strpos(trim($line), "location_on") !== false) {
                        continue;
                    }

                    $data[] = trim($line);
                }
                $count++;
            }
        }
        return $data;

    }

    private function getPages($content)
    {
        $html_dom = new DOMDocument();
        @$html_dom->loadHTML($content);
        $divs = $html_dom->getElementsByTagName('ul');

        foreach ( $divs as $key => $div) {
            $attr = $div->getAttribute('class');
            if ($attr == 'pagination') {
                foreach (preg_split("/((\r?\n)|(\r\n?))/", $div->textContent) as $line) {
                    if (trim($line) == "") {
                        continue;
                    }

                    if(trim($line) == "chevron_right"){
                        continue;
                    }
                    if(trim($line) == "fast_forward"){
                        continue;
                    }
                    $this->totalPages = trim($line);

                }
            }
        }
    }

    private function generateKey($length) {
        $characters = 'ABCDEFGHIJLKMNOPQRSTUVXYZ0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    private function produtos($content, $codigoProduto)
    {
        $produto = $this->produtoName($content);

        $html_dom = new DOMDocument();
        @$html_dom->loadHTML($content);
        $divs = $html_dom->getElementsByTagName('div');
        $matriz = [];
        $data = [];
        $count = 0;
        foreach ( $divs as $key => $div){
            $attr = $div->getAttribute('class');

            if($attr == 'card-content'){
                foreach(preg_split("/((\r?\n)|(\r\n?))/", $div->textContent) as $line)
                {
                    if(trim($line) == ""){
                        continue;
                    }

                    $value = str_replace('location_on','',$line);
                    $value = str_replace(' location_city','',$value);

                    $data[$count][] = trim($value);

                }
                array_push($data[$count],$produto);
                array_push($data[$count],$codigoProduto);

                $count++;

            }

        }

        return $data;
    }

    private function produtoName($content)
    {
        $html_dom = new DOMDocument();
        @$html_dom->loadHTML($content);
        $divs = $html_dom->getElementsByTagName('div');;
        foreach ( $divs as $key => $div){
            $attr = $div->getAttribute('class');

//            if (!$this->filtros($div->textContent))
//            {
//                continue;
//            }

            if($attr == 'modal-content'){
                foreach(preg_split("/((\r?\n)|(\r\n?))/", $div->textContent) as $index => $line)
                {

                    if(trim($line) == ""){
                        continue;
                    }

                    $value = str_replace('location_on','',$line);
                    $value = str_replace(' location_city','',$value);

                    if($index == 1){
                        return trim($line);
                    }
                }

            }

        }
    }

//    private function filtros($context) : bool
//    {
//        foreach ($this->collection as $filtro)
//        {
//            if (strpos($context, $filtro->local) !== false)
//            {
//                return true;
//            }
//        }
//        return false;
//    }

    private function getConteudo($content)
    {

        $query = $this->findDomHtml($content,'class','row');
        $dados = [];
        if($query->length > 0){
            foreach($query as $row){
                $dados[] = $row->textContent;
            }
        }

        return $dados;

        $dados = [];
        $count = 0;
        foreach(preg_split("/((\r?\n)|(\r\n?))/", $this->findDomHtml($content,'class','col s12 m4')->textContent) as $line)
        {
            if(trim($line) == ""){
               continue;
            }
            $dados[$count][] = trim($line);
        }

        return $dados;
    }

    public static function findDomHtml($content,$attr, $prop )
    {
        $html_dom = new DOMDocument();
        @$html_dom->loadHTML($content);
        $xpath = new DOMXPath($html_dom);
        return $xpath->query("//*[@".$attr."='".$prop."']");
    }
}