<?php
require ( "config.inc.php" );
if (isset($_GET["file"])) {
  $bildpfad=$server_temp_dir.$_GET["file"];
  @unlink($bildpfad);
}
function fromhex($string) {
	global $image;
	sscanf($string, "%2x%2x%2x%2x", $red, $green, $blue, $alpha);
	return imagecolorallocatealpha($image,$red,$green,$blue, $alpha);
}
$bildgrx=100;
$bildgry=100;


$image = imagecreatetruecolor($bildgrx,$bildgry); 


// <img src="/modules/default/filedownload/filedownload.php?file=FILE&bgcolor=FFFFFF&color=000000&font=arial.ttf&fontsize=10" border="0" alt="Download File" /></a>

$fontsize=10;
if (isset($_GET["color"])){ 
	$farbestr = str_replace("#", "", $_GET["color"]);
	$farbe_b=fromhex($farbestr);
}else{
	$farbe_b=imagecolorallocatealpha($image,255,255,255,0); 
}

$farbe_body = imagecolorallocatealpha($image,0,0,0,0);

$font="arial.ttf";
if (isset($_GET["font"])){
	if (file_exists($fonts_dir.$_GET["font"])){
		$font=$_GET["font"];
	}
}
if (isset($_GET["fontsize"])) $fontsize=$_GET["fontsize"];




$fontstring="AIE - Ajax Image Editor"; 
if (isset($_GET["fontstring"])){
	$fontstring=$_GET["fontstring"];
}
$tcoords=ImageTTFText ($image, $fontsize, 0, 32+4, ($bildgry/2)+$fontsize/2,  $farbe_b, $fonts_dir.$font,$fontstring);
$imagee = imagecreatetruecolor($tcoords[2]-$tcoords[0],$tcoords[3]-$tcoords[5]);
imageSaveAlpha($imagee, true);
ImageAlphaBlending($imagee, false);  
$farbe_body=imagecolorallocatealpha($imagee,255,255,255,127); 
imagecolortransparent($imagee, $farbe_body); 
imagefilledrectangle($imagee, 0, 0,  $tcoords[2]-$tcoords[0],$tcoords[3]-$tcoords[5], $farbe_body);
$tcoords=ImageTTFText ($imagee, $fontsize, 0, 0, (($bildgry/2)+$fontsize/2)-$tcoords[5],  $farbe_b, $fonts_dir.$font,$fontstring);
if (isset($_GET["file"])){
  imagepng($imagee,$bildpfad);
}
header( "Content-Type: image/png" );

imagepng($imagee); 
imagedestroy($image);
?>
