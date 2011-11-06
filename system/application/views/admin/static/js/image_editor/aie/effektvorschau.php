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
require ( "config.inc.php" );
if (isset($_GET["img"])){
	$bildpfad=$server_temp_dir.$_GET["img"];
	$posx="+0";
	$posy="+0";
	if (isset($_GET["posx"])) $posx=intval($_GET["posx"]);
	if (isset($_GET["posy"])) $posy=intval($_GET["posy"]);
	$befehlszeilea=$imagemagick_dir."convert -crop 120x120+".$posx."+".$posy."  ";
	if (isset($_GET["eff"])){
		if ($_GET["eff"]=="blur"){
			$befehlszeilea.=" -blur ".intval($_GET["param1"])."x".intval($_GET["param1"])."%";
		}else if ($_GET["eff"]=="contrast"){
			
			//$befehlszeilea.=" -fill white -tint ".(intval($_GET["param1"])+100)."%";
			$param1=intval($_GET["param1"]);
			if ($param1<0){
				$param1=abs($param1);
				$befehlszeilea.=" -fill black -colorize ".$param1."%";
			}else{
				$befehlszeilea.=" -fill white -colorize ".$param1."%";
			}
			$param2=intval($_GET["param2"]);
			$param2=($param2/2);
			$befehlszeilea.=" -level ".$param2."%,".(100-$param2)."%,1.0";
		}else if ($_GET["eff"]=="normalize"){
			$befehlszeilea.=" -normalize";
		}else if ($_GET["eff"]=="invert"){
				$befehlszeilea.=" -negate";
		}else if ($_GET["eff"]=="balance"){
			
			//$befehlszeilea.=" -fill white -tint ".(intval($_GET["param1"])+100)."%";
			$param1=intval($_GET["param1"]);
			$param2=intval($_GET["param2"]);
			$param3=intval($_GET["param3"]);
			$befehlszeilea.=" -gamma ".(((100+$param1)/100)).",".(((100+$param2)/100)).",".(((100+$param3)/100));
		}
	}
	exec($befehlszeilea." '".escapeshellarg($bildpfad)."' '".$server_temp_dir."effekttemp.jpg'");
	header("Content-type: image/jpeg"); 
	$image = imagecreatefromjpeg($server_temp_dir."effekttemp.jpg"); 
	imagejpeg($image); 
}
?>
