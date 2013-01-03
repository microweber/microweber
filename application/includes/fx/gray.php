<?php

$url = $_GET['img'];


$type="";

$info = getimagesize($url);
$mime = image_type_to_mime_type($info[2]);

if($mime=='image/jpeg'){
  $im = imagecreatefromjpeg($url);
  $type="jpg";
}
else if($mime=='image/png'){
  $im = imagecreatefrompng($url);
  imagealphablending($im, false);
  imagesavealpha($im, true);

  $type="png";
}
else if($mime=='image/gif'){
  $im = imagecreatefromgif($url);
  $type="gif";
}
else{
  print $url;
  die();
}



imagefilter($im, IMG_FILTER_GRAYSCALE);

 
 
$name = 'image_'.uniqid().".".$type;

if($type=='jpg'){
  $img = imagejpeg($im, 'img/'.$name, 96);
}
else if($type=='png'){
   $img = imagepng($im, 'img/'.$name, 0);
}
else if($type=='gif'){
  $img = imagegif($im, 'img/'.$name);
}
imagedestroy($im);

print "<body style='background:magenta'><img src='http://pecata/1k/application/includes/fx/img/" . $name . "' /></body>";



?>