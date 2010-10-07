<?php

/**
 * Project:     Securimage: A PHP class for creating and managing form CAPTCHA images<br />
 * File:        securimage.php<br />
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or any later version.<br /><br />
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.<br /><br />
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA<br /><br />
 *
 * Any modifications to the library should be indicated clearly in the source code
 * to inform users that the changes are not a part of the original software.<br /><br />
 *
 * If you found this script useful, please take a quick moment to rate it.<br />
 * http://www.hotscripts.com/rate/49400.html  Thanks.
 *
 * @link http://www.phpcaptcha.org Securimage PHP CAPTCHA
 * @link http://www.phpcaptcha.org/latest.zip Download Latest Version
 * @link http://www.phpcaptcha.org/Securimage_Docs/ Online Documentation
 * @copyright 2009 Drew Phillips
 * @author Drew Phillips <drew@drew-phillips.com>
 * @version 2.0 BETA (November 15, 2009)
 * @package Securimage
 *
 */

/**
 ChangeLog

 2.0.0
 - Add mathematical distortion to characters (using code from HKCaptcha)
 - Improved session support
 - Added Securimage_Color class for easier color definitions
 - Add distortion to audio output to prevent binary comparison attack (proposed by Sven "SavageTiger" Hagemann [insecurity.nl])
 - Flash button to stream mp3 audio (Douglas Walsh www.douglaswalsh.net)
 - Audio output is mp3 format by default
 - Change font to AlteHaasGrotesk by yann le coroller
 - Some code cleanup 

 1.0.4 (unreleased)
 - Ability to output audible codes in mp3 format to stream from flash

 1.0.3.1
 - Error reading from wordlist in some cases caused words to be cut off 1 letter short

 1.0.3
 - Removed shadow_text from code which could cause an undefined property error due to removal from previous version

 1.0.2
 - Audible CAPTCHA Code wav files
 - Create codes from a word list instead of random strings

 1.0
 - Added the ability to use a selected character set, rather than a-z0-9 only.
 - Added the multi-color text option to use different colors for each letter.
 - Switched to automatic session handling instead of using files for code storage
 - Added GD Font support if ttf support is not available.  Can use internal GD fonts or load new ones.
 - Added the ability to set line thickness
 - Added option for drawing arced lines over letters
 - Added ability to choose image type for output

 */

/**
 * Output images in JPEG format
 */
define ( 'SI_IMAGE_JPEG', 1 );
/**
 * Output images in PNG format
 */
define ( 'SI_IMAGE_PNG', 2 );
/**
 * Output images in GIF format
 * Must have GD >= 2.0.28!
 */
define ( 'SI_IMAGE_GIF', 3 );

define ( 'SI_TTF_PATH', dirname (__FILE__) . '/AHGBold.ttf' );



/**
 * Securimage CAPTCHA Class.
 *
 * @package    Securimage
 * @subpackage classes
 *
 */
class Securimage {

	/**
	 * The desired width of the CAPTCHA image.
	 *
	 * @var int
	 */
	var $image_width;

	/**
	 * The desired width of the CAPTCHA image.
	 *
	 * @var int
	 */
	var $image_height;

	/**
	 * The image format for output.<br />
	 * Valid options: SI_IMAGE_PNG, SI_IMAGE_JPG, SI_IMAGE_GIF
	 *
	 * @var int
	 */
	var $image_type;

	/**
	 * The length of the code to generate.
	 *
	 * @var int
	 */
	var $code_length;

	/**
	 * The character set for individual characters in the image.<br />
	 * Letters are converted to uppercase.<br />
	 * The font must support the letters or there may be problematic substitutions.
	 *
	 * @var string
	 */
	var $charset;

	/**
	 * Create codes using this word list
	 *
	 * @var string  The path to the word list to use for creating CAPTCHA codes
	 */
	var $wordlist_file;

	/**
	 * Use wordlist of not
	 *
	 * @var bool true to use wordlist file, false to use random code
	 */
	var $use_wordlist = false;

	/**
	 * Note: Use of GD fonts is not recommended as many distortion features are not available<br />
	 * The GD font to use.<br />
	 * Internal gd fonts can be loaded by their number.<br />
	 * Alternatively, a file path can be given and the font will be loaded from file.
	 *
	 * @var mixed
	 */
	var $gd_font_file = SI_TTF_PATH;

	/**
	 * The approximate size of the font in pixels.<br />
	 * This does not control the size of the font because that is determined by the GD font itself.<br />
	 * This is used to aid the calculations of positioning used by this class.<br />
	 *
	 * @var int
	 */
	var $gd_font_size;

	/**
	 * Use a gd font instead of TTF
	 *
	 * @var bool true for gd font, false for TTF
	 */
	var $use_gd_font = false;

