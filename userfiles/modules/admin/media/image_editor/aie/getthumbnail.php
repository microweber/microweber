<?php
/*-- ***** BEGIN LICENSE BLOCK *****
  -   Version: MPL 1.1/GPL 2.0/LGPL 2.1
  -
  - The contents of this file are subject to the Mozilla Public License Version
  - 1.1 (the "License"); you may not use this file except in compliance with
  - the License. You may obtain a copy of the License at
  - http://www.mozilla.org/MPL/
  - 
  - Software distributed under the License is distributed on an "AS IS" basis,
  - WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License
  - for the specific language governing rights and limitations under the
  - License.
  -
  - The Original Code is AIE (Ajax Image Editor).
  -
  - The Initial Developer of the Original Code is
  - Julian Stricker.
  - Portions created by the Initial Developer are Copyright (C) 2006
  - the Initial Developer. All Rights Reserved.
  -
  - Contributor(s):
  -
  - Alternatively, the contents of this file may be used under the terms of
  - either the GNU General Public License Version 2 or later (the "GPL"), or
  - the GNU Lesser General Public License Version 2.1 or later (the "LGPL"),
  - in which case the provisions of the GPL or the LGPL are applicable instead
  - of those above. If you wish to allow use of your version of this file only
  - under the terms of either the GPL or the LGPL, and not to allow others to
  - use your version of this file under the terms of the MPL, indicate your
  - decision by deleting the provisions above and replace them with the notice
  - and other provisions required by the GPL or the LGPL. If you do not delete
  - the provisions above, a recipient may use your version of this file under
  - the terms of any one of the MPL, the GPL or the LGPL.
  - 
  - ***** END LICENSE BLOCK ***** */
