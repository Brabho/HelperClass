<?php

/*
 * Captcha Class
 * GD Require
 * Font `.ttf`
 */

class captcha {
    /*
     * Generate Image File
     */

    private function create($arr = []) {

        /*
         * Checking GD 
         */

        if (!get_extension_funcs("gd")) {
            echo '<h1>GD Require</h1>';
            return false;
        }

        /*
         * Creating Image
         */

        $image = imagecreatetruecolor($arr['width'], $arr['height']);
        $c = array();
        if ($arr['color'] === 'rand') {
            $c[0] = mt_rand(0, 255);
            $c[1] = mt_rand(0, 255);
            $c[2] = mt_rand(0, 255);
            $c[3] = mt_rand(0, 127);
        } else {
            $c = explode(',', $arr['color']);
        }
        $bg_color = imagecolorallocatealpha($image, $c[0], $c[1], $c[2], $c[3]);
        imagefilledrectangle($image, 0, 0, $arr['width'], $arr['height'], $bg_color);
        $pixels = (($arr['width'] * $arr['height']) / 100) + 60;

        /*
         * Making Lines and Pixel
         */

        switch ($arr['style']) {
            case 'line':
                $line_color = imagecolorallocate($image, 0, 0, 0);
                for ($i = 0; $i < 5; $i++) {
                    imageline($image, 0, mt_rand() % 50, $arr['width'], mt_rand() % $arr['height'], $line_color);
                }
                break;

            case 'pixel':
                $pixel_color = imagecolorallocate($image, 0, 0, 0);
                for ($i = 0; $i < $pixels; $i++) {
                    imagesetpixel($image, mt_rand() % $arr['width'], mt_rand() % $arr['height'], $pixel_color);
                }
                break;

            case 'both':
                $line_color = imagecolorallocate($image, 0, 0, 0);
                for ($i = 0; $i < 5; $i++) {
                    imageline($image, 0, mt_rand() % 50, $arr['width'], mt_rand() % $arr['height'], $line_color);
                }

                $pixel_color = imagecolorallocate($image, 0, 0, 0);
                for ($i = 0; $i < $pixels; $i++) {
                    imagesetpixel($image, mt_rand() % $arr['width'], mt_rand() % $arr['height'], $pixel_color);
                }
                break;
        }

        /*
         * Image Text Colour
         */

        $tc = array(
            '0' => mt_rand(0, 255),
            '1' => mt_rand(0, 255),
            '2' => mt_rand(0, 255)
        );
        $text_color = imagecolorallocate($image, $tc[0], $tc[1], $tc[2]);

        /*
         * Generate Image Text (Number/String)
         */

        switch ($arr['text_type']) {
            case 'num':
                for ($i = -1; $i <= 4; $i++) {
                    $bytes = openssl_random_pseudo_bytes(4, $crypto_strong);
                    $img_text = hexdec(bin2hex($bytes));
                }
                break;

            case 'str':
                for ($i = -1; $i <= 4; $i++) {
                    $bytes = openssl_random_pseudo_bytes(4, $crypto_strong);
                    $img_text = bin2hex($bytes);
                }
                break;

            default :
                $img_text = $arr['text_type'];
                break;
        }

        $img_text = substr($img_text, 0, $arr['len']);

        /*
         * Font Style
         * Adding Font, Colour and Text
         * 
         * If no Font Style
         */

        if ($arr['font_style'] === '') {
            $font = mt_rand(3, 6);
            imagestring($image, $font, $arr['cx'], $arr['cy'], $img_text, $text_color);
        } else {

            /*
             * If font exists
             */

            $angle = mt_rand(-6, 6);
            imagettftext($image, $arr['font_size'], $angle, $arr['cx'], $arr['cy'], $text_color, $arr['font_style'], $img_text);
        }

        /*
         * Saving Image
         * 
         * Adding Extra Image
         */

        if ($arr['add_img'] !== '') {
            $im_file = imagecreatefrompng($arr['add_img']);
            imagecopymerge($im_file, $image, 0, 0, 0, 0, $arr['width'], $arr['height'], 55);
            imagepng($im_file, $arr['image_path'] . $arr['image_name'] . '.png', 9);
            imagedestroy($image);
            imagedestroy($im_file);
        } else {

            /*
             * If no extra image
             */

            imagepng($image, $arr['image_path'] . $arr['image_name'] . '.png', 9);
            imagedestroy($image);
        }

        /*
         * Set Session 
         */

        $_SESSION['captcha_code'] = hash('sha384', $img_text);
        $_SESSION['captcha_path'] = $arr['image_path'] . $arr['image_name'] . '.png';
    }

