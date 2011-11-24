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
$quality=' -quality 100';

if (isset($_GET["img"])){
  $historypos="";
	$bildpfad=$server_temp_dir.$_GET["img"];
	if (!is_file($bildpfad."-0")){
	 @copy($bildpfad,$bildpfad."-0");
	}
	
	//bildfunktion ausführen?
	if (isset($_GET["func"])){	  
	  if (isset($_GET["historypos"])) {
      $historypos="-".intval($_GET["historypos"]);
      $historyposvor="-".(intval($_GET["historypos"])-1);
      
    }
		if ($_GET["func"]=="crop"){
			$pm=explode(",",$_GET["params"]);
			//?img="+was+"&func="+was+"&params="+oh1+","+ov1+","+oh2+","+ov2;
			$befehlszeilea=$imagemagick_dir."convert -gravity NorthWest -crop ".(intval($pm[2])-intval($pm[0]))."x".(intval($pm[3])-intval($pm[1]))."+".intval($pm[0])."+".intval($pm[1])." ";
			exec($befehlszeilea." '".escapeshellarg($bildpfad.$historyposvor)."' '".escapeshellarg($bildpfad.$historypos)."'");
			//echo $befehlszeilea." '".$bildpfad."' '".$bildpfad."'";
		}else if ($_GET["func"]=="resize"){
			$pm=explode(",",$_GET["params"]);
			//?img="+was+"&func="+was+"&params="+oh1+","+ov1+","+oh2+","+ov2;
			$befehlszeilea=$imagemagick_dir."convert -resize ".(intval($pm[0]))."x".(intval($pm[1]))." ";
			exec($befehlszeilea." '".escapeshellarg($bildpfad.$historyposvor)."' '".escapeshellarg($bildpfad.$historypos)."'");
			//echo $befehlszeilea." '".$bildpfad."' '".$bildpfad."'";
		}else if ($_GET["func"]=="rotate"){
			$pm=$_GET["params"];
			if ($pm=="flip"){
				$befehlszeilea=$imagemagick_dir."convert -flip ";
			}else if ($pm=="flop"){
				$befehlszeilea=$imagemagick_dir."convert -flop ";
			}else{
				$befehlszeilea=$imagemagick_dir."convert -rotate ".escapeshellarg($pm);
			}
			
			//?img="+was+"&func="+was+"&params="+oh1+","+ov1+","+oh2+","+ov2;
			
			exec($befehlszeilea." '".$bildpfad.$historyposvor."' '".$bildpfad.$historypos."'");
			//echo $befehlszeilea." '".$bildpfad."' '".$bildpfad."'";
		}else if ($_GET["func"]=="filter"){
			$pm=explode(",",$_GET["params"]);
			$befehlszeilea=$imagemagick_dir."convert ";
			if ($pm[0]=="blur"){
				$befehlszeilea.=" -blur ".intval($pm[1])."x".intval($pm[1])."%";
			}else if ($pm[0]=="contrast"){
				
				//$befehlszeilea.=" -fill white -tint ".(intval($_GET["param1"])+100)."%";
				$param1=intval($pm[1]);
				if ($param1<0){
					$param1=abs($param1);
					$befehlszeilea.=" -fill black -colorize ".$param1."%";
				}else{
					$befehlszeilea.=" -fill white -colorize ".$param1."%";
				}
				$param2=intval($pm[2]);
				$param2=($param2/2);
				$befehlszeilea.=" -level ".$param2."%,".(100-$param2)."%,1.0";
			}else if ($pm[0]=="normalize"){
				$befehlszeilea.=" -normalize";
			}else if ($pm[0]=="invert"){
				$befehlszeilea.=" -negate";
			}else if ($pm[0]=="balance"){
				
				//$befehlszeilea.=" -fill white -tint ".(intval($_GET["param1"])+100)."%";
				$param1=intval($pm[1]);
				$param2=intval($pm[2]);
				$param3=intval($pm[3]);
				$befehlszeilea.=" -gamma ".(((100+$param1)/100)).",".(((100+$param2)/100)).",".(((100+$param3)/100));
			}
			exec($befehlszeilea." '".escapeshellarg($bildpfad.$historyposvor)."' '".escapeshellarg($bildpfad.$historypos)."'");
			
		}else if ($_GET["func"]=="schrift"){
			$pm=explode(",",$_GET["params"]);
			
			$befehlszeilea=$imagemagick_dir."composite -compose over -geometry +".intval($pm[0])."+".intval($pm[1])." '".$server_temp_dir.escapeshellarg($pm[2])."'";
			
			
			//?img="+was+"&func="+was+"&params="+oh1+","+ov1+","+oh2+","+ov2;
			
			exec($befehlszeilea." '".escapeshellarg($bildpfad.$historyposvor)."' ".$quality." '".escapeshellarg($bildpfad.$historypos)."'");
		}else if ($_GET["func"]=="watermark"){
			$pm=explode(",",$_GET["params"]);
			$blendtext="";
			if (intval($pm[0])<100) $blendtext=" -blend ".intval($pm[0]);
			$befehlszeilea=$imagemagick_dir."composite -compose over".$blendtext." ".escapeshellarg($web_root_dir."images/".$pm[1])." -resize ".(intval($pm[4])-intval($pm[2]))."x".(intval($pm[5])-intval($pm[3]))."! -geometry +".intval($pm[2])."+".intval($pm[3]);
			
			
			//?img="+was+"&func="+was+"&params="+oh1+","+ov1+","+oh2+","+ov2;
			
			exec($befehlszeilea." ".escapeshellarg($bildpfad.$historyposvor)." ".$quality." ".escapeshellarg($bildpfad.$historypos));
			//echo $befehlszeilea." ".escapeshellarg($bildpfad.$historyposvor)." ".$quality." ".escapeshellarg($bildpfad.$historypos);
		}
	}else{
	 if (isset($_GET["historypos"])) {
      $historypos="-".intval($_GET["historypos"]);
      //if ($historypos=="-0") $historypos="";
    }
	}
	@copy($bildpfad.$historypos,$bildpfad);
	$dateityp = GetImageSize ( $bildpfad.$historypos );
	if ( $dateityp[2] != 0 ) {
		
		if ( $dateityp[2] == 1 ) { //gif:
			header("Content-type: image/gif"); 
			//header("Content-Transfer-Encoding: binary");
      //header('Content-Length: '. filesize($bildpfad.$historypos)); 
			$image = imagecreatefromgif($bildpfad.$historypos); 
			imagegif($image); 
		}else if ( $dateityp[2] == 2 ) { //jpeg:
			header("Content-type: image/jpeg"); 
			//header("Content-Transfer-Encoding: binary");
      //header('Content-Length: '. filesize($bildpfad.$historypos)); 
			$image = imagecreatefromjpeg($bildpfad.$historypos); 
			imagejpeg($image); 
		}else if ( $dateityp[2] == 3 ) { //png:
			header("Content-type: image/png"); 
			//header("Content-Transfer-Encoding: binary");
      //header('Content-Length: '. filesize($bildpfad.$historypos)); 
			$image = imagecreatefrompng($bildpfad.$historypos); 
			imagepng($image); 
		}
			
			
	}
}
?>