include_once( "config.inc.php" );
if ( !isset( $_GET["maxx"] ) ) {
	$maxx = 128;
} else {
	$maxx = intval($_GET["maxx"]);
}
if ( !isset( $_GET["maxy"] ) ) {
	$maxy = 96;
} else {
	$maxy = intval($_GET["maxy"]);
}
if ( !isset( $_GET["dat"] ) ) {
	die();
} else {
	$dat = $web_root_dir.$_GET["dat"];
}
$fehler = 0;
$info = getimagesize( $dat );
$bildnamearr = explode( ".", $dat );
$endung = strtolower( $bildnamearr[count( $bildnamearr ) - 1] );
if ( ( $info[2] > 0 && $info[2] < 4 ) || $endung == "flv" ) { // Ist die Datei ein GIF, JPG oder PNG?
	if ( is_file( $server_temp_dir.md5( $dat.filectime( $dat ).$maxx.$maxy ).".png" ) ) { // Gibt es schon ein Thumbnail?
		header( "Content-Type: image/png" );
		$fp = fopen( $server_temp_dir.md5( $dat.filectime( $dat ).$maxx.$maxy ).".png", "r" );
		echo fpassthru( $fp );
	} else { // Erstelle Thumbnail
	  	if ( $endung == 'flv' && $ffmpeg_location!="" ) {
			$bildname = $server_temp_dir.md5( $dat.filectime( $dat ).$maxx.$maxy ).".png";
			$befehl = $ffmpeg_location." -y -i '".escapeshellarg($dat)."' -vframes 1 -ss 00:00:02 -an -vcodec png -f rawvideo '".$web_root_dir.$bildname."'";
			$ausgabe = Array();
			@exec( $befehl, $ausgabe );
			$info = @getimagesize( $bildname );
			if ( $info ) {
				$oimage = imagecreatefrompng( $bildname );
				$ogrx = $info[0];
				$ogry = $info[1];
				if ( $ogrx / $maxx > $ogry / $maxy ) { //Breitformat
					$ngrx = $maxx;
					$ngry = ( $ogry * $maxx ) / $ogrx;
				} else { //Hochformat
					$ngry = $maxy;
					$ngrx = ( $ogrx * $maxy ) / $ogry;
				}
				$image = imagecreatetruecolor( $ngrx, $ngry );
				imagesavealpha( $image, true );
				$farbe_body = imagecolorallocate( $image, 255, 255, 255 );
				$trans = imagecolortransparent( $image, $farbe_body );
				imagecopyresampled( $image, $oimage, 0, 0, 0, 0, $ngrx, $ngry, $ogrx, $ogry );
				header( "Content-Type: image/png" );
				imagepng( $image, $bildname );
				imagepng( $image );
			} else {
				$fehler = 1;
			}
	  	} else {
			if ( $info[2] == 1 ) { //Original ist ein GIF
				$oimage = imagecreatefromgif( $dat );
			} else if ( $info[2] == 2 ) { //Original ist ein JPG
				$oimage = imagecreatefromjpeg( $dat );
			} else if ( $info[2] == 3 ) { //Original ist ein PNG
				$oimage = imagecreatefrompng( $dat );
			}
			$ogrx = $info[0];
			$ogry = $info[1];
			if ( $ogrx / $maxx > $ogry / $maxy ) { //Breitformat
				$ngrx = $maxx;
				$ngry = ( $ogry * $maxx ) / $ogrx;
			} else { //Hochformat
				$ngry = $maxy;
				$ngrx = ( $ogrx * $maxy ) / $ogry;
			}
			if ( $info[2] == 2 || $info[2] == 3 ) { //PNG, JPG
				$image = imagecreatetruecolor( $ngrx, $ngry );
			} else { //GIF
				$image = imagecreate( $ngrx, $ngry );
			}
			if ( $info[2] == 3 ) { //PNG
				//imagealphablending( $image, false );
				imagesavealpha( $image, true );
				$farbe_body = imagecolorallocate( $image, 255, 255, 255 );
				$trans = imagecolortransparent( $image, $farbe_body );
			} else if ( $info[2] == 1 ) { //GIF
				$farbe_body = imagecolorallocate( $image, 255, 255, 255 );
				//imagealphablending( $image, true );
				//$trans = imagecolortransparent( $image, $farbe_body );
			}
			//imagecopyresized( $image, $oimage, 0, 0, 0, 0, $ngrx, $ngry, $ogrx, $ogry );
			imagecopyresampled( $image, $oimage, 0, 0, 0, 0, $ngrx, $ngry, $ogrx, $ogry );
			header( "Content-Type: image/png" );
			imagepng( $image, $server_temp_dir.md5( $dat.filectime( $dat ).$maxx.$maxy ).".png" );
			imagepng( $image );
			imagedestroy( $image );
			imagedestroy( $oimage );
		}
	}
}else{

//if ( $fehler != 0 ) {
	$fileendungen = array(
		"aif" => "AIF",
		"aiff" => "AIF",
		"avi" => "AVI",
		"wmv" => "WMV",
		"" => "plain_text",
		"bmp" => "BMP",
		"cdr" => "CDR",
		"divx" => "DivX",
		"dll" => "DLL",
		"doc" => "DOC",
		"eps" => "EPS",
		"eps2" => "EPS2",
		"gif" => "GIF",
		"htm" => "HTML2",
		"html" => "HTML2",
		"ini" => "INI",
		"jpg" => "JPG",
		"jpeg" => "JPG",
		"jsp" => "JSP",
		"midi" => "MIDI",
		"mid" => "MIDI",
		"mov" => "MOV1",
		"mp3" => "MP3",
		"mpg" => "MPG1",
		"mpeg" => "MPG1",
		"otf" => "OTF",
		"pdf" => "PDF",
		"pfm" => "PFM",
		"php" => "PHP",
		"text" => "plain_text",
		"ppt" => "PPT",
		"rar" => "RAR",
		"sit" => "SIT",
		"tif" => "TIF",
		"ttf" => "TTF",
		"txt" => "TXT1",
		"wav" => "WAV",
		"wma" => "WAV",
		"xls" => "XLS",
		"xml" => "XML"
	);
	$fileaendung = explode( ".", $dat );
	$dieaendung = strtolower( $fileaendung[count( $fileaendung ) - 1] );
	if ( isset( $fileendungen[$dieaendung] ) ) {
		header( "Content-Type: image/png" );
		$fp = fopen( "fileicons/".$fileendungen[$dieaendung].".png", "r" );
		echo fpassthru( $fp );
	} else {
		header( "Content-Type: image/png" );
		$fp = fopen( "fileicons/plain_text.png", "r" );
		echo fpassthru( $fp );
	}
}
?>
