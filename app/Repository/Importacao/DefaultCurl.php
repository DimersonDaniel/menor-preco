<?php
/**
 * Created by PhpStorm.
 * User: dimer
 * Date: 28/02/2019
 * Time: 15:02
 */

namespace App\Repository;


class DefaultCurl
{
    public function connect($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_PROXY, '127.0.0.1:8888');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
        //curl_setopt($ch, CURLOPT_VERBOSE, true); // HABILITA CABEÇALHO DAS REQUISIÇOES NO CONSOLE//
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        return $ch;
    }
}