<?php

/*
 * Minify class
 * (HTML, CSS, JavaScript, JSON, XML)
 */

class minify {
    /*
     * HTML
     */

    public static function html($html) {
        $search = [
            '/\>[^\S ]+|> |>  |>   /si',
            '/[^\S ]+\<| <|  <|   </si',
            '/(\s)+/si',
            "/\r\n|\r|\n|\t|<!--(.*?)-->/si"
        ];
        $replace = ['> ', ' <', '\\1', ''];
        $html = preg_replace($search, $replace, $html);

        unset($search, $replace);
        return $html;
        unset($html);
    }

    /*
     * CSS
     */

    public function css($file) {
        $buffer = file_get_contents($file);

        $search = [
            '!/\*[^*]*\*+([^/][^*]*\*+)*/!',
            '/\n|\r|\t|\r\n|  |   |    /',
        ];
        $buffer = preg_replace($search, '', $buffer);

        $search = ['/ {/s', '/ }/s', '/ :|: /s'];
        $replace = ['{', '}', ':'];
        $buffer = preg_replace($search, $replace, $buffer);
        $buffer = trim($buffer, "\t\n\r\0\x0B");

        unset($file, $css, $search, $replace);
        return $buffer;
        unset($buffer);
    }

    /*
     * JavaScript
     */

    public function js($file) {
        $buffer = file_get_contents($file);

        $search = [
            '!/\*[^*]*\*+([^/][^*]*\*+)*/!',
            '/\n|\r|\t|\r\n|  |   |    /',
        ];

        $buffer = preg_replace($search, '', $buffer);
        $buffer = trim($buffer, "\t\n\r\0\x0B");

        unset($file, $search, $replace);
        return $buffer;
        unset($buffer);
    }

    /*
     * JSON
     */

    public function json($json) {
        $buffer = '';
        $search = ['/[\p{Z}\s]{2,}/u'];
        $buffer = preg_replace($search, '', $json);
        $buffer = trim($buffer, "\t\n\r\0\x0B");

        unset($json, $search);
        return $buffer;
        unset($buffer);
    }

    /*
     * XML
     */

    public function xml($xml) {
        $buffer = '';
        $search = [
            '/\>[^\S ]+|> |>  |>   /si',
            '/[^\S ]+\<| <|  <|   </si',
            '/(\s)+/si',
            "/\r\n|\r|\n|\t|<!--(.*?)-->/si"
        ];
        $replace = ['> ', ' <', '\\1', ''];
        $buffer = preg_replace($search, $replace, $xml);
        $buffer = trim($buffer, "\t\n\r\0\x0B");

        unset($xml, $search, $replace);
        return $xml;
        unset($xml);
    }

    function __destruct() {
        unset($this);
    }

}

?>