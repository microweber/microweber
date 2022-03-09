<?php

namespace MicroweberPackages\Utils\Captcha\Adapters;

class MicroweberCaptcha
{
    public function validate($key, $captcha_id = null, $unset_if_found = true)
    {
        if ($key == false) {
            return false;
        }

        $key = trim($key);

        $old_array = app()->user_manager->session_get('captcha_recent');
        if (is_array($old_array)) {
            $old_array = array_map(function ($piece) {
                return (string)$piece;
            }, $old_array);
        }

         if (is_array($old_array) and in_array($key, $old_array)) {
            $found_key = array_search($key, $old_array);
             if ($found_key !== false) {
                if ($unset_if_found) {
                    unset($old_array[$found_key]);
                }
                app()->user_manager->session_set('captcha_recent', $old_array);
                $this->reset();
                return true;
            }
        }

        $existing = app()->user_manager->session_get('captcha_' . $captcha_id);
         if ($existing == $key) {
             if ($captcha_id) {
                 $this->reset($captcha_id);
             }
            return true;
        } else {
            $existing = app()->user_manager->session_get('captcha');
            if ($existing == $key) {
                $this->reset();
                return true;
            }
            return false;
        }
    }

    public function reset($captcha_id = null)
    {
        $old = app()->user_manager->session_set('captcha',[]);
        $old = app()->user_manager->session_set('captcha_recent',[]);
        if ($captcha_id) {
            $old = app()->user_manager->session_set('captcha_' . $captcha_id,[]);
        }
    }

