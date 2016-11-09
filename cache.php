<?php

/*
 * Server Side Cache Class
 */

class cache {
    /*
     * Encode Uri
     */

    public function encode($link) {
        $search = array('/', '?', '#');
        $replace = array('-s-', '-q-', '-h-');
        return str_replace($search, $replace, $link);
        unset($link, $search, $replace);
    }

    /*
     * Check file exists
     */

    public function check($link, $dir, $extn = '.html') {
        $link = $this->encode($link);
        return (file_exists($dir . $link . $extn)) ? $file_path : false;
        unset($link, $dir, $extn);
    }

    /*
     * Create Cache File
     */

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

    /*
     * Remove/Delete Cache File
     */

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