	// Note: These font options below do not apply if you set $use_gd_font to true with the exception of $text_color

	/**
	 * The path to the TTF font file to load.
	 *
	 * @var string
	 */
	var $ttf_file = SI_TTF_PATH;

	/**
	 * How much to distort image, higher = more distortion.<br />
	 * Distortion is only available when using TTF fonts.<br />
	 *
	 * @var float
	 */
	var $perturbation;

	/**
	 * The minimum angle in degrees, with 0 degrees being left-to-right reading text.<br />
	 * Higher values represent a counter-clockwise rotation.<br />
	 * For example, a value of 90 would result in bottom-to-top reading text.<br />
	 * This value along with maximum angle distance do not need to be very high with perturbation
	 *
	 * @var int
	 */
	var $text_angle_minimum;

	/**
	 * The minimum angle in degrees, with 0 degrees being left-to-right reading text.<br />
	 * Higher values represent a counter-clockwise rotation.<br />
	 * For example, a value of 90 would result in bottom-to-top reading text.
	 *
	 * @var int
	 */
	var $text_angle_maximum;

	/**
	 * The X-Position on the image where letter drawing will begin.<br />
	 * This value is in pixels from the left side of the image.
	 *
	 * @var int
	 * @deprecated 2.0
	 */
	var $text_x_start;

	/**
	 * The background color for the image as a Securimage_Color.<br />
	 *
	 * @var Securimage_Color
	 */
	var $image_bg_color;

	/**
	 * Scan this directory for gif, jpg, and png files to use as background images.<br />
	 * A random image file will be picked each time.<br />
	 * Change from null to the full path to your directory.<br />
	 * i.e. var $background_directory = $_SERVER['DOCUMENT_ROOT'] . '/securimage/backgrounds';
	 * Make sure not to pass a background image to the show function, otherwise this directive is ignored.
	 *
	 * @var string
	 */
	var $background_directory = null; //'./backgrounds';

	/**
	 * The text color to use for drawing characters as a Securimage_Color.<br />
	 * This value is ignored if $use_multi_text is set to true.<br />
	 * Make sure this contrasts well with the background color or image.<br />
	 *
	 * @see Securimage::$use_multi_text
	 * @var Securimage_Color
	 */
	var $text_color;

	/**
	 * Set to true to use multiple colors for each character.
	 *
	 * @see Securimage::$multi_text_color
	 * @var boolean
	 */
	var $use_multi_text;

	/**
	 * Array of Securimage_Colors which will be randomly selected for each letter.<br />
	 *
	 * @var array
	 */
	var $multi_text_color;

	/**
	 * Set to true to make the characters appear transparent.
	 *
	 * @see Securimage::$text_transparency_percentage
	 * @var boolean
	 */
	var $use_transparent_text;

	/**
	 * The percentage of transparency, 0 to 100.<br />
	 * A value of 0 is completely opaque, 100 is completely transparent (invisble)
	 *
	 * @see Securimage::$use_transparent_text
	 * @var int
	 */
	var $text_transparency_percentage;


	// Line options
	/**
	* Draw vertical and horizontal lines on the image.
	*
	* @see Securimage::$line_color
	* @see Securimage::$draw_lines_over_text
	* @var boolean
	*/
	var $num_lines;

	/**
	 * Color of lines drawn over text
	 *
	 * @var string
	 */
	var $line_color;

	/**
	 * Draw the lines over the text.<br />
	 * If fales lines will be drawn before putting the text on the image.
	 *
	 * @var boolean
	 */
	var $draw_lines_over_text;

	/**
	 * Text to write at the bottom corner of captcha image
	 * 
	 * @since 2.0
	 * @var string Signature text
	 */
	var $image_signature;
	
	/**
	 * Color to use for writing signature text
	 * 
	 * @since 2.0
	 * @var Securimage_Color
	 */
	var $signature_color;

	/**
	 * Full path to the WAV files to use to make the audio files, include trailing /.<br />
	 * Name Files  [A-Z0-9].wav
	 *
	 * @since 1.0.1
	 * @var string
	 */
	var $audio_path;

	/**
	 * Type of audio file to generate (mp3 or wav)
	 *
	 * @var string
	 */
	var $audio_format;

	/**
	 * The session name to use if not the default.  Blank for none
	 *
	 * @see http://php.net/session_name
	 * @since 2.0
	 * @var string
	 */
	var $session_name = '';


	//END USER CONFIGURATION
	//There should be no need to edit below unless you really know what you are doing.

	/**
	 * The gd image resource.
	 *
	 * @access private
	 * @var resource
	 */
	var $im;

	/**
	 * Temporary image for rendering
	 *
	 * @access private
	 * @var resource
	 */
	var $tmpimg;

