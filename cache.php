<?php

/*
 * PHP Server Side Cache Class
 */

class cache {

    public function encode($link) {
        $search = array('/', '?', '#');
        $replace = array('-s-', '-q-', '-h-');
        return str_replace($search, $replace, $link);
        unset($link, $search, $replace);
    }

    public function check($link, $dir, $extn = '.html') {
        $link = $this->encode($link);
        return (file_exists($dir . $link . $extn)) ? $file_path : false;
        unset($link, $dir, $extn);
    }

    public function create($link, $dir, $extn = '.html') {
        $entire_content = ob_get_contents();
        $link = $this->encode($link);
        if (is_dir($dir)) {
            file_put_contents($dir . $link . $extn, $entire_content);
        } else {
            return false;
        }
        unset($entire_content, $link, $dir, $extn);
    }

    public function remove($link, $dir, $extn = '.html') {
        $link = $this->encode($link);
        return (file_exists($dir . $link . $extn)) ? unlink($dir . $link . $extn) : false;
        unset($link, $dir, $extn);
    }

    function __destruct() {
        unset($this);
    }

}

?>