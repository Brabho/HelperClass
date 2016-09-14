<?php

/*
 * Zip Class
 */

class zip {

    public function add($source, $destiny) {
        if (!extension_loaded('zip') || !file_exists($source)) {
            return false;
        }
        $source = str_replace('\\', '/', $source);
        $zip = new ZipArchive();
        if (!$zip->open($destiny, ZIPARCHIVE::CREATE)) {
            return false;
        }

        if (is_dir($source)) {
            $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);
            foreach ($files as $file) {

                $file = str_replace('\\', '/', $file);
                if ($file === '.' || $file === '..') {
                    continue;
                }

                if (is_dir($file)) {
                    $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
                } elseif (is_file($file)) {
                    $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
                }
            }
        } elseif (is_file($source)) {
            $zip->addFromString(basename($source), file_get_contents($source));
        }
        return $zip->close();
    }

    public function extract($source, $destiny) {
        $zip = new ZipArchive;
        if ($zip->open($source)) {
            $zip->extractTo($destiny);
            $zip->close();
            return true;
        } else {
            return false;
        }
        unset($source, $destiny, $zip);
    }

    function __destruct() {
        unset($this);
    }

}

?>