<?php

/*
 * Minify class
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

    public function css($css) {
        $search = [
            '!/\*[^*]*\*+([^/][^*]*\*+)*/!',
            '/\n|\r|\t|\r\n|  |   |    /',
        ];
        $css = preg_replace($search, '', $css);

        $search = ['/ {/s', '/ }/s', '/ :|: /s'];
        $replace = ['{', '}', ':'];
        $css = preg_replace($search, $replace, $css);
        $css = trim($css, "\t\n\r\0\x0B");

        unset($search, $replace);
        return $css;
        unset($css);
    }

    /*
     * JavaScript
     */

    public function js($js) {
        $search = [
            '!/\*[^*]*\*+([^/][^*]*\*+)*/!',
            '/\n|\r|\t|\r\n|  |   |    /',
        ];

        $js = preg_replace($search, '', $js);
        $js = trim($js, "\t\n\r\0\x0B");

        unset($search);
        return $js;
        unset($js);
    }

    /*
     * JSON
     */

    public function json($json) {
        $search = ['/[\p{Z}\s]{2,}/u'];
        $json = preg_replace($search, '', $json);
        $json = trim($json, "\t\n\r\0\x0B");

        unset($search);
        return $json;
        unset($json);
    }

    /*
     * XML
     */

    public function xml($xml) {
        $search = [
            '/\>[^\S ]+|> |>  |>   /si',
            '/[^\S ]+\<| <|  <|   </si',
            '/(\s)+/si',
            "/\r\n|\r|\n|\t|<!--(.*?)-->/si"
        ];
        $replace = ['> ', ' <', '\\1', ''];
        $xml = preg_replace($search, $replace, $xml);
        $xml = trim($xml, "\t\n\r\0\x0B");

        unset($search, $replace);
        return $xml;
        unset($xml);
    }

    function __destruct() {
        unset($this);
    }

}

?>