	/**
	 * Internal scale factor for anti-alias @hkcaptcha
	 *
	 * @access private
	 * @since 2.0
	 * @var int
	 */
	var $iscale; // internal scale factor for anti-alias @hkcaptcha

	/**
	 * The background image resource
	 *
	 * @access private
	 * @var resource
	 */
	var $bgimg;

	/**
	 * The code generated by the script
	 *
	 * @access private
	 * @var string
	 */
	var $code;

	/**
	 * The code that was entered by the user
	 *
	 * @access private
	 * @var string
	 */
	var $code_entered;

	/**
	 * Whether or not the correct code was entered
	 *
	 * @access private
	 * @var boolean
	 */
	var $correct_code;

	/**
	 * Class constructor.<br />
	 * Because the class uses sessions, this will attempt to start a session if there is no previous one.<br />
	 * If you do not start a session before calling the class, the constructor must be called before any
	 * output is sent to the browser.
	 *
	 * <code>
	 *   $securimage = new Securimage();
	 * </code>
	 *
	 */
	function Securimage()
	{
		// Initialize session or attach to existing
		if ( session_id() == '' ) { // no session has been started yet, which is needed for validation
			if (trim($this->session_name) != '') {
				session_name($this->session_name);
			}
			session_start();
		}

		// Set Default Values
		$this->image_width   = 230;
		$this->image_height  = 80;
		$this->image_type    = SI_IMAGE_PNG;

		$this->code_length   = 6;
		$this->charset       = 'ABCDEFGHKLMNPRSTUVWYZabcdefghklmnprstuvwyz23456789';
		$this->wordlist_file = './words/words.txt';
		$this->use_wordlist  = false;

		$this->gd_font_file  = '';
		$this->use_gd_font   = false;
		$this->gd_font_size  = 24;
		$this->text_x_start  = 15;

		$this->ttf_file      = './AHGBold.ttf';

		$this->perturbation       = 0.75;
		$this->iscale             = 5;
		$this->text_angle_minimum = 0;
		$this->text_angle_maximum = 0;

		$this->image_bg_color   = new Securimage_Color(0xff, 0xff, 0xff);
    $this->text_color       = new Securimage_Color(0x3d, 0x3d, 0x3d);
		$this->multi_text_color = array(new Securimage_Color(0x0, 0x20, 0xCC),
		new Securimage_Color(0x0, 0x30, 0xEE),
		new Securimage_color(0x0, 0x40, 0xCC),
		new Securimage_Color(0x0, 0x50, 0xEE),
		new Securimage_Color(0x0, 0x60, 0xCC));
		$this->use_multi_text   = false;

		$this->use_transparent_text         = false;
		$this->text_transparency_percentage = 30;

		$this->num_lines            = 10;
		$this->line_color           = new Securimage_Color(0x3d, 0x3d, 0x3d);
		$this->draw_lines_over_text = true;

		$this->image_signature = '';
		$this->signature_color = new Securimage_Color(0x20, 0x50, 0xCC);
		$this->signature_font  = './AHGBold.ttf';

		$this->audio_path   = './audio/';
		$this->audio_format = 'mp3';
		$this->session_name = '';
	}

	/**
	 * Generate a code and output the image to the browser.
	 *
	 * <code>
	 *   <?php
	 *   include 'securimage.php';
	 *   $securimage = new Securimage();
	 *   $securimage->show('bg.jpg');
	 *   ?>
	 * </code>
	 *
	 * @param string $background_image  The path to an image to use as the background for the CAPTCHA
	 */
	function show($background_image = "")
	{
		if($background_image != "" && is_readable($background_image)) {
			$this->bgimg = $background_image;
		}

		$this->doImage();
	}

	/**
	 * Validate the code entered by the user.
	 *
	 * <code>
	 *   $code = $_POST['code'];
	 *   if ($securimage->check($code) == false) {
	 *     die("Sorry, the code entered did not match.");
	 *   } else {
	 *     $valid = true;
	 *   }
	 * </code>
	 * @param string $code  The code the user entered
	 * @return boolean  true if the code was correct, false if not
	 */
	function check($code)
	{
		$this->code_entered = $code;
		$this->validate();
		return $this->correct_code;
	}

	/**
	 * Output audio file with HTTP headers to browser
	 * 
	 * <code>
	 *   $sound = new Securimage();
	 *   $sound->audio_format = 'mp3';
	 *   $sound->outputAudioFile();
	 * </code>
	 * 
	 * @since 2.0
	 */
	function outputAudioFile()
	{
		if (strtolower($this->audio_format) == 'wav') {
			header('Content-type: audio/x-wav');
			$ext = 'wav';
		} else {
			header('Content-type: audio/mpeg'); // default to mp3
			$ext = 'mp3';
		}

		header("Content-Disposition: attachment; name=\"securimage_audio.{$ext}\"");
		header('Cache-Control: no-store, no-cache, must-revalidate');
		header('Expires: Sun, 1 Jan 2000 12:00:00 GMT');
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . 'GMT');

