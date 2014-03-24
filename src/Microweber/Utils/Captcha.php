<?php



namespace Microweber\Utils;


class Captcha
{


    static function render()
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
        $image = @imagecreate($x, 20) or die("Unable to render a CAPTCHA picture!");

        $tcol1z = rand(1, 150);
        $ttcol1z1 = rand(0, 150);
        $tcol1z11 = rand(0, 150);

        $bgcolor = imagecolorallocate($image, 255, 255, 255);
        $gray = imagecolorallocate($image, 240, 240, 240);

        // $black = imagecolorallocate($image, $tcol1z, $ttcol1z1, $tcol1z11);
        $black = imagecolorallocate($image, 0, 0, 0);
        $captcha_sid = 'captcha';
        if (isset($_GET['id'])) {
            $captcha_sid = 'captcha_' . $_GET['id'];
        }

        $sess = mw()->user->session_set($captcha_sid, $answ);
        // $test = mw()->user->session_get('captcha');

        // session_write_close();
        $col1z = rand(200, 242);
        $col1z1 = rand(150, 242);
        $col1z11 = rand(150, 242);
        $color1 = imagecolorallocate($image, $col1z, $col1z1, $tcol1z11);
        $color2 = imagecolorallocate($image, $tcol1z - 1, $ttcol1z1 - 1, $tcol1z11 - 2);
        // imagefill($image, 0, 0, $color1);
        for ($i = 0; $i < $x; $i++) {
            for ($j = 0; $j < $y; $j++) {
                if (mt_rand(0, 20) < 10) {

                    //$coords = array(mt_rand(0, 10), mt_rand(0, 10), mt_rand(0, 10), mt_rand(0, 10), 5, 6);

                    $y21 = mt_rand(5, 20);
                    self::captcha_vector($image, $x - mt_rand(0, 10), mt_rand(0, 10), mt_rand(0, 180), 200, $bgcolor);
                    //  imagesetpixel($image, $i, $j, $color2);
                }
            }
        }





        $x1 = mt_rand(0, 5);
        $y1 = mt_rand(20, 22);


        $tsize = rand(13, 15);

        $pad = 2;                      // extra char spacing for text






        if (function_exists('imagettftext')) {
            imagettftext($image, $tsize, $roit, $x1, $y1, $black, $font, $text);
        } else if (function_exists('imagestring')) {
            $font = MW_INCLUDES_DIR . DS . 'admin' . DS . 'catcha_fonts' . DS . 'font' . $roit1 . '.gdf';
            $font = normalize_path($font, 0);
            $font = imageloadfont($font);
            imagestring($image, $font, 0, 0, $text, $black);


        } else {

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
        self::captcha_vector($image, $x, $y21 / 2, 180, 200, $bgcolor);

        $y21 = mt_rand(5, 20);
        self::captcha_vector($image, $x, $y21 / 2, $col1z11, 200, $bgcolor);

        $y21 = mt_rand(5, 20);
        self::captcha_vector($image, $x / 3, $y21 / 3, $col1z11, 200, $bgcolor);

        if (function_exists('imagestring')) {
       //  imagestring($image, 5, $y21, 2, $text, $bgcolor);
          self::captcha_vector($image, $x / 3, $y21 / 3, $col1z11, 200, $gray);
         imagestring($image, 0, $y21, 2, $text, $gray);

//            $line_color = imagecolorallocate($image, 64,64,64);
//            for($i=0;$i<10;$i++) {
//                imageline($image,0,rand()%50,200,rand()%50,$line_color);
//            }


        }

        $emboss = array(array(2, 0, 0), array(0, -1, 0), array(0, 0, -1));
        $embize = mt_rand(1, 4);
        // imageconvolution($image, $emboss, $embize, 255);
        //   imagefilter($image, IMG_FILTER_SMOOTH, 50);

        header("Content-type: image/png");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");


        imagepng($image);
        imagecolordeallocate($image, $bgcolor);
        imagecolordeallocate($image, $black);

        imagedestroy($image);
    }

    static function captcha_vector($palette, $startx, $starty, $angle, $length, $colour)
    {
        $angle = deg2rad($angle);
        $endx = $startx + cos($angle) * $length;
        $endy = $starty - sin($angle) * $length;
        return (imageline($palette, $startx, $starty, $endx, $endy, $colour));
    }

    static function text_arc($im, $cx, $cy, $r, $s, $e, $txtcol, $txt, $font, $size)
    {
        $tlen = strlen($txt);
        $arclen = deg2rad($e - $s);
        $perChar = $arclen / ($tlen - 1); // monospaced text - you may want to measure each char and
        // space proportionally
        for ($i = 0, $theta = deg2rad($s); $i < $tlen; $i++, $theta += $perChar) {
            $ch = $txt{$i};
            $tx = $cx + $r * cos($theta);
            $thank_you = $cy + $r * sin($theta);
            $angle = rad2deg(M_PI * 3 / 2 - $theta);
            imagettftext($im, $size, $angle, $tx, $thank_you, $txtcol, $font, $ch);
        }
        return $im;
    }


}