<?php
// Smart Image Resizer 1.4.1
// Resizes images, intelligently sharpens, crops based on width:height ratios, color fills
// transparent GIFs and PNGs, and caches variations for optimal performance

// Created by: Joe Lencioni (http://shiftingpixel.com)
// Date: August 6, 2008
// Based on: http://veryraw.com/history/2005/03/image-resizing-with-php/

/////////////////////
// LICENSE
/////////////////////

// I love to hear when my work is being used, so if you decide to use this, feel encouraged
// to send me an email. Smart Image Resizer is released under a Creative Commons
// Attribution-Share Alike 3.0 United States license
// (http://creativecommons.org/licenses/by-sa/3.0/us/). All I ask is that you include a link
// back to Shifting Pixel (either this page or shiftingpixel.com), but don’t worry about
// including a big link on each page if you don’t want to–one will do just nicely. Feel
// free to contact me to discuss any specifics (joe@shiftingpixel.com).

/////////////////////
// REQUIREMENTS
/////////////////////

// PHP and GD

/////////////////////
// PARAMETERS
/////////////////////

// Parameters need to be passed in through the URL's query string:
// image		absolute path of local image starting with "/" (e.g. /images/toast.jpg)
// width		maximum width of final image in pixels (e.g. 700)
// height		maximum height of final image in pixels (e.g. 700)
// color		(optional) background hex color for filling transparent PNGs (e.g. 900 or 16a942)
// cropratio	(optional) ratio of width to height to crop final image (e.g. 1:1 or 3:2)
// nocache		(optional) does not read image from the cache
// quality		(optional, 0-100, default: 90) quality of output image

/////////////////////
// EXAMPLES
/////////////////////

// Resizing a JPEG:
// <img src="/image.php/image-name.jpg?width=100&amp;height=100&amp;image=/path/to/image.jpg" alt="Don't forget your alt text" />

// Resizing and cropping a JPEG into a square:
// <img src="/image.php/image-name.jpg?width=100&amp;height=100&amp;cropratio=1:1&amp;image=/path/to/image.jpg" alt="Don't forget your alt text" />

// Matting a PNG with #990000:
// <img src="/image.php/image-name.png?color=900&amp;image=/path/to/image.png" alt="Don't forget your alt text" />

/////////////////////
// CODE STARTS HERE
/////////////////////

if (!isset($_GET['image']))
{
	header('HTTP/1.1 400 Bad Request');
	echo 'Error: no image was specified';
	exit();
}

define('MEMORY_TO_ALLOCATE',	'100M');
define('DEFAULT_QUALITY',		90);
define('CURRENT_DIR',			dirname(__FILE__));
define('CACHE_DIR_NAME',		'/imagecache/');
define('CACHE_DIR',				CURRENT_DIR . CACHE_DIR_NAME);
define('DOCUMENT_ROOT',			$_SERVER['DOCUMENT_ROOT']);

// Images must be local files, so for convenience we strip the domain if it's there
$image			= preg_replace('/^(s?f|ht)tps?:\/\/[^\/]+/i', '', (string) $_GET['image']);

// For security, directories cannot contain ':', images cannot contain '..' or '<', and
// images must start with '/'
if ($image{0} != '/' || strpos(dirname($image), ':') || preg_match('/(\.\.|<|>)/', $image))
{
	header('HTTP/1.1 400 Bad Request');
	echo 'Error: malformed image path. Image paths must begin with \'/\'';
	exit();
}

// If the image doesn't exist, or we haven't been told what it is, there's nothing
// that we can do
if (!$image)
{
	header('HTTP/1.1 400 Bad Request');
	echo 'Error: no image was specified';
	exit();
}

// Strip the possible trailing slash off the document root
$docRoot	= preg_replace('/\/$/', '', DOCUMENT_ROOT);

if (!file_exists($docRoot . $image))
{
	header('HTTP/1.1 404 Not Found');
	echo 'Error: image does not exist: ' . $docRoot . $image;
	exit();
}

// Get the size and MIME type of the requested image
$size	= GetImageSize($docRoot . $image);
$mime	= $size['mime'];

// Make sure that the requested file is actually an image
if (substr($mime, 0, 6) != 'image/')
{
	header('HTTP/1.1 400 Bad Request');
	echo 'Error: requested file is not an accepted type: ' . $docRoot . $image;

	exit();
}

$width			= $size[0];
$height			= $size[1];

$maxWidth		= (isset($_GET['width'])) ? (int) $_GET['width'] : 0;
$maxHeight		= (isset($_GET['height'])) ? (int) $_GET['height'] : 0;

if (isset($_GET['color']))
	$color		= preg_replace('/[^0-9a-fA-F]/', '', (string) $_GET['color']);
else
	$color		= FALSE;

