<?php
/**
 * Created by PhpStorm.
 * User: dimer
 * Date: 27/02/2019
 * Time: 14:59
 */

namespace App\Repository;


use DOMDocument;
use DOMXPath;

class StringContent
{
    public static function findContent($str, $startDelimiter, $endDelimiter) : array
    {
        $contents = array();
        $startDelimiterLength = strlen($startDelimiter);
        $endDelimiterLength = strlen($endDelimiter);
        $startFrom = $contentStart = $contentEnd = 0;
        while (false !== ($contentStart = strpos($str, $startDelimiter, $startFrom))) {
            $contentStart += $startDelimiterLength;
            $contentEnd = strpos($str, $endDelimiter, $contentStart);
            if (false === $contentEnd) {
                break;
            }
            $contents[] = substr($str, $contentStart, $contentEnd - $contentStart);
            $startFrom = $contentEnd + $endDelimiterLength;
        }

        return $contents;
    }
    public static function montedObjectPost(array $value)
    {
        $objPost = "";
        foreach($value as $key => $item)
        {
            $objPost .= $key.'='.$item.'&';
        }
        $objPost = rtrim($objPost, '&');
        return $objPost;
    }
    public static function getCookie($_CONTENT, $POSITION)
    {
        $_CONTENT = explode('Set-Cookie: ', $_CONTENT);
        $_CONTENT = explode(';', $_CONTENT[$POSITION]);
        return $_CONTENT[0];
    }
    public static function findValueDomHtml($content, $attr, $element)
    {
        $html_dom = new DOMDocument();
        @$html_dom->loadHTML($content);
        $xpath = new DOMXPath($html_dom);
        return $xpath->query("//*[@".$attr."='".$element."']")->item(0);
    }
}