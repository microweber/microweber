<?php

namespace Microweber\Utils\lib;


/**
 *
 * @from https://gist.github.com/mistic100/9433241
 *
 *
 * Generate a very simple image containing some text
 *
 * Basic usage:
 *    (new SimpleTextImage('Hello world!'))->render();
 *
 * All functionalities:
 *    (new SimpleTextImage())
 *      ->setText('Hello world!')
 *      ->setBackground(255,0,0)
 *      ->setForeground(0,255,255)
 *      ->setFontSize(2)
 *      ->setPadding(10)
 *      ->setFile('hello.jpg')
 *      ->render('jpg');
 *
 */
class SimpleTextImage
{

    var $text = '';
    var $font_size = 4;
    var $padding = 2;
    var $bg = array(0, 0, 0);
    var $fg = array(255, 255, 255);
    var $file = null;

    function __construct($text = '')
    {
        $this->text = $text;
    }

    function setText($text)
    {
        $this->text = $text;
        return $this;
    }

    function setFontSize($font_size)
    {

        $this->font_size = $font_size;
        return $this;
    }

    function setPadding($padding)
    {
        $this->padding = $padding;
        return $this;
    }

    function setBackground($r, $g, $b)
    {
        $this->bg = array($r, $g, $b);
        return $this;
    }

    function setForeground($r, $g, $b)
    {
        $this->fg = array($r, $g, $b);
        return $this;
    }

    function setFile($file)
    {
        $this->file = $file;
        return $this;
    }

    function render($type = 'png')
    {

        // ($fontSizeInPixel * 3) / 4 -> pxtopt


        $this->text = str_replace("\r\n", "\n", $this->text);

        $lines = explode("\n", $this->text);

        $max_nb_chars = 0;
        foreach ($lines as $string) {
            $max_nb_chars = max($max_nb_chars, strlen($string));
        }

        $char_width = imagefontwidth($this->font_size);
        $char_height = imagefontheight($this->font_size);
        $width = $char_width * $max_nb_chars + $this->padding * 2;
        $height = $char_height * count($lines) + $this->padding * 2;
        $img = imagecreatetruecolor($width, $height);

        $bg = imagecolorallocate($img, $this->bg[0], $this->bg[1], $this->bg[2]);
        imagefilledrectangle($img, 0, 0, $width, $height, $bg);

        $fg = imagecolorallocate($img, $this->fg[0], $this->fg[1], $this->fg[2]);
        foreach ($lines as $k => $string) {
            for ($i = 0, $l = strlen($string); $i < $l; $i++) {
                $xpos = $i * $char_width + $this->padding;
                $ypos = $k * $char_height + $this->padding;
                imagechar($img, $this->font_size, $xpos, $ypos, $string[$i], $fg);
            }
        }
        switch ($type) {
            case 'jpg':
            case 'jpeg':
                if ($this->file == null) {
                    //  header("Content-Type: image/jpeg");
                }
                imagejpeg($img, $this->file, 95);
                break;

            case 'gif':
                if ($this->file == null) {
                    // header("Content-Type: image/gif");
                }
                imagegif($img, $this->file);
                break;

            case 'png':
            default:
                if ($this->file == null) {
                    //   header("Content-Type: image/png");
                }
                imagepng($img, $this->file);
                break;
        }
        imagedestroy($img);
    }


}