// If either a max width or max height are not specified, we default to something
// large so the unspecified dimension isn't a constraint on our resized image.
// If neither are specified but the color is, we aren't going to be resizing at
// all, just coloring.
if (!$maxWidth && $maxHeight)
{
	$maxWidth	= 99999999999999;
}
elseif ($maxWidth && !$maxHeight)
{
	$maxHeight	= 99999999999999;
}
elseif ($color && !$maxWidth && !$maxHeight)
{
	$maxWidth	= $width;
	$maxHeight	= $height;
}

// If we don't have a max width or max height, OR the image is smaller than both
// we do not want to resize it, so we simply output the original image and exit
if ((!$maxWidth && !$maxHeight) || (!$color && $maxWidth >= $width && $maxHeight >= $height))
{
	$data	= file_get_contents($docRoot . '/' . $image);
	
	$lastModifiedString	= gmdate('D, d M Y H:i:s', filemtime($docRoot . '/' . $image)) . ' GMT';
	$etag				= md5($data);
	
	doConditionalGet($etag, $lastModifiedString);
	
	header("Content-type: $mime");
	header('Content-Length: ' . strlen($data));
	echo $data;
	exit();
}

// Ratio cropping
$offsetX	= 0;
$offsetY	= 0;

if (isset($_GET['cropratio']))
{
	$cropRatio		= explode(':', (string) $_GET['cropratio']);
	if (count($cropRatio) == 2)
	{
		$ratioComputed		= $width / $height;
		$cropRatioComputed	= (float) $cropRatio[0] / (float) $cropRatio[1];
		
		if ($ratioComputed < $cropRatioComputed)
		{ // Image is too tall so we will crop the top and bottom
			$origHeight	= $height;
			$height		= $width / $cropRatioComputed;
			$offsetY	= ($origHeight - $height) / 2;
		}
		else if ($ratioComputed > $cropRatioComputed)
		{ // Image is too wide so we will crop off the left and right sides
			$origWidth	= $width;
			$width		= $height * $cropRatioComputed;
			$offsetX	= ($origWidth - $width) / 2;
		}
	}
}

// Setting up the ratios needed for resizing. We will compare these below to determine how to
// resize the image (based on height or based on width)
$xRatio		= $maxWidth / $width;
$yRatio		= $maxHeight / $height;

if ($xRatio * $height < $maxHeight)
{ // Resize the image based on width
	$tnHeight	= ceil($xRatio * $height);
	$tnWidth	= $maxWidth;
}
else // Resize the image based on height
{
	$tnWidth	= ceil($yRatio * $width);
 	$tnHeight	= $maxHeight;
}

// Determine the quality of the output image
$quality	= (isset($_GET['quality'])) ? (int) $_GET['quality'] : DEFAULT_QUALITY;

// Before we actually do any crazy resizing of the image, we want to make sure that we
// haven't already done this one at these dimensions. To the cache!
// Note, cache must be world-readable

// We store our cached image filenames as a hash of the dimensions and the original filename
$resizedImageSource		= $tnWidth . 'x' . $tnHeight . 'x' . $quality;
if ($color)
	$resizedImageSource	.= 'x' . $color;
if (isset($_GET['cropratio']))
	$resizedImageSource	.= 'x' . (string) $_GET['cropratio'];
$resizedImageSource		.= '-' . $image;

$resizedImage	= md5($resizedImageSource);
	
$resized		= CACHE_DIR . $resizedImage;

// Check the modified times of the cached file and the original file.
// If the original file is older than the cached file, then we simply serve up the cached file
if (!isset($_GET['nocache']) && file_exists($resized))
{
	$imageModified	= filemtime($docRoot . $image);
	$thumbModified	= filemtime($resized);
	
	if($imageModified < $thumbModified) {
		$data	= file_get_contents($resized);
	
		$lastModifiedString	= gmdate('D, d M Y H:i:s', $thumbModified) . ' GMT';
		$etag				= md5($data);
		
		doConditionalGet($etag, $lastModifiedString);
		
		header("Content-type: $mime");
		header('Content-Length: ' . strlen($data));
		echo $data;
		exit();
	}
}

// We don't want to run out of memory
ini_set('memory_limit', MEMORY_TO_ALLOCATE);

// Set up a blank canvas for our resized image (destination)
$dst	= imagecreatetruecolor($tnWidth, $tnHeight);