    /*
     * Creating Form
     */

    public function form($para = array()) {

        /*
         * Length of the Text
         */

        if (!isset($para['len'])) {
            $para['len'] = '4';
        }

        /*
         * Colour of the Background
         */

        if (!isset($para['color'])) {
            $para['color'] = 'rand';
        }

        /*
         * Line, Pixel(Dots)
         */

        if (!isset($para['style'])) {
            $para['style'] = 'both';
        }

        /*
         * String or Number or User Define
         */

        if (!isset($para['text_type'])) {
            $para['text_type'] = 'str';
        }

        /*
         * Width Of the Image
         */

        if (!isset($para['width'])) {
            $para['width'] = '75';
        }

        /*
         * Height Of the Image
         */

        if (!isset($para['height'])) {
            $para['height'] = '25';
        }

        /*
         * Image Text x-ordinate
         */

        if (!isset($para['cx'])) {
            $para['cx'] = '15';
        }

        /*
         * Image Text y-ordinate
         */

        if (!isset($para['cy'])) {
            $para['cy'] = '5';
        }

        /*
         * Create Image Name
         */

        if (!isset($para['image_name'])) {
            $para['image_name'] = time();
        }

        /*
         * Save Path
         */

        if (!isset($para['image_path'])) {
            $para['image_path'] = '';
        }

        /*
         * Add or Marge Extra Image
         */

        if (!isset($para['add_img'])) {
            $para['add_img'] = '';
        }

        /*
         * Use `.ttf` font
         */

        if (!isset($para['font_style'])) {
            $para['font_style'] = '';
        }

        /*
         * Font Size if using `.ttf` font
         */

        if (!isset($para['font_size'])) {
            $para['font_size'] = '12';
        }

        /*
         * Set Token
         */

        for ($i = -1; $i <= 4; $i++) {
            $ran = mt_rand(16, 32);
            $bytes = openssl_random_pseudo_bytes($ran, $crypto_strong);
            $token = bin2hex($bytes);
        }

        $_SESSION['captcha_token'] = $token;

        $this->create($para);

        echo PHP_EOL . '<img src="' . $para['image_path'] . $para['image_name'] . '.png?t=' . time() . '" alt="CAPTCHA" />';
        echo PHP_EOL . '<input class="captcha_text" name="captcha_text" type="text" placeholder="CAPTCHA" autocomplete="off" />';
        echo PHP_EOL . '<input type="hidden" name="captcha_token" value="' . $token . '" />';

        unset($para);
    }

    /*
     * Validation Captcha
     */

    public function valid() {

        /*
         * Delete Captcha Image File
         */

        if (file_exists($_SESSION['captcha_path'])) {
            unlink($_SESSION['captcha_path']);
        }

        /*
         * Remove Session Captcha Code
         */

        if (isset($_SESSION['captcha_code'])) {
            $captcha_code = $_SESSION['captcha_code'];
            $_SESSION['captcha_code'] = null;
            unset($_SESSION['captcha_code']);
        } else {
            return false;
        }

        /*
         * Remove Session Captcha Token
         */

        if (isset($_SESSION['captcha_token'])) {
            $captcha_token = $_SESSION['captcha_token'];
            $_SESSION['captcha_token'] = null;
            unset($_SESSION['captcha_token']);
        } else {
            return false;
        }

        /*
         * Checking 
         */

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['captcha_text']) && isset($_POST['captcha_token'])) {
            if ($captcha_code === hash('sha384', $_POST['captcha_text']) && $captcha_token === $_POST['captcha_token']) {
                return true;
            }
            return false;
        }
        return false;
    }

    function __destruct() {
        unset($this);
    }

}

?>