    public function render($params = array())
    {
        ob_get_clean();

        $roit1 = rand(1, 6);
        $font = dirname(dirname(__FILE__)). DS . 'catcha_fonts' . DS . 'font' . $roit1 . '.ttf';
        $font = normalize_path($font, 0);

        $x = 100;
        $y = 62;

        if (isset($params['w'])) {
            $x = intval($params['w']);
        }
        if (isset($params['h'])) {
            $y = intval($params['h']);
        }


        if (function_exists('imagettftext')) {
            $text1 = mt_rand(100, 4500);
        } else {
            $text1 = mt_rand(100, 999);
        }
        $text2 = mt_rand(2, 9);
        $roit = mt_rand(1, 5);
        $text = "$text1";
        $answ = $text1;


        $captcha_sid = 'captcha';
        if (isset($params['id'])) {
            $captcha_sid = 'captcha_' . $params['id'];
        } elseif (isset($_GET['id'])) {
            $captcha_sid = 'captcha_' . $_GET['id'];
        }

        $captcha_sid = 'captcha';

        $image = @imagecreate($x, $y) or die('Unable to render a CAPTCHA picture!');

        $tcol1z = rand(1, 150);
        $ttcol1z1 = rand(0, 150);
        $tcol1z11 = rand(0, 150);

        $bgcolor = imagecolorallocate($image, 255, 255, 255);
        $gray = imagecolorallocate($image, 230, 230, 230);

        // $black = imagecolorallocate($image, $tcol1z, $ttcol1z1, $tcol1z11);
        $black = imagecolorallocate($image, 0, 0, 0);


        $old = app()->user_manager->session_get('captcha');
        if ($old != false) {
            $old_array = app()->user_manager->session_get('captcha_recent');
            if (!is_array($old_array)) {
                $old_array = array();
            }

            $old_array[] =$old;
            // array_unshift($old_array, $old);
            //   array_slice($old_array, 20);
            // app()->user_manager->session_set('captcha_recent', $old_array);
        }
        if (!isset($old_array) or !is_array($old_array)) {
            $old_array = array();
        }
        if($captcha_sid != 'captcha'){
            $old_array[$captcha_sid] = $answ;
        } else {
            $old_array[] = $answ;
        }
        if($old_array){
            $old_array = array_unique($old_array);
            if(count($old_array)> 20){
                $old_array=  array_slice($old_array, 0,20);
            }
        }
        app()->user_manager->session_set('captcha_recent', $old_array);

        //dd($old_array);
       // dd($old_array);
        $sess = app()->user_manager->session_set($captcha_sid, $answ);
     //   dd($captcha_sid,$old_array);
        $col1z = rand(200, 242);
        $col1z1 = rand(150, 242);
        $col1z11 = rand(150, 242);
      //  $color1 = imagecolorallocate($image, $col1z, $col1z1, $tcol1z11);
       // $color2 = imagecolorallocate($image, $tcol1z - 1, $ttcol1z1 - 1, $tcol1z11 - 2);
        // imagefill($image, 0, 0, $color1);
        for ($i = 0; $i < $x; ++$i) {
            for ($j = 0; $j < $y; ++$j) {
                if (mt_rand(0, 15) < 10) {

                    //$coords = array(mt_rand(0, 10), mt_rand(0, 10), mt_rand(0, 10), mt_rand(0, 10), 5, 6);

                    $y21 = mt_rand(5, 20);
                    $this->captcha_vector($image, $x - mt_rand(0, 10), mt_rand(0, 10), mt_rand(0, 180), 200, $bgcolor);
                    //  imagesetpixel($image, $i, $j, $color2);
                    //  imagesetpixel($image, $i, $j, $color2);
                }

            }
        }


        $tsize = $y / 3;


        $digit = '';
        for ($rand_bg_digit = 15; $rand_bg_digit <= $x; $rand_bg_digit += 20) {
            $digit .= ($num = rand(0, 9));
            imagechar($image, rand(3, 5), $rand_bg_digit, rand(2, 14), $num, $gray);
        }
        $x1 = mt_rand($x / 5, $x / 2);
        $x1 = ($x / 2) - $x1;

        if ($text > 3) {
            $x1 = ($x / 9);
            $x1 = $x1 - 10;
        }


        $y1 = mt_rand(1, $y / 20);
        $y1 = ($y / 2) + $y1;


        if (function_exists('imagettftext')) {


            $font_size = 16;

            // Get image Width and Height
            $image_width = imagesx($image);
            $image_height = imagesy($image);

// Get Bounding Box Size
            $text_box = imagettfbbox($tsize,$roit,$font,$text);

// Get your Text Width and Height
            $text_width = $text_box[2]-$text_box[0];
            $text_height = $text_box[7]-$text_box[1];

// Calculate coordinates of the text
            $xa = ($image_width/2) - ($text_width/2);
            $ya = ($image_height/2) - ($text_height/2);

// Add some shadow to the text
            //imagettftext($im, $font_size, 0, $x, $y+1, $grey, $font, $text);

// Add the text
            imagettftext($image, $font_size, 0, $xa, $ya, $black, $font, $text);





            //imagettftext($image, $tsize, $roit, $x1, $y1, $black, $font, $text);
        } else {
            if (function_exists('imagestring')) {
                $font = mw_includes_path() . DS . 'admin' . DS . 'catcha_fonts' . DS . 'font' . $roit1 . '.gdf';
                $font = normalize_path($font, 0);
                $font = imageloadfont($font);
                // imagestring($image, $font, $x1, $y1, $text, $black);
                $image = $this->imagestringcentered($image, $font, $y1, $text, $black);


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
            //imagestring($image, 0, $y21, 2, $text, $gray);
        }

        ob_start();
        imagepng($image);
        imagecolordeallocate($image, $bgcolor);
        imagecolordeallocate($image, $black);

        imagedestroy($image);

        $stuff = ob_get_clean();


        return response($stuff)
            ->header('Content-Type', 'image/png')
            ->header('Pragma', 'no-cache')
            ->header('X-Robots-Tag', 'noindex, nofollow, noarchive, nosnippet')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate')
            ->header('Cache-Control', 'max-age=60, must-revalidate');


    }

    private function imagestringcentered($img, $font, $cy, $text, $color)
    {
        while (strlen($text) * imagefontwidth($font) > imagesx($img)) {
            if ($font > 1) {
                $font--;
            } else {
                break;
            }
        }
        imagestring($img, $font, imagesx($img) / 2 - strlen($text) * imagefontwidth($font) / 2, $cy, $text, $color);
        return $img;
    }

    private function captcha_vector($palette, $startx, $starty, $angle, $length, $colour)
    {
        $angle = deg2rad($angle);
        $endx = $startx + cos($angle) * $length;
        $endy = $starty - sin($angle) * $length;

        return imageline($palette, $startx, $starty, $endx, $endy, $colour);
    }
}
