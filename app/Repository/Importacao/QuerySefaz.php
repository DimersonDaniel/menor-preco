<?php


namespace App\Repository;

use DOMDocument;
use DOMXPath;

class QuerySefaz
{

    public $totalPages = 1;
    private $codigoBarra;
    private $page;

    /**
     * @return mixed
     */
    public function getCodigoBarra()
    {
        return $this->codigoBarra;
    }

    /**
     * @param mixed $codigoBarra
     * @return QuerySefaz
     */
    public function setCodigoBarra($codigoBarra)
    {
        $this->codigoBarra = $codigoBarra;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param mixed $page
     * @return QuerySefaz
     */
    public function setPage($page)
    {
        $this->page = $page;
        return $this;
    }

    public function execute()
    {
        $ch = new DefaultCurl();
        $ch =  $ch->connect("https://buscapreco.sefaz.am.gov.br/item/grupo/page/".$this->getPage());
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
        curl_setopt($ch, CURLOPT_POSTFIELDS,  'cdGtin='.$this->getCodigoBarra().
            '&descricaoProd='.$this->getCodigoBarra().
            '&_consultaExata=on'.
            '&tipoConsulta=168'.
            '&distancia=9999'.
            '&municipio=Manaus'
        );
        curl_setopt($ch, CURLOPT_POST, 1);
        $output = curl_exec($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($output, 0, $header_size);
        curl_close($ch);

        $this->getPages($output);

        $proutos = $this->produtos($output);

        return $proutos;
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

    private function produtos($content)
    {
        $produto = $this->produtoName($content);

        $html_dom = new DOMDocument();
        @$html_dom->loadHTML($content);
        $divs = $html_dom->getElementsByTagName('div');
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
                array_push($data[$count],$this->getCodigoBarra());

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

    public static function findDomHtml($content,$attr, $prop )
    {
        $html_dom = new DOMDocument();
        @$html_dom->loadHTML($content);
        $xpath = new DOMXPath($html_dom);
        return $xpath->query("//*[@".$attr."='".$prop."']");
    }
}