<?php

/*
 * Image Class
 */

class image {

    private $status;
    private $mime;

    function __construct($arr = array()) {

        $this->status = [];
        $this->mime = [
            'image/jpeg',
            'image/pjpeg',
            'image/png',
            'image/gif',
            'image/bmp',
            'image/x-icon',
            'image/tiff',
            'image/x-tiff'
        ];

        $this->status[0] = 'pass';
    }

    private function real_path($file) {
        return preg_replace('/\\.[^.\\s]{3,4}$/', '', $file);
    }

    private function real_name($file) {
        return pathinfo($file, PATHINFO_FILENAME);
    }

    /*
     * Checking File
     */

    public function check($file, $mime = null) {

        if (isset($mime) && is_array($mime)) {
            $this->mime = $mime;
        }

        if (file_exists($file)) {

            if ($this->status['details'] = getimagesize($file)) {
                $this->status['file'] = $file;
                $this->status['saved'] = $file;
                $this->status['real_name'] = basename($file);
            } else {
                $this->status[0] = 'fail';
                $this->status['reason'] = 'not_img';
            }

            if (!in_array($this->status['details']['mime'], $this->mime)) {
                $this->status[0] = 'fail';
                $this->status['reason'] = 'wrong_mime';
            }
        } else {
            $this->status[0] = 'fail';
            $this->status['reason'] = 'not_found';
        }
        return $this->status;
    }

    /*
     * Crop Image
     */

    public function crop($file, $arr = array()) {
        $this->check($file);
        if (!is_numeric($arr['width']) || !is_numeric($arr['height'])) {
            $this->status[0] = 'fail';
            $this->status['reason'] = 'wrong_size';
        }
        if ($this->status[0] !== 'fail') {

            switch ($this->status['details']['mime']) {
                case 'image/jpeg':
                    $image = imagecreatefromjpeg($file);
                    $this->status['saved'] = $this->real_path($file) . '.jpeg';
                    $this->status['extn'] = '.jpeg';
                    break;

                case 'image/png':
                    $image = imagecreatefrompng($file);
                    $this->status['saved'] = $this->real_path($file) . '.png';
                    $this->status['extn'] = '.png';
                    break;

                case 'image/gif':
                    $image = imagecreatefromgif($file);
                    $this->status['saved'] = $this->real_path($file) . '.gif';
                    $this->status['extn'] = '.gif';
                    break;

                default:
                    $this->status[0] = 'fail';
                    $this->status['reason'] = 'not_support';
                    break;
            }

            if ($this->status[0] !== 'fail') {

                $width = imagesx($image);
                $height = imagesy($image);

                $original_aspect = $width / $height;
                $thumb_aspect = $arr['width'] / $arr['height'];

                if ($original_aspect >= $thumb_aspect) {
                    $new_height = $arr['height'];
                    $new_width = $width / ($height / $arr['height']);
                } else {
                    $new_width = $arr['width'];
                    $new_height = $height / ($width / $arr['width']);
                }

                $thumb = imagecreatetruecolor($arr['width'], $arr['height']);
                imagecopyresampled($thumb, $image, 0 - ($new_width - $arr['width']) / 2, 0 - ($new_height - $arr['height']) / 2, 0, 0, $new_width, $new_height, $width, $height);

                switch ($this->status['details']['mime']) {
                    case 'image/jpeg':
                        imagejpeg($thumb, $this->status['saved']);
                        break;

                    case 'image/png':
                        imagepng($thumb, $this->status['saved']);
                        break;

                    case 'image/gif':
                        imagegif($thumb, $this->status['saved']);
                        break;
                }
            }
        }
        return $this->status;
        unset($arr, $image, $width, $height, $original_aspect, $thumb_aspect, $new_height, $new_width, $thumb);
    }

    /*
     * Convert Image
     */

