<?
	
	class codeCleaner{
		var $standardCurly = false;
		var $tabSpaces = false;
		var $removeempty = true;
				
		var $functionComm = true;
		var $ifComm = true;
		var $loopComm = true;
		var $classComm = true;
		
		//var $ifComm = true;
		//var $lookComm = true;
		//var $commLines = 4;
		
		function cleanFile($filename,$contents=""){
			
			if($filename) {
				$codelines = file($filename);
				echo "Going to Indent Code of : <b>$filename</b> <br>";
			}	
			
			if($contents){
				$codelines = explode("\n" , $contents);
			}
			
			$newlines = "";
			foreach($codelines as $oneline){
				if(trim($oneline) || $this->removeempty == false){
					//$oneline = ltrim($oneline) 
					$newlines .= $oneline;
				}
			}
			
			$newlines = $this->parseIt($newlines);
			$newlines = $this->addComments($newlines);
			
			return trim($newlines);
		}
		
		function addComments($contents){
		
			if(!$this->functionComm && !$this->classComm) return $contents;
			
			$class_comm  = "// -- Class Name : {class_name}\n";
			$class_comm .= "// -- Purpose : \n";
			$class_comm .= "// -- Created On : ";
			
			$func_comm  = "// -- Function Name : {func_name}\n";
			$func_comm .= "// -- Params : {func_params}\n";
			$func_comm .= "// -- Purpose : ";
			
			$newstr = "";
			$tokens = token_get_all($contents);
			foreach($tokens as $ind => $token){
				$name = @token_name($token[0]);
				$value = $token[1];
				$line = $token[2];
				
				if(!trim($name)){
					$value = $token[0];
					
					if($token[0] == "{") $name = "CURLY_START";
					if($token[0] == "}") $name = "CURLY_END";
					if($token[0] == "(") $name = "SMALL_START";
					if($token[0] == ")") $name = "SMALL_END";
					if($token[0] == "[") $name = "SQ_START";
					if($token[0] == "]") $name = "SQ_END";
					if($token[0] == ",") $name = "COMMA";
					if($token[0] == ";") $name = "COLON";
					if($token[0] == "=") $name = "EQUAL";
					if($token[0] == ".") $name = "DOT";
				}
				
				if(!trim($name)){
					$name = "UNKNOWN";
				}
				
				$comments = "";
				$fname = "";
				$cname = "";
				if($name == "T_CLASS" && $lastone != "T_COMMENT" && $this->classComm ){
					for($i=1 ; $i < 100 ; $i++){
						$nexttok = $tokens[($ind+$i)];
						if(@token_name($nexttok[0]) == "T_STRING"){
							$cname = $nexttok[1];
							break;
						}
					}
					$comments = str_replace("{class_name}" , $cname , $class_comm);
				}
				
				if($name == "T_FUNCTION" && $lastone != "T_COMMENT" && $this->functionComm){
					for($i=1 ; $i < 100 ; $i++){
						$nexttok = $tokens[($ind+$i)];
						if(@token_name($nexttok[0]) == "T_STRING"){
							$fname = $nexttok[1];
							break;
						}
					}
					$params = "";
					$so = 0;
					$sc = 0;
					for($x=($i+1) ; $x < 100 ; $x++){
						$nexttok = $tokens[($ind+$x)];
						
						if(!$nexttok[1]) $v = $nexttok[0];
						else $v = $nexttok[1];
						
						$params .= $v;
						if($v == "(") $so++;
						if($v == ")") $sc++;
						
						if($v == ")" && ($so > 0 && $so == $sc)){
							break;
						}
					}
					$comments = str_replace("{func_name}" , $fname , $func_comm);
					$comments = str_replace("{func_params}" , substr(trim($params) , 1 , -1) , $comments);
				}
				
				if($comments){
					$tabs = "";
					for($i=0 ; $i < 1000 ; $i++){
						$pos = ((strlen($newstr)-1)-$i);
						$chr = $newstr[$pos];
						if(ord($chr) == 9) {
							if($this->tabSpaces == false )$tabs .= "\t";
							else $tabs .= "    ";
						}	
						if(ord($chr) == 10) {
							//$pos--;
							break;
						}	
					}
					$part1 = substr($newstr , 0 , $pos);
					$part2 = substr($newstr , ($pos+1));
					$newstr = $part1."\n\n".str_replace("//" , "$tabs//" , $comments)."\n".$part2;
				}
				
				$newstr .= $value;
				
				if($name != "T_WHITESPACE")	$lastone = $name;
			}
			return $newstr;	
		}
		
		function parseIt($contents){
			$tokens = token_get_all($contents);
			$newstr = "";
			
			//if($this->tabSpaces == false ) $currtab = "\t";
			//else $currtab = "    ";
			$currtab = "\t";
			$incond = false;
			$smallso = 0;
			$smallsc = 0;
			$scs = 0;
			$sce = 0;
			
			foreach($tokens as $ind => $token){
				$name = @token_name($token[0]);
				$value = $token[1];
				
				if(!trim($name)){
					$value = $token[0];
					
					if($token[0] == "{") $name = "CURLY_START";
					if($token[0] == "}") $name = "CURLY_END";
					if($token[0] == "(") $name = "SMALL_START";
					if($token[0] == ")") $name = "SMALL_END";
					if($token[0] == "[") $name = "SQ_START";
					if($token[0] == "]") $name = "SQ_END";
					if($token[0] == ",") $name = "COMMA";
					if($token[0] == ";") $name = "COLON";
					if($token[0] == ":") $name = "COLON2";
					if($token[0] == "=") $name = "EQUAL";
					if($token[0] == ".") $name = "DOT";
				}
				
				if(!trim($name)){
					$name = "UNKNOWN";
				}
				
				$extra = 0;
				if($name == "T_WHITESPACE") {
					$nv = "";
					for($i=0 ; $i < strlen($value) ; $i++){
						$extra = ord($value[$i]);
						if($extra != 10 && $extra != 13 && $extra != 9) $nv .= $value[$i];
					}
					$value = $nv;
					if($value=="") continue;
				}	
				
				if($name == "T_SWITCH"){
					$switch = true;
				}
				
				if($switch){
					if($name == "CURLY_START"){
						$scs++;
					}
					if($name == "CURLY_END"){
						$sce++;
					}
				}
				
				
				if($name == "CURLY_START") {
					$currtab .= "\t";
				}	
				
				if($name == "CURLY_END" ) {
					$currtab = substr($currtab , 0 , -1);
					
					if($switch){
						if($scs >0 && $scs == $sce){
							$currtab = substr($currtab , 0 , -1);
							$switch = false;
							$lookforcol2 = false;
							$caseend = true;
						}
					}
				}	
				
				if($name == "T_CASE" || $name == "T_DEFAULT"){
					$lookforcol2 = true;
				}
				
				if($name == "COLON2"  && $lookforcol2){
					$currtab .= "\t";
				}
				
				if($caseend && $name == "COLON"){
					$currtab = substr($currtab , 0 , -1);
					$caseend = false;
				}
				
				if($name == "T_BREAK" && $lookforcol2){
					$caseend = true;
					$lookforcol2 = false;
				}else{
					$caseend = false;
				}
				
				if($lastone == "CURLY_END" && $name == "T_WHITESPACE") $value = str_replace(" " , "" , $value);
				
				if($name == "T_OPEN_TAG" && $value == "<?") $value = "<?php";
				
				if($incond && $name== "SMALL_START") $smallso++;
				if($incond && $name== "SMALL_END") $smallsc++;
				
				if($incond && ($smallso > 0) && $smallso == $smallsc) $incond =false;
				
				if(($lastone == "COLON" && $incond == false) || $lastone == "COLON2" || $lastone == "CURLY_START" || $lastone == "T_OPEN_TAG" || $name == "T_FUNCTION" || $name == "T_CLASS"  || $lastone == "T_COMMENT") {
					if($name == "T_COMMENT") {
						if($lastone != "T_COMMENT")	$value = "\n".$currtab.trim($value," ");
						else $value = $currtab.trim($value," ");
					}else {
						if($name == "T_FUNCTION") {
							if($lastone == "T_COMMENT") $value = $currtab.trim($value," ");
							else $value = "\n".$currtab.trim($value," ");
								
						}else{
							$value = "\n".$currtab.trim($value," ");
						}	
					}	
				}	
				if($name == "T_IF") $value = "\n".$currtab.$value;
				
				if($name == "CURLY_START") {
					if($this->standardCurly == true) $value = "\n".substr($currtab,0,-1).$value;	
				}
				
				if($name == "CURLY_END") {
					if(substr($newstr , -1) == chr(9)) {
						$newstr = substr($newstr , 0 , -1);
					}	
					$value .= "\n\n";
				}	
				
				if($lastone == "CURLY_END") $value = $currtab.$value;
				
				if($name == "T_FOR") {
					$incond = true;
					$smallso = 0;
					$smallsc = 0;
 				}
				
				if($name == "T_ELSE") {
					$value = trim($value);
					$rnewstr = $newstr;
					for($i=0 ; $i < 1000 ; $i++){
						$pos = ((strlen($rnewstr)-1)-$i);
						$chr = ord($newstr[$pos]);
						if($chr != 9 && $chr != 10 && $chr != 13 && $chr != 32) {
							break;
						}else{
							$newstr = substr($newstr , 0 , -1);
						}	
					}
					if($this->standardCurly == true) $value = "\n".$currtab.$value;
					else $value = " ".$value;
				}
				
				if($lastone == "T_ELSE" && $name != "T_WHITESPACE") $value = " ".$value;
				
				$newstr .= $value;
				//echo "$name -- $value -- $extra<hr>";
				$lastone = $name;
			}

			$tokens = token_get_all($newstr);
			$newstr = "";
			foreach($tokens as $ind => $token){
				$name = @token_name($token[0]);
				$value = $token[1];
				$line = $token[2];
				
				if($value == '') $value = $token[0];
				
				if($name == "T_WHITESPACE" && substr_count($value , chr(10))){
					$value = str_replace(" " , "" , $value);
					
					if($this->tabSpaces == true ) $value = str_replace(chr(9) , "    " , $value);
				}
				$newstr .= $value;
			}	

			$tokens = token_get_all($newstr);
			$newstr = "";
			foreach($tokens as $ind => $token){
				$name = @token_name($token[0]);
				$value = $token[1];
				$line = $token[2];
				
				if(trim($name) == '') $value = $token[0];
				
				if($name == "T_OPEN_TAG"){
					$lb = 0;
					$found = false;
					for($i=1 ; $i < 1000 ; $i++){
						$pret = $tokens[($ind+$i)];
						$pname = @token_name($pret[0]);
						$pvalue = $pret[1];
						
						if($pname == "T_WHITESPACE"){
							if(substr_count($pvalue , "\n")) $lb++;
						}
						
						if($lb > 2) break;
						if($pname == "T_CLOSE_TAG") {
							$found = true;
							break;
						}	
					}
					
					if($found) $value .= " ";
				}
				
				if($name == "T_CLOSE_TAG" && $found) $value = " ".$value;
				if($name == "T_CLOSE_TAG") $found = false;
				
				if($found ){
					if($this->tabSpaces == false ) $value = str_replace(array("\n" , "\t") , "" , $value);
					else $value = str_replace(array("\n" , "\t" , "    ") , "" , $value);
					$value = str_replace(array("\n" , "\t") , "" , $value);
				}
				
				$newstr .= $value;
			}			
						
			return $newstr;
		}
		
	}
?>