// Set up the appropriate image handling functions based on the original image's mime type
switch ($size['mime'])
{
	case 'image/gif':
		// We will be converting GIFs to PNGs to avoid transparency issues when resizing GIFs
		// This is maybe not the ideal solution, but IE6 can suck it
		$creationFunction	= 'ImageCreateFromGif';
		$outputFunction		= 'ImagePng';
		$mime				= 'image/png'; // We need to convert GIFs to PNGs
		$doSharpen			= FALSE;
		$quality			= round(10 - ($quality / 10)); // We are converting the GIF to a PNG and PNG needs a compression level of 0 (no compression) through 9
	break;
	
	case 'image/x-png':
	case 'image/png':
		$creationFunction	= 'ImageCreateFromPng';
		$outputFunction		= 'ImagePng';
		$doSharpen			= FALSE;
		$quality			= round(10 - ($quality / 10)); // PNG needs a compression level of 0 (no compression) through 9
	break;
	
	default:
		$creationFunction	= 'ImageCreateFromJpeg';
		$outputFunction	 	= 'ImageJpeg';
		$doSharpen			= TRUE;
	break;
}

// Read in the original image
$src	= $creationFunction($docRoot . $image);

if (in_array($size['mime'], array('image/gif', 'image/png')))
{
	if (!$color)
	{
		// If this is a GIF or a PNG, we need to set up transparency
		imagealphablending($dst, false);
		imagesavealpha($dst, true);
	}
	else
	{
		// Fill the background with the specified color for matting purposes
		if ($color[0] == '#')
			$color = substr($color, 1);
		
		$background	= FALSE;
		
		if (strlen($color) == 6)
			$background	= imagecolorallocate($dst, hexdec($color[0].$color[1]), hexdec($color[2].$color[3]), hexdec($color[4].$color[5]));
		else if (strlen($color) == 3)
			$background	= imagecolorallocate($dst, hexdec($color[0].$color[0]), hexdec($color[1].$color[1]), hexdec($color[2].$color[2]));
		if ($background)
			imagefill($dst, 0, 0, $background);
	}
}

// Resample the original image into the resized canvas we set up earlier
ImageCopyResampled($dst, $src, 0, 0, $offsetX, $offsetY, $tnWidth, $tnHeight, $width, $height);

if ($doSharpen)
{
	// Sharpen the image based on two things:
	//	(1) the difference between the original size and the final size
	//	(2) the final size
	$sharpness	= findSharp($width, $tnWidth);
	
	$sharpenMatrix	= array(
		array(-1, -2, -1),
		array(-2, $sharpness + 12, -2),
		array(-1, -2, -1)
	);
	$divisor		= $sharpness;
	$offset			= 0;
	imageconvolution($dst, $sharpenMatrix, $divisor, $offset);
}

// Make sure the cache exists. If it doesn't, then create it
if (!file_exists(CACHE_DIR))
	mkdir(CACHE_DIR, 0755);

// Make sure we can read and write the cache directory
if (!is_readable(CACHE_DIR))
{
	header('HTTP/1.1 500 Internal Server Error');
	echo 'Error: the cache directory is not readable';
	exit();
}
else if (!is_writable(CACHE_DIR))
{
	header('HTTP/1.1 500 Internal Server Error');
	echo 'Error: the cache directory is not writable';
	exit();
}

// Write the resized image to the cache
$outputFunction($dst, $resized, $quality);

// Put the data of the resized image into a variable
ob_start();
$outputFunction($dst, null, $quality);
$data	= ob_get_contents();
ob_end_clean();

// Clean up the memory
ImageDestroy($src);
ImageDestroy($dst);

// See if the browser already has the image
$lastModifiedString	= gmdate('D, d M Y H:i:s', filemtime($resized)) . ' GMT';
$etag				= md5($data);

doConditionalGet($etag, $lastModifiedString);

// Send the image to the browser with some delicious headers
header("Content-type: $mime");
header('Content-Length: ' . strlen($data));
echo $data;

function findSharp($orig, $final) // function from Ryan Rud (http://adryrun.com)
{
	$final	= $final * (750.0 / $orig);
	$a		= 52;
	$b		= -0.27810650887573124;
	$c		= .00047337278106508946;
	
	$result = $a + $b * $final + $c * $final * $final;
	
	return max(round($result), 0);
} // findSharp()

function doConditionalGet($etag, $lastModified)
{
	header("Last-Modified: $lastModified");
	header("ETag: \"{$etag}\"");
		
	$if_none_match = isset($_SERVER['HTTP_IF_NONE_MATCH']) ?
		stripslashes($_SERVER['HTTP_IF_NONE_MATCH']) : 
		false;
	
	$if_modified_since = isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ?
		stripslashes($_SERVER['HTTP_IF_MODIFIED_SINCE']) :
		false;
	
	if (!$if_modified_since && !$if_none_match)
		return;
	
	if ($if_none_match && $if_none_match != $etag && $if_none_match != '"' . $etag . '"')
		return; // etag is there but doesn't match
	
	if ($if_modified_since && $if_modified_since != $lastModified)
		return; // if-modified-since is there but doesn't match
	
	// Nothing has changed since their last request - serve a 304 and exit
	header('HTTP/1.1 304 Not Modified');
	exit();
} // doConditionalGet()

// old pond
// a frog jumps
// the sound of water

// —Matsuo Basho
?>