    public function convert($file, $to) {
        $this->check($file);
        if ($this->status[0] !== 'fail') {

            switch ($to) {

                /*
                 * convert to jpeg
                 */
                case ($this->status['details']['mime'] === 'image/jpeg' && $to === 'jpeg'):
                    $image_obj = imagecreatefromjpeg($this->status['file']);
                    imagejpeg($image_obj, $this->real_path($file) . '.jpeg');
                    $this->status['saved'] = $this->real_path($file) . '.jpeg';
                    $this->status['covert_job'] = 'jpeg_jpeg';
                    $this->status['extn'] = '.jpeg';

                    imagedestroy($image_obj);
                    break;

                case ($this->status['details']['mime'] === 'image/jpeg' && $to === 'png'):
                    $image_obj = imagecreatefromjpeg($this->status['file']);
                    imagepng($image_obj, $this->real_path($file) . '.png');
                    $this->status['saved'] = $this->real_path($file) . '.png';
                    $this->status['covert_job'] = 'jpeg_png';
                    $this->status['extn'] = '.png';
                    imagedestroy($image_obj);
                    break;

                case($this->status['details']['mime'] === 'image/jpeg' && $to === 'gif'):
                    $image_obj = imagecreatefromjpeg($this->status['file']);
                    imagegif($image_obj, $this->real_path($file) . '.gif');
                    $this->status['saved'] = $this->real_path($file) . '.gif';
                    $this->status['covert_job'] = 'jpeg_gif';
                    $this->status['extn'] = '.gif';
                    imagedestroy($image_obj);
                    break;

                /*
                 * convert to png
                 */
                case ($this->status['details']['mime'] === 'image/png' && $to === 'png'):
                    $image_obj = imagecreatefrompng($this->status['file']);
                    imagepng($image_obj, $this->real_path($file) . '.png');
                    $this->status['saved'] = $this->real_path($file) . '.png';
                    $this->status['covert_job'] = 'png_png';
                    $this->status['extn'] = '.png';
                    imagedestroy($image_obj);
                    break;

                case ($this->status['details']['mime'] === 'image/png' && $to === 'jpeg'):
                    $image_obj = imagecreatefrompng($this->status['file']);

                    $output = imagecreatetruecolor($this->status['details'][0], $this->status['details'][1]);
                    $white = imagecolorallocate($output, 255, 255, 255);
                    imagefilledrectangle($output, 0, 0, $this->status['details'][0], $this->status['details'][1], $white);
                    imagecopy($output, $image_obj, 0, 0, 0, 0, $this->status['details'][0], $this->status['details'][1]);

                    imagejpeg($output, $this->real_path($file) . '.jpeg');
                    $this->status['saved'] = $this->real_path($file) . '.jpeg';
                    $this->status['covert_job'] = 'png_jpeg';
                    $this->status['extn'] = '.jpeg';
                    imagedestroy($image_obj);
                    break;

                case ($this->status['details']['mime'] === 'image/png' && $to === 'gif'):
                    $image_obj = imagecreatefrompng($this->status['file']);
                    imagegif($image_obj, $this->real_path($file) . '.gif');
                    $this->status['saved'] = $this->real_path($file) . '.gif';
                    $this->status['covert_job'] = 'png_gif';
                    $this->status['extn'] = '.gif';
                    imagedestroy($image_obj);
                    break;

                /*
                 * convert to git
                 */
                case ($this->status['details']['mime'] === 'image/gif' && $to === 'gif'):
                    $image_obj = imagecreatefromgif($this->status['file']);
                    imagegif($image_obj, $this->real_path($file) . '.gif');
                    $this->status['saved'] = $this->real_path($file) . '.gif';
                    $this->status['covert_job'] = 'gif_gif';
                    $this->status['extn'] = '.gif';
                    imagedestroy($image_obj);
                    break;

                case ($this->status['details']['mime'] === 'image/gif' && $to === 'jpeg'):
                    $image_obj = imagecreatefromgif($this->status['file']);
                    imagegif($image_obj, $this->real_path($file) . '.jpeg');
                    $this->status['saved'] = $this->real_path($file) . '.jpeg';
                    $this->status['covert_job'] = 'gif_jpeg';
                    $this->status['extn'] = '.jpeg';
                    imagedestroy($image_obj);
                    break;

                case ($this->status['details']['mime'] === 'image/gif' && $to === 'png'):
                    $image_obj = imagecreatefromgif($this->status['file']);
                    imagegif($image_obj, $this->real_path($file) . '.png');
                    $this->status['saved'] = $this->real_path($file) . '.png';
                    $this->status['covert_job'] = 'gif_png';
                    $this->status['extn'] = '.png';
                    imagedestroy($image_obj);
                    break;

                /*
                 * Not Support
                 */
                default:
                    $this->status[0] = 'fail';
                    $this->status['reason'] = 'not_support';
                    break;
            }
        }
        return $this->status;
        unset($arr, $image_obj, $output, $white);
    }

    /*
     * Check, Crop, Convert & Save 
     * All in One
     */

    public function save($arr = array()) {

        if (!array_key_exists('mime', $arr) && is_array($arr['mime'])) {
            $this->mime = $arr['mime'];
        }

        $this->check($arr['file'], $arr['mime']);
        if ($this->status[0] !== 'fail') {

            if (array_key_exists('crop', $arr) && is_array($arr['crop'])) {
                $this->crop($this->status['saved'], $arr['crop']);
            }

            if (array_key_exists('convert', $arr)) {
                $this->convert($this->status['saved'], $arr['convert']);
            }

            if (!array_key_exists('name', $arr)) {
                $arr['name'] = $this->real_name($arr['file']);
            }

            if (array_key_exists('save', $arr)) {
                $arr['save'] = dirname($arr['file']);
            }

            $new_save = $arr['save'] . '/' . $arr['name'] . $this->status['extn'];
            rename($this->status['saved'], $new_save);
            $this->status['saved'] = $new_save;
        }

        return $this->status;
        unset($arr, $new_save);
    }

    function __destruct() {
        unset($this);
    }

}

?>