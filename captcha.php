<?php

/*
 * Captcha Class
 * GD Require
 */

class captcha {
    /*
     * Generate Image File
     */

    private function create($arr = array()) {

        if (!get_extension_funcs("gd")) {
            echo '<h2>GD not Installed</h2>';
            return false;
        }

        $image = imagecreatetruecolor($arr['width'], $arr['height']);
        $c = array();
        if ($arr['color'] == 'rand') {
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

        $tc = array(
            '0' => mt_rand(0, 255),
            '1' => mt_rand(0, 255),
            '2' => mt_rand(0, 255)
        );
        $text_color = imagecolorallocate($image, $tc[0], $tc[1], $tc[2]);

        $font = 6;
        if ($arr['font'] != '') {
            $font = imageloadfont($arr['font']);
        }

        $letters = '1234567890';
        $spl_letters = str_split($letters);
        $img_text = '';
        for ($i = 0; $i < $arr['len']; $i++) {
            $img_text .= $spl_letters[rand(0, 9)];
        }

        if ($arr['the_text'] === 'str') {
            $img_text = hash('sha256', $img_text);
        }

        $img_text = substr($img_text, 0, $arr['len']);

        imagestring($image, $font, $arr['cx'], $arr['cy'], $img_text, $text_color);

        if ($arr['im_path'] != '') {
            $im_file = imagecreatefrompng($arr['im_path']);
            imagecopymerge($im_file, $image, 0, 0, 0, 0, $arr['width'], $arr['height'], 35);
            imagepng($im_file, $arr['s_path'] . $arr['sim_nam'] . '.png', 9);
            imagedestroy($image);
            imagedestroy($im_file);
        } else {
            imagepng($image, $arr['s_path'] . $arr['sim_nam'] . '.png', 9);
            imagedestroy($image);
        }

        $_SESSION['captcha_code'] = hash('sha256', $img_text);
        $_SESSION['captcha_path'] = $arr['s_path'] . $arr['sim_nam'] . '.png';
    }

    /*
     * Creating Form
     */

    public function form($para = array()) {

        if (!isset($para['len']))
            $para['len'] = '4';
        if (!isset($para['color']))
            $para['color'] = 'rand';
        if (!isset($para['style']))
            $para['style'] = 'both';
        if (!isset($para['the_text']))
            $para['the_text'] = '';
        if (!isset($para['width']))
            $para['width'] = '75';
        if (!isset($para['height']))
            $para['height'] = '25';
        if (!isset($para['cx']))
            $para['cx'] = '15';
        if (!isset($para['cy']))
            $para['cy'] = '5';
        if (!isset($para['s_path']))
            $para['s_path'] = '';
        if (!isset($para['sim_nam']))
            $para['sim_nam'] = time();
        if (!isset($para['font']))
            $para['font'] = '';
        if (!isset($para['im_path']))
            $para['im_path'] = '';

        $this->create($para);
        echo '<img src="' . $para['s_path'] . $para['sim_nam'] . '.png?c=' . mt_rand(999, 99999) . '" alt="CAPTCHA" />' . "\n";
        echo '<input class="vp_captcha_text" name="captcha_text" type="text" placeholder="CAPTCHA" autocomplete="off" />';
        unset($para);
    }

    /*
     * Validation Captcha
     */

    public function valid() {
        if (file_exists($_SESSION['captcha_path'])) {
            unlink($_SESSION['captcha_path']);
        }
        $captcha_code = $_SESSION['captcha_code'];
        $_SESSION['captcha_code'] = null;
        unset($_SESSION['captcha_code']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['captcha_text'])) {
            return ($captcha_code === hash('sha256', $_POST['captcha_text']));
        }
    }

    function __destruct() {
        unset($this);
    }

}

?>