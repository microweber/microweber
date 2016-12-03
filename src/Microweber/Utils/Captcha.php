<?php

namespace Microweber\Utils;

class Captcha
{
    public function validate($key, $captcha_id = null, $unset_if_found = true)
    {
        if ($key == false) {
            return false;
        }
        $key = trim($key);
        $old_array = mw()->user_manager->session_get('captcha_recent');
        if (is_array($old_array)) {
            $old_array = array_map(function ($piece) {
                return (string)$piece;
            }, $old_array);
        }
        $existing = mw()->user_manager->session_get('captcha');
        if (is_array($old_array) and in_array($key, $old_array)) {
            $found_key = array_search($key, $old_array);
            if ($found_key !== false) {
                if ($unset_if_found) {
                    unset($old_array[$found_key]);
                }
                mw()->user_manager->session_set('captcha_recent', $old_array);
            }

            return true;
        }
        if ($captcha_id == false) {
            $existing = mw()->user_manager->session_get('captcha');
        } else {
            $existing = mw()->user_manager->session_get('captcha_' . $captcha_id);
        }
        if ($existing == $key) {
            return true;
        } else {
            return false;
        }
    }

    public function render($params = array())
    {
        $roit1 = rand(1, 6);
        $font = dirname(__FILE__) . DS . 'catcha_fonts' . DS . 'font' . $roit1 . '.ttf';
        $font = normalize_path($font, 0);

        if (function_exists('imagettftext')) {
            $text1 = mt_rand(100, 4500);
        } else {
            $text1 = mt_rand(100, 999);
        }
        $text2 = mt_rand(2, 9);
        $roit = mt_rand(1, 5);
        $text = "$text1";
        $answ = $text1;
        $x = 100;
        $y = 20;
        $image = @imagecreate($x, 20) or die('Unable to render a CAPTCHA picture!');

        $tcol1z = rand(1, 150);
        $ttcol1z1 = rand(0, 150);
        $tcol1z11 = rand(0, 150);

        $bgcolor = imagecolorallocate($image, 255, 255, 255);
        $gray = imagecolorallocate($image, 240, 240, 240);

        // $black = imagecolorallocate($image, $tcol1z, $ttcol1z1, $tcol1z11);
        $black = imagecolorallocate($image, 0, 0, 0);
        $captcha_sid = 'captcha';
        if (isset($params['id'])) {
            $captcha_sid = 'captcha_' . $params['id'];
        } elseif (isset($_GET['id'])) {
            $captcha_sid = 'captcha_' . $_GET['id'];
        }

        $old = mw()->user_manager->session_get('captcha');
        if ($old != false) {
            $old_array = mw()->user_manager->session_get('captcha_recent');
            if (!is_array($old_array)) {
                $old_array = array();
            }
            $old_array = array_unique($old_array);

            array_unshift($old_array, $old);
            array_slice($old_array, 20);
            mw()->user_manager->session_set('captcha_recent', $old_array);
        }
        if (!isset($old_array) or !is_array($old_array)) {
            $old_array = array();
        }
        $old_array[$captcha_sid] = $answ;
        mw()->user_manager->session_set('captcha_recent', $old_array);

        //dd($old_array);
        //  $sess = mw()->user_manager->session_set($captcha_sid, $answ);

        $col1z = rand(200, 242);
        $col1z1 = rand(150, 242);
        $col1z11 = rand(150, 242);
        $color1 = imagecolorallocate($image, $col1z, $col1z1, $tcol1z11);
        $color2 = imagecolorallocate($image, $tcol1z - 1, $ttcol1z1 - 1, $tcol1z11 - 2);
        // imagefill($image, 0, 0, $color1);
        for ($i = 0; $i < $x; ++$i) {
            for ($j = 0; $j < $y; ++$j) {
                if (mt_rand(0, 20) < 10) {

                    //$coords = array(mt_rand(0, 10), mt_rand(0, 10), mt_rand(0, 10), mt_rand(0, 10), 5, 6);

                    $y21 = mt_rand(5, 20);
                    $this->captcha_vector($image, $x - mt_rand(0, 10), mt_rand(0, 10), mt_rand(0, 180), 200, $bgcolor);
                    //  imagesetpixel($image, $i, $j, $color2);
                }
            }
        }

        $x1 = mt_rand(0, 5);
        $y1 = mt_rand(20, 22);

        $tsize = rand(13, 15);

        $pad = 2;  // extra char spacing for text

        if (function_exists('imagettftext')) {
            imagettftext($image, $tsize, $roit, $x1, $y1, $black, $font, $text);
        } else {
            if (function_exists('imagestring')) {
                $font = mw_includes_path() . DS . 'admin' . DS . 'catcha_fonts' . DS . 'font' . $roit1 . '.gdf';
                $font = normalize_path($font, 0);
                $font = imageloadfont($font);
                imagestring($image, $font, 0, 0, $text, $black);
            } else {
            }
        }
        $s = 180;
        $e = 360;

        if (function_exists('imagefilter')) {
            $filter_img = rand(1, 6);

            switch ($filter_img) {
                case 1:
                    $gaussian = array(array(1.0, 2.0, 1.0), array(2.0, 4.0, 2.0), array(1.0, 2.0, 1.0));
                    imageconvolution($image, $gaussian, 16, 0);
                    break;

                // break;
                case 3:
                    imagefilter($image, IMG_FILTER_PIXELATE, 1);
                    break;

                default:

                    break;

            }
        }

        $y21 = mt_rand(5, 20);
        $this->captcha_vector($image, $x, $y21 / 2, 180, 200, $bgcolor);

        $y21 = mt_rand(5, 20);
        $this->captcha_vector($image, $x, $y21 / 2, $col1z11, 200, $bgcolor);

        $y21 = mt_rand(5, 20);
        $this->captcha_vector($image, $x / 3, $y21 / 3, $col1z11, 200, $bgcolor);

        if (function_exists('imagestring')) {
            $this->captcha_vector($image, $x / 3, $y21 / 3, $col1z11, 200, $gray);
            imagestring($image, 0, $y21, 2, $text, $gray);
        }


        ob_start();
        imagepng($image);
        imagecolordeallocate($image, $bgcolor);
        imagecolordeallocate($image, $black);

        imagedestroy($image);
        
        $stuff = ob_get_clean();

        return response($stuff)
            ->header('Content-Type','image/png')
            ->header('Pragma','no-cache')
            ->header('Cache-Control','no-store, no-cache, must-revalidate')
            ->header('Cache-Control','max-age=60, must-revalidate');



    }

    private function captcha_vector($palette, $startx, $starty, $angle, $length, $colour)
    {
        $angle = deg2rad($angle);
        $endx = $startx + cos($angle) * $length;
        $endy = $starty - sin($angle) * $length;

        return imageline($palette, $startx, $starty, $endx, $endy, $colour);
    }
}
