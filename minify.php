<?php

/*
 * Minify class
 */

class minify {
    /*
     * HTML, XHTML, XML
     */

    public static function xtml($content) {
        $search = [
            '/\>[^\S ]+|> |>  |>   /si',
            '/[^\S ]+\<| <|  <|   </si',
            '/(\s)+/si',
            "/\r\n|\r|\n|\t|<!--(.*?)-->/si"
        ];
        $replace = ['> ', ' <', '\\1', ''];
        $content = preg_replace($search, $replace, $content);
        return $content;
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

        $search = ['/ {/s', '/ }/s', '/ :|: /s', '/ ,|, /s'];
        $replace = ['{', '}', ':', ','];
        $css = preg_replace($search, $replace, $css);
        $css = trim($css, "\t\n\r\0\x0B");
        return $css;
    }

    /*
     * JavaScript, PHP
     */

    public function script($script) {
        $search = [
            '!/\*[^*]*\*+([^/][^*]*\*+)*/!',
            '/\n|\r|\t|\r\n|  |   |    /',
        ];

        $script = preg_replace($search, '', $script);
        $script = trim($script, "\t\n\r\0\x0B");
        return $script;
    }

    /*
     * JSON
     */

    public function json($json) {
        $search = ['/[\p{Z}\s]{2,}/u'];
        $json = preg_replace($search, '', $json);
        $json = trim($json, "\t\n\r\0\x0B");
        return $json;
    }

}

?>