		$audio = $this->getAudibleCode($ext);

		header('Content-Length: ' . strlen($audio));

		echo $audio;
		exit;
	}

	/**
	 * Generate and output the image
	 *
	 * @access private
	 *
	 */
	function doImage()
	{
		if ($this->use_gd_font == true) {
			$this->iscale = 1;
		}
		if($this->use_transparent_text == true || $this->bgimg != "") {
			$this->im = imagecreatetruecolor($this->image_width, $this->image_height);
			$bgcolor = imagecolorallocate($this->im, $this->image_bg_color->r, $this->image_bg_color->g, $this->image_bg_color->b);
			imagefilledrectangle($this->im, 0, 0, $this->image_width * $this->iscale, $this->image_height * $this->iscale, $bgcolor);


			$this->tmpimg = imagecreatetruecolor($this->image_width * $this->iscale, $this->image_height * $this->iscale);
			imagepalettecopy($this->tmpimg, $this->im);
			imagefilledrectangle($this->tmpimg, 0, 0, $this->image_width * $this->iscale, $this->image_height * $this->iscale, $bgcolor);

		} else { //no transparency
			$this->im = imagecreate($this->image_width, $this->image_height);
			$bgcolor = imagecolorallocate($this->im, $this->image_bg_color->r, $this->image_bg_color->g, $this->image_bg_color->b);

			$this->tmpimg = imagecreate($this->image_width * $this->iscale, $this->image_height * $this->iscale);
			imagepalettecopy($this->tmpimg, $this->im);
		}

		$this->setBackground();

		$this->createCode();

		if (!$this->draw_lines_over_text && $this->num_lines > 0) $this->drawLines();

		$this->drawWord();
		if ($this->use_gd_font == false) $this->distortedCopy();

		if ($this->draw_lines_over_text && $this->num_lines > 0) $this->drawLines();

		if (trim($this->image_signature) != '')	$this->addSignature();

		$this->output();

	}

	/**
	 * Set the background of the CAPTCHA image
	 *
	 * @access private
	 *
	 */
	function setBackground()
	{
		if ($this->bgimg == '') {
			if ($this->background_directory != null && is_dir($this->background_directory) && is_readable($this->background_directory)) {
				$img = $this->getBackgroundFromDirectory();
				if ($img != false) {
					$this->bgimg = $img;
				}
			}
		}

		$dat = @getimagesize($this->bgimg);
		if($dat == false) { return; }

		switch($dat[2]) {
			case 1:  $newim = @imagecreatefromgif($this->bgimg); break;
			case 2:  $newim = @imagecreatefromjpeg($this->bgimg); break;
			case 3:  $newim = @imagecreatefrompng($this->bgimg); break;
			case 15: $newim = @imagecreatefromwbmp($this->bgimg); break;
			case 16: $newim = @imagecreatefromxbm($this->bgimg); break;
			default: return;
		}

		if(!$newim) return;

		imagecopyresized($this->im, $newim, 0, 0, 0, 0, $this->image_width, $this->image_height, imagesx($newim), imagesy($newim));
	}

	/**
	 * Return the full path to a random gif, jpg, or png from the background directory.
	 *
	 * @see Securimage::$background_directory
	 * @return mixed  false if none found, string $path if found
	 */
	function getBackgroundFromDirectory()
	{
		$images = array();

		if ($dh = opendir($this->background_directory)) {
			while (($file = readdir($dh)) !== false) {
				if (preg_match('/(jpg|gif|png)$/i', $file)) $images[] = $file;
			}

			closedir($dh);

			if (sizeof($images) > 0) {
				return rtrim($this->background_directory, '/') . '/' . $images[rand(0, sizeof($images)-1)];
			}
		}

		return false;
	}

	/**
	 * Draw random curvy lines over the image<br />
	 * Modified code from HKCaptcha
	 *
	 * @since 2.0
	 * @access private
	 *
	 */
	function drawLines()
	{
		$linecolor = imagecolorallocate($this->im, $this->line_color->r, $this->line_color->g, $this->line_color->b);

		for ($line = 0; $line < $this->num_lines; ++$line) {
			$x = $this->image_width * (1 + $line) / ($this->num_lines + 1);
			$x += (0.5 - $this->frand()) * $this->image_width / $this->num_lines;
			$y = rand($this->image_height * 0.1, $this->image_height * 0.9);
			 
			$theta = ($this->frand()-0.5) * M_PI * 0.7;
			$w = $this->image_width;
			$len = rand($w * 0.4, $w * 0.7);
			$lwid = rand(0, 2);
			 
			$k = $this->frand() * 0.6 + 0.2;
			$k = $k * $k * 0.5;
			$phi = $this->frand() * 6.28;
			$step = 0.5;
			$dx = $step * cos($theta);
			$dy = $step * sin($theta);
			$n = $len / $step;
			$amp = 1.5 * $this->frand() / ($k + 5.0 / $len);
			$x0 = $x - 0.5 * $len * cos($theta);
			$y0 = $y - 0.5 * $len * sin($theta);
			 
			$ldx = round(-$dy * $lwid);
			$ldy = round($dx * $lwid);
			 
			for ($i = 0; $i < $n; ++$i) {
				$x = $x0 + $i * $dx + $amp * $dy * sin($k * $i * $step + $phi);
				$y = $y0 + $i * $dy - $amp * $dx * sin($k * $i * $step + $phi);
				imagefilledrectangle($this->im, $x, $y, $x + $lwid, $y + $lwid, $linecolor);
			}
		}
	}

	/**
	 * Draw the CAPTCHA code over the image
	 *
	 * @access private
	 *
	 */
	function drawWord()
	{
		$width2 = $this->image_width * $this->iscale;
		$height2 = $this->image_height * $this->iscale;
		 
		if ($this->use_gd_font == true) {
			if (!is_int($this->gd_font_file)) { //is a file name
				$font = @imageloadfont($this->gd_font_file);
				if ($font == false) {
					trigger_error("Failed to load GD Font file {$this->gd_font_file} ", E_USER_WARNING);
					return;
				}
			} else { //gd font identifier
				$font = $this->gd_font_file;
			}

			$color = imagecolorallocate($this->im, hexdec(substr($this->text_color, 1, 2)), hexdec(substr($this->text_color, 3, 2)), hexdec(substr($this->text_color, 5, 2)));
			imagestring($this->im, $font, $this->text_x_start, ($this->image_height / 2) - ($this->gd_font_size / 2), $this->code, $color);
		} else { //ttf font
			$font_size = $height2 * .35;
			$bb = imagettfbbox($font_size, 0, SI_TTF_PATH, $this->code);
			$tx = $bb[4] - $bb[0];
			$ty = $bb[5] - $bb[1];
			$x  = floor($width2 / 2 - $tx / 2 - $bb[0]);
			$y  = round($height2 / 2 - $ty / 2 - $bb[1]);

			if($this->use_transparent_text == true) {
				$alpha = intval($this->text_transparency_percentage / 100 * 127);
				$font_color = imagecolorallocatealpha($this->tmpimg, $this->text_color->r, $this->text_color->g, $this->text_color->b, $alpha);
			} else { //no transparency
				$font_color = imagecolorallocate($this->tmpimg, $this->text_color->r, $this->text_color->g, $this->text_color->b);
			}

			$strlen = strlen($this->code);
			if (!is_array($this->multi_text_color)) $this->use_multi_text = false;


			if ($this->use_multi_text == false && $this->text_angle_minimum == 0 && $this->text_angle_maximum == 0) { // no angled or multi-color characters
				imagettftext($this->tmpimg, $font_size, 0, $x, $y, $font_color, SI_TTF_PATH, $this->code);
			} else {
				for($i = 0; $i < $strlen; ++$i) {
					$angle = rand($this->text_angle_minimum, $this->text_angle_maximum);
					$y = rand($y - 5, $y + 5);
					if ($this->use_multi_text == true) {
						$idx = rand(0, sizeof($this->multi_text_color) - 1);
						if (!is_object($this->multi_text_color[$idx])) $this->multi_text_color[$idx] = $this->text_color;
						 
						if($this->use_transparent_text == true) {
							$font_color = imagecolorallocatealpha($this->tmpimg, $this->multi_text_color[$idx]->r, $this->multi_text_color[$idx]->g, $this->multi_text_color[$idx]->b, $alpha);
						} else {
							$font_color = imagecolorallocate($this->tmpimg, $this->multi_text_color[$idx]->r, $this->multi_text_color[$idx]->g, $this->multi_text_color[$idx]->b);
						}
					}
					 
					$ch = $this->code{$i};
					 
					imagettftext($this->tmpimg, $font_size, $angle, $x, $y, $font_color, $this->ttf_file, $ch);
					 
					// estimate character widths to increment $x without creating spaces that are too large or too small
					// these are best estimates to align text but may vary between fonts
					// for optimal character widths, do not use multiple text colors or character angles and the complete string will be written by imagettftext
					if (strpos('abcdeghknopqsuvxyz', $ch) !== false) {
						$min_x = $font_size - ($this->iscale * 6);
						$max_x = $font_size - ($this->iscale * 6);
					} else if (strpos('ilI1', $ch) !== false) {
						$min_x = $font_size / 5;
						$max_x = $font_size / 3;
					} else if (strpos('fjrt', $ch) !== false) {
						$min_x = $font_size - ($this->iscale * 12);
						$max_x = $font_size - ($this->iscale * 12);
					} else if ($ch == 'wm') {
						$min_x = $font_size;
						$max_x = $font_size + ($this->iscale * 3);
					} else { // numbers, capitals or unicode
						$min_x = $font_size + ($this->iscale * 2);
						$max_x = $font_size + ($this->iscale * 5);
					}
					 
					$x += rand($min_x, $max_x);
				} //for loop
			} // angled or multi-color
		} //else ttf font
		//$this->im = $this->tmpimg;
		//$this->output();
	} //function

	/**
	 * Warp text from temporary image onto final image.<br />
	 * Modified for securimage
	 *
	 * @access private
	 * @since 2.0
	 * @author Han-Kwang Nienhuys modified
	 * @copyright Han-Kwang Neinhuys
	 *
	 */
	function distortedCopy()
	{
		$numpoles = 3; // distortion factor
		 
		// make array of poles AKA attractor points
		for ($i = 0; $i < $numpoles; ++$i) {
			$px[$i]  = rand($this->image_width * 0.3, $this->image_width * 0.7);
			$py[$i]  = rand($this->image_height * 0.3, $this->image_height * 0.7);
			$rad[$i] = rand($this->image_width * 0.4, $this->image_width * 0.7);
			$tmp     = -$this->frand() * 0.15 - 0.15;
			$amp[$i] = $this->perturbation * $tmp;
		}
		 
		$bgCol   = imagecolorat($this->tmpimg, 0, 0);
		$width2  = $this->iscale * $this->image_width;
		$height2 = $this->iscale * $this->image_height;
		 
		imagepalettecopy($this->im, $this->tmpimg); // copy palette to final image so text colors come across
		 
		// loop over $img pixels, take pixels from $tmpimg with distortion field
		for ($ix = 0; $ix < $this->image_width; ++$ix) {
			for ($iy = 0; $iy < $this->image_height; ++$iy) {
				$x = $ix;
				$y = $iy;
					
				for ($i = 0; $i < $numpoles; ++$i) {
					$dx = $ix - $px[$i];
					$dy = $iy - $py[$i];
					if ($dx == 0 && $dy == 0) continue;

					$r = sqrt($dx * $dx + $dy * $dy);
					if ($r > $rad[$i]) continue;

					$rscale = $amp[$i] * sin(3.14 * $r / $rad[$i]);
					$x += $dx * $rscale;
					$y += $dy * $rscale;
				}
					
				$c = $bgCol;
				$x *= $this->iscale;
				$y *= $this->iscale;

				if ($x >= 0 && $x < $width2 && $y >= 0 && $y < $height2) {
					$c = imagecolorat($this->tmpimg, $x, $y);
				}

				if ($c != $bgCol) { // only copy pixels of letters to preserve any background image
					imagesetpixel($this->im, $ix, $iy, $c);
				}
			}
		}
	}

	/**
	 * Create a code and save to the session
	 *
	 * @since 1.0.1
	 *
	 */
	function createCode()
	{
		$this->code = false;

		if ($this->use_wordlist && is_readable($this->wordlist_file)) {
			$this->code = $this->readCodeFromFile();
		}

		if ($this->code == false) {
			$this->code = $this->generateCode($this->code_length);
		}

		$this->saveData();
	}

	/**
	 * Generate a code
	 *
	 * @access private
	 * @param int $len  The code length
	 * @return string
	 */
	function generateCode($len)
	{
		$code = '';

		for($i = 1, $cslen = strlen($this->charset); $i <= $len; ++$i) {
			$code .= $this->charset{rand(0, $cslen - 1)};
		}
		return $code;
	}

	/**
	 * Reads a word list file to get a code
	 *
	 * @access private
	 * @since 1.0.2
	 * @return mixed  false on failure, a word on success
	 */
	function readCodeFromFile()
	{
		$fp = @fopen($this->wordlist_file, 'rb');
		if (!$fp) return false;

		$fsize = filesize($this->wordlist_file);
		if ($fsize < 32) return false; // too small of a list to be effective

		if ($fsize < 128) {
			$max = $fsize; // still pretty small but changes the range of seeking
		} else {
			$max = 128;
		}

		fseek($fp, rand(0, $fsize - $max), SEEK_SET);
		$data = fread($fp, 128); // read a random 128 bytes from file
		fclose($fp);
		$data = preg_replace("/\r?\n/", "\n", $data);

		$start = strpos($data, "\n", rand(0, 100)) + 1; // random start position
		$end   = strpos($data, "\n", $start);           // find end of word

		return strtolower(substr($data, $start, $end - $start)); // return substring in 128 bytes
	}

	/**
	 * Output image to the browser
	 *
	 * @access private
	 *
	 */
	function output()
	{
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");

		switch($this->image_type)
		{
			case SI_IMAGE_JPEG:
				header("Content-Type: image/jpeg");
				imagejpeg($this->im, null, 90);
				break;

			case SI_IMAGE_GIF:
				header("Content-Type: image/gif");
				imagegif($this->im);
				break;

			default:
				header("Content-Type: image/png");
				imagepng($this->im);
				break;
		}

		imagedestroy($this->im);
		exit;
	}

	/**
	 * Get WAV or MP3 file data of the spoken code.<br />
	 * This is appropriate for output to the browser as audio/x-wav or audio/mpeg
	 *
	 * @since 1.0.1
	 * @return string  WAV or MP3 data
	 *
	 */
	function getAudibleCode($format = 'wav')
	{
		$letters = array();
		$code    = $this->getCode();

		if ($code == '') {
			$this->createCode();
			$code = $this->getCode();
		}

		for($i = 0; $i < strlen($code); ++$i) {
			$letters[] = $code{$i};
		}

		if ($format == 'mp3') {
			return $this->generateMP3($letters);
		} else {
			return $this->generateWAV($letters);
		}
	}

	/**
	 * Set the path to the audio directory.<br />
	 *
	 * @since 1.0.4
	 * @return bool true if the directory exists and is readble, false if not
	 */
	function setAudioPath($audio_directory)
	{
		if (is_dir($audio_directory) && is_readable($audio_directory)) {
			$this->audio_path = $audio_directory;
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Save the code in the session
	 *
	 * @access private
	 *
	 */
	function saveData()
	{
		$_SESSION['securimage_code_value'] = strtolower($this->code);
	}

	/**
	 * Validate the code to the user code
	 *
	 * @access private
	 *
	 */
	function validate()
	{
		if ( isset($_SESSION['securimage_code_value']) && !empty($_SESSION['securimage_code_value']) ) {
			if ( strtolower($_SESSION['securimage_code_value']) == strtolower(trim($this->code_entered)) ) {
				$this->correct_code = true;
				$_SESSION['securimage_code_value'] = '';  // clear code to prevent session re-use
			} else {
				$this->correct_code = false;
			}
		} else {
			$this->correct_code = false; // value was never set or is blank
		}
	}

	/**
	 * Get the captcha code
	 *
	 * @since 1.0.1
	 * @return string
	 */
	function getCode()
	{
		if (isset($_SESSION['securimage_code_value']) && !empty($_SESSION['securimage_code_value'])) {
			return strtolower($_SESSION['securimage_code_value']);
		} else {
			return '';
		}
	}

	/**
	 * Check if the user entered code was correct
	 *
	 * @access private
	 * @return boolean
	 */
	function checkCode()
	{
		return $this->correct_code;
	}

	/**
	 * Generate a wav file by concatenating individual files
	 *
	 * @since 1.0.1
	 * @access private
	 * @param array $letters  Array of letters to build a file from
	 * @return string  WAV file data
	 */
	function generateWAV($letters)
	{
		$data_len    = 0;
		$files       = array();
		$out_data    = '';

		foreach ($letters as $letter) {
			$filename = $this->audio_path . strtoupper($letter) . '.wav';

			$fp = fopen($filename, 'rb');

			$file = array();

			$data = fread($fp, filesize($filename)); // read file in

			$header = substr($data, 0, 36);
			$body   = substr($data, 44);


			$data = unpack('NChunkID/VChunkSize/NFormat/NSubChunk1ID/VSubChunk1Size/vAudioFormat/vNumChannels/VSampleRate/VByteRate/vBlockAlign/vBitsPerSample', $header);

			$file['sub_chunk1_id']   = $data['SubChunk1ID'];
			$file['bits_per_sample'] = $data['BitsPerSample'];
			$file['channels']        = $data['NumChannels'];
			$file['format']          = $data['AudioFormat'];
			$file['sample_rate']     = $data['SampleRate'];
			$file['size']            = $data['ChunkSize'] + 8;
			$file['data']            = $body;

			if ( ($p = strpos($file['data'], 'LIST')) !== false) {
				// If the LIST data is not at the end of the file, this will probably break your sound file
				$info         = substr($file['data'], $p + 4, 8);
				$data         = unpack('Vlength/Vjunk', $info);
				$file['data'] = substr($file['data'], 0, $p);
				$file['size'] = $file['size'] - (strlen($file['data']) - $p);
			}

			$files[] = $file;
			$data    = null;
			$header  = null;
			$body    = null;

			$data_len += strlen($file['data']);

			fclose($fp);
		}

		$out_data = '';
		for($i = 0; $i < sizeof($files); ++$i) {
			if ($i == 0) { // output header
				$out_data .= pack('C4VC8', ord('R'), ord('I'), ord('F'), ord('F'), $data_len + 36, ord('W'), ord('A'), ord('V'), ord('E'), ord('f'), ord('m'), ord('t'), ord(' '));

				$out_data .= pack('VvvVVvv',
				16,
				$files[$i]['format'],
				$files[$i]['channels'],
				$files[$i]['sample_rate'],
				$files[$i]['sample_rate'] * (($files[$i]['bits_per_sample'] * $files[$i]['channels']) / 8),
				($files[$i]['bits_per_sample'] * $files[$i]['channels']) / 8,
				$files[$i]['bits_per_sample'] );

				$out_data .= pack('C4', ord('d'), ord('a'), ord('t'), ord('a'));

				$out_data .= pack('V', $data_len);
			}

			$out_data .= $files[$i]['data'];
		}

		$this->scrambleAudioData($out_data, 'wav');
		return $out_data;
	}

	/**
	 * Randomly modify the audio data to scramble sound and prevent binary recognition.<br />
	 * Take care not to "break" the audio file by leaving the header data intact.
	 *
	 * @since 2.0
	 * @param $data Sound data in mp3 of wav format
	 */
	function scrambleAudioData(&$data, $format)
	{
		if ($format == 'wav') {
			$start = strpos($data, 'data') + 4; // look for "data" indicator
			if ($start === false) $start = 44;  // if not found assume 44 byte header
		} else { // mp3
			$start = 4; // 4 byte (32 bit) frame header
		}
		 
		$start  += rand(1, 64); // randomize starting offset
		$datalen = strlen($data) - $start - 256; // leave last 256 bytes unchanged
		 
		for ($i = $start; $i < $datalen; $i += 64) {
			$ch = ord($data{$i});
			if ($ch < 9 || $ch > 119) continue;

			$data{$i} = chr($ch + rand(-8, 8));
		}
	}

	/**
	 * Generate an mp3 file by concatenating individual files
	 * @since 1.0.4
	 * @access private
	 * @param array $letters  Array of letters to build a file from
	 * @return string  MP3 file data
	 */
	function generateMP3($letters)
	{
		$data_len    = 0;
		$files       = array();
		$out_data    = '';

		foreach ($letters as $letter) {
			$filename = $this->audio_path . strtoupper($letter) . '.mp3';

			$fp   = fopen($filename, 'rb');
			$data = fread($fp, filesize($filename)); // read file in

			$this->scrambleAudioData($data, 'mp3');
			$out_data .= $data;

			fclose($fp);
		}


		return $out_data;
	}

	/**
	 * Generate random number less than 1
	 * @since 2.0
	 * @access private
	 * @return float
	 */
	function frand()
	{
		return 0.0001*rand(0,9999);
	}

	/**
	 * Print signature text on image
	 *
	 * @since 2.0
	 * @access private
	 *
	 */
	function addSignature()
	{
		$cmtcol = imagecolorallocate($this->im, $this->signature_color->r, $this->signature_color->g, $this->signature_color->b);

		if ($this->use_gd_font) {
			imagestring($this->im, 5, $this->image_width - (strlen($this->image_signature) * 10), $this->image_height - 20, $this->image_signature, $cmtcol);
		} else {
			 
			$bbox = imagettfbbox(10, 0, $this->signature_font, $this->image_signature);
			$textlen = $bbox[2] - $bbox[0];
			$x = $this->image_width - $textlen - 5;
			$y = $this->image_height - 3;
			 
			imagettftext($this->im, 10, 0, $x, $y, $cmtcol, $this->signature_font, $this->image_signature);
		}
	}
} /* class Securimage */


/**
 * Color object for Securimage CAPTCHA
 *
 * @since 2.0
 * @package Securimage
 * @subpackage classes
 *
 */
class Securimage_Color {
	/**
	 * Red component: 0-255
	 *
	 * @var int
	 */
	var $r;
	/**
	 * Green component: 0-255
	 *
	 * @var int
	 */
	var $g;
	/**
	 * Blue component: 0-255
	 *
	 * @var int
	 */
	var $b;

	/**
	 * Create a new Securimage_Color object.<br />
	 * Specify the red, green, and blue components using their HTML hex code equivalent.<br />
	 * i.e. #4A203C is declared as new Securimage_Color(0x4A, 0x20, 0x3C)
	 *
	 * @param $red Red component 0-255
	 * @param $green Green component 0-255
	 * @param $blue Blue component 0-255
	 */
	function Securimage_Color($red, $green, $blue)
	{
		if ($red < 0) $red       = 0;
		if ($red > 255) $red     = 255;
		if ($green < 0) $green   = 0;
		if ($green > 255) $green = 255;
		if ($blue < 0) $blue     = 0;
		if ($blue > 255) $blue   = 255;

		$this->r = $red;
		$this->g = $green;
		$this->b = $blue;
	}
}
