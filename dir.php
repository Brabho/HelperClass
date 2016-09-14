<?php

/*
 * Directory Class
 */

class dir {

    public function mk($path) {
        $path = $path . '/';
        if (!is_dir($path)) {
            $directory_path = '';
            $directories = explode('/', $path);
            array_pop($directories);
            foreach ($directories as $directory) {
                $directory_path .= $directory . '/';
                if (!is_dir($directory_path)) {
                    mkdir($directory_path);
                    chmod($directory_path, 0777);
                }
            }
        }
        unset($path, $directory_path, $directories);
    }

    public function copy($src, $dest) {
        if (!file_exists($dest)) {
            mkdir($dest);
        }
        foreach (scandir($src) as $file) {
            $srcfile = trim($src, '/') . '/' . $file;
            $destfile = trim($dest, '/') . '/' . $file;
            if (!is_readable($srcfile)) {
                continue;
            } if ($file != '.' && $file != '..') {
                if (is_dir($srcfile)) {
                    if (!file_exists($destfile)) {
                        mkdir($destfile);
                    } $this->copy($srcfile, $destfile);
                } else {
                    copy($srcfile, $destfile);
                }
            }
        }
        unset($src, $dest);
    }

    public function del($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != '.' && $object != '..') {
                    if (filetype($dir . '/' . $object) == 'dir') {
                        $this->del($dir . '/' . $object);
                    } else {
                        unlink($dir . '/' . $object);
                    }
                }
            }
            reset($objects);
            rmdir($dir);
        }
        unset($dir, $objects, $object);
    }

    public function size($dir) {
        if (is_dir($dir)) {
            $size = 0;
            foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir)) as $file) {
                if ($file->getFileName() != '..' && $file->getFileName() != '.') {
                    $size += $file->getSize();
                }
            }
            return $size;
        } else {
            return false;
        }
        unset($dir, $size);
    }

    function __destruct() {
        unset($this);
    }

}

?>