		INTRO
This class *Cyrillic to Latin (translit)* provides methods for converting (translitering) symbols. License: FREEWARE (please, leave my copyright!).
What you need to work with this class:
No additional extensions/pears/pecls needed.


functions:

string cyr2lat(input string) - cyrillic to latin convert
string lat2cyr(input string) - latin to cyrillic convert

variables:

array $cyr - cyrillic symbols
array $lat - latin replacement for cyrillic symbols
array $lat_additional - additional latin symbols set (for non-usuall replacements)
array $cyr_additional - additional cyrillic symbols (to replace non-usuall latin symbol replacements)

		BUGS
Known bugs and _undocumented features_:

+ Work only with Windows-1251 codepage. If you want use it with koi8-r or other, use php-standard function convert_cyr_string(...) on your input, like 
  <code>
   #...
   $input=convert_cyr_string($input, "k", "w"); //convert from KOI8-R to Windows-1251
   #...
  </code>
+ Some symbolsets have wrong translitering (by their dual nature). For example:
  "Shoroh" is "Шорох" and "Shvatka" is "Шватка" (not "Схватка"). Bugfix: manual editing of outputed string  :)
  # Слижком уж он велик и могуч, этот Русский язык. Всё алгоритмом не опишешь. 
+ Class represent my own vision of translit rules. If you want "Jopa" instead of "Zhopa" - edit $cyr and $lat 

  (!notice) symbols order matters in arrays $cyr, $lat, $cyr_additional, $lat_additional!

		USAGE
The usage is rather simple:
<code>
#------->8------test.php------------
<?php
require_once("cyrlat.class.php");// include main class
$text=new CyrLat; // initialize $text as 

$input="Привет, Мир!";
echo $text->cyr2lat($input);
echo "<hr>\n";
$input="Privet, Mir!";
echo $text->lat2cyr($input);
?>
#-------8<---------------------------
</code>

See /examples/ for more examples.
You can simply integrate it to forums, forms, sms-senders etc.
Da pribudet s vami Sila!