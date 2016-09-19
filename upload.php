<?php

/*
 * Upload File(s)
 */

class upload {

    public $file_name;
    public $new_name;
    public $save;
    public $min_size;
    public $max_size;
    public $multi;
    public $overwrite;
    public $space;
    public $mime;
    public $extension;
    private $status;
    private $compare;
    private $name;

    function __construct() {

        $this->status = [];
        $this->compare = [];

        $this->status[0] = 'pass';
        $this->save = '';
        $this->overwrite = false;
        $this->min_size = 1;
        $this->max_size = 10485760;
    }

    private function files($key) {
        return (isset($this->multi) && is_numeric($this->multi)) ? $_FILES[$this->file_name][$key][$this->multi] : $_FILES[$this->file_name][$key];
    }

    private function extension($file) {
        return end(explode('.', $file));
    }

    private function mime($file) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        return finfo_file($finfo, $file);
        finfo_close($finfo);
        unset($file, $finfo);
    }

    private function check1() {

        if (!is_uploaded_file($this->files('tmp_name'))) {
            $this->status[0] = 'fail';
            $this->status['reason'] = 'attack_upload';
            $this->status['where'] = 'check1';
        } else {
            $this->status['details'] = $_FILES[$this->file_name];
        }

        if (isset($this->mime) && is_array($this->mime)) {
            if (!in_array($this->files('type'), $this->mime)) {
                $this->status[0] = 'fail';
                $this->status['reason'] = 'wrong_mime';
                $this->status['where'] = 'check1';
            }
        }
        $this->compare['type'] = $this->files('type');

        if (isset($this->extension) && is_array($this->extension)) {
            if (!in_array($this->extension($this->files('name')), $this->extension)) {
                $this->status[0] = 'fail';
                $this->status['reason'] = 'wrong_extension';
                $this->status['where'] = 'check1';
            }
        }
        $this->compare['extension'] = $this->extension($this->files('name'));

        if ($this->files('size') < $this->min_size) {
            $this->status[0] = 'fail';
            $this->status['reason'] = 'small_size';
            $this->status['where'] = 'check1';
        } elseif ($this->files('size') > $this->max_size) {
            $this->status[0] = 'fail';
            $this->status['reason'] = 'big_size';
            $this->status['where'] = 'check1';
        }
        $this->compare['size'] = $this->files('size');

        if ($this->files('error') !== UPLOAD_ERR_OK || $this->files('error') > 0) {
            $this->status[0] = 'fail';
            $this->status['reason'] = 'header';
            $this->status['where'] = 'check1';
        }
    }

    private function progress() {
        $this->check1();
        if ($this->status[0] !== 'fail') {

            if (isset($this->space)) {
                $this->name = preg_replace('/\s/', $this->space, $this->files('name'));
            } elseif (isset($this->new_name)) {
                $this->name = $this->new_name . '.' . $this->compare['extension'];
            } else {
                $this->name = $this->files('name');
            }

            $this->status['name'] = $this->name;
            $this->status['path'] = $this->save . $this->name;

            if (file_exists($this->status['path'])) {
                if ($this->overwrite === true) {
                    move_uploaded_file($this->files('tmp_name'), $this->status['path']);
                } else {
                    $this->status[0] = 'fail';
                    $this->status['reason'] = 'already_exists';
                    $this->status['where'] = 'progress';
                }
            } else {
                move_uploaded_file($this->files('tmp_name'), $this->status['path']);
            }
        }
    }

    private function check2() {
        $this->progress();
        if ($this->status[0] !== 'fail') {

            if (filesize($this->status['path']) !== $this->compare['size']) {
                $this->status[0] = 'fail';
                $this->status['reason'] = 'size_changed';
                $this->status['where'] = 'check2';
            }

            if ($this->mime($this->status['path']) !== $this->compare['type']) {
                $this->status[0] = 'fail';
                $this->status['reason'] = 'type_changed';
                $this->status['where'] = 'check2';
            }

            if ($this->extension($this->status['path']) !== $this->compare['extension']) {
                $this->status[0] = 'fail';
                $this->status['reason'] = 'extension_changed';
                $this->status['where'] = 'check2';
            }

            if ($this->status[0] === 'fail') {
                unlink($this->status['path']);
            }
        }
    }

    /*
     * Main Function
     */

    public function start() {
        $this->check2();
        return $this->status;
    }

    function __destruct() {
        unset($this);
    }

}

?>