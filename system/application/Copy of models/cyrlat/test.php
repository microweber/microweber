<?php
require_once("cyrlat.class.php");
$text=new CyrLat;

$input="Привет, Мир!";
echo $text->cyr2lat($input);
echo "<hr>\n";
$input="Privet, Mir!";
echo $text->lat2cyr($input);
?>