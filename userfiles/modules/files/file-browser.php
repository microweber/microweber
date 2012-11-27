<?

########################################################
##                                                    ##
## FILE BROWSER - PHP CLASS             VERSION 1.3.1 ##
## OPEN SOURCE            FREE FOR NON-COMMERCIAL USE ##
##                                                    ##
## AUTHOR: BOGDAN ZARCHIEVICI                         ##
##                                                    ##
## LICENSED UNDER CREATIVE COMMONS                    ##
## ATTRIBUTION-NONCOMMERCIAL-SHAREALIKE 2.5           ##
##                                                    ##
########################################################

class file_browser
{ var $templates=null;
  var $displaydot=false;
  var $template=null;
  var $version="1.3.1";

  function dirsize($directory)
  { $content=opendir($directory);
    $size=0;
    while (($filename=readdir($content))!==false)
    if ($filename!="." && $filename!="..")
    { $path=$directory."/".$filename;
      if (is_dir($path)) $size+=$this->dirsize($path);
      if (is_file($path)) $size+=filesize($path);
    }
    closedir($content);
    return $size;
  }

  function bytesize($size)
  { if (empty($size)) return number_format($size, 2)." KB"; $size=$size/1024;
    if ($size < 1024) return number_format($size, 2)." KB";
    elseif ($size / 1024 < 1024) return number_format($size/1024, 2)." MB";
    elseif ($size / 1024 / 1024 < 1024) return number_format($size / 1024 / 1024, 2)." GB";
    else return number_format($size / 1024 / 1024 / 1024, 2)." TB";
  }

  function error($code)
  { switch($code)
    { case 'ERR01': $error='Invalid interface path. Please make a proper call for the File Browser Class.'; break;
      case 'ERR02': $error='Valid interface path but the template you have selected does not exist.'; break;
    }
    if (isset($error))
    { echo '			<table align="center" border="0" cellpadding="0" cellspacing="0" width="780">';
      echo '				<tr>';
      echo '					<td align="left" bgcolor="#BF000D"   height="21" nowrap="nowrap" style="padding-left: 05px; padding-right: 10px"><font face="Tahoma" size=1 style="font-size: 8pt; text-decoration: none;" color="#FFFFFF"><b>Error:</b></font></td>';
      echo '					<td align="left" bgcolor="#BF000D"   width="100%" height="21" nowrap="nowrap" style="padding-left: 05px; padding-right: 10px"><font face="Tahoma" size=1 style="font-size: 8pt; text-decoration: none;" color="#FFFFFF" width="100%"><b>'.$error.'</b></font></td>';
      echo '					<td align="right" bgcolor="#BF000D"  height="21" nowrap="nowrap" style="padding-left: 05px; padding-right: 05px"><font face="Tahoma" size=1 style="font-size: 8pt; text-decoration: none;" color="#FFFFFF"><b>Please fix it.</b></font></td>';
      echo '				</tr>';
      echo '			</table>'; }
    exit;
  }

  function filetypes($extension=null,$advanced=false,$hidden=false)
  { if ($advanced==true)
    { $filetypes[null]="";
      $filetypes['ai']="Adobe Illustrator";
      $filetypes['aif']="Audio Interchange";
      $filetypes['aiff']="Audio Interchange";
      $filetypes['ani']="Animated Cursor";
      $filetypes['ans']="ANSI Text";
      $filetypes['api']="Application Program Interface";
      $filetypes['app']="Macromedia Authorware Package";
      $filetypes['arc']="ARC Compressed";
      $filetypes['arj']="ARJ Compressed";
      $filetypes['asc']="ASCII Text";
      $filetypes['asf']="Active Streaming";
      $filetypes['asm']="Assembly Source Code";
      $filetypes['asp']="Active Server Page";
      $filetypes['avi']="AVI Movie";
      $filetypes['bak']="Backup Copy";
      $filetypes['bas']="BASIC Program";
      $filetypes['bat']="Batch";
      $filetypes['bk$']="Backup";
      $filetypes['bk']="Backup Copy";
      $filetypes['bmp']="Bitmap";
      $filetypes['c']="C Program";
      $filetypes['cab']="Microsoft Compressed";
      $filetypes['cdr']="CorelDraw";
      $filetypes['cdt']="CorelDraw Template";
      $filetypes['cdx']="CorelDraw Compressed";
      $filetypes['cdx']="FoxPro Database Index";
      $filetypes['cfg']="Configuration";
      $filetypes['cgi']="Common Gateway Interface";
      $filetypes['cpp']="C++ Program";
      $filetypes['css']="Cascading Style Sheet";
      $filetypes['csv']="Comma Delimited";
      $filetypes['cur']="Windows Cursor Image";
      $filetypes['dat']="Data";
      $filetypes['db']="Table - Paradox";
      $filetypes['dbc']="Visual FoxPro Database";
      $filetypes['dbf']="dBASE Database";
      $filetypes['dbt']="dBASE Database Text";
      $filetypes['doc']="Document file";
      $filetypes['drv']="Driver";
      $filetypes['dwg']="AutoCAD Vector";
      $filetypes['eml']="Electronic Mail";
      $filetypes['enc']="Encoded";
      $filetypes['eps']="Encapsulated PostScript";
      $filetypes['exe']="Executable";
      $filetypes['fax']="Fax";
      $filetypes['fnt']="Font";
      $filetypes['fon']="Bitmapped Font";
      $filetypes['fot']="TrueType Font";
      $filetypes['gif']="Graphics Interchange";
      $filetypes['gz']="GZIP Compressed";
      $filetypes['h']="C Header";
      $filetypes['hlp']="Help";
      $filetypes['htm']="HTML Document";
      $filetypes['html']="HTML Document";
      $filetypes['ico']="Icon";
      $filetypes['it']="MOD Music";
      $filetypes['jpeg']="JPEG Image";
      $filetypes['jpg']="JPEG Image";
      $filetypes['lib']="Library";
      $filetypes['log']="Log";
      $filetypes['lst']="List";
      $filetypes['mime']="MIME";
      $filetypes['mme']="MIME Encoded";
      $filetypes['mov']="QuickTime Movie";
      $filetypes['movie']="QuickTime movie";
      $filetypes['mp2']="MP2 Audio";
      $filetypes['mp3']="MP3 Audio";
      $filetypes['mpe']="MPEG";
      $filetypes['mpeg']="MPEG Movie";
      $filetypes['mpg']="MPEG Movie";
      $filetypes['pas']="Pascal Program";
      $filetypes['phps']="PHP Source Code";
      $filetypes['phtml']="HTML Document";
      $filetypes['pjx']="Visual FoxPro Project";
      $filetypes['pl']="Perl script";
      $filetypes['pps']="PowerPoint Slideshow";
      $filetypes['ppt']="PowerPoint Presentation";
      $filetypes['psd']="Photoshop Document";
      $filetypes['qt']="QuickTime Movie";
      $filetypes['qtm']="QuickTime Movie";
      $filetypes['ra']="Real Audio";
      $filetypes['ram']="Real Audio";
      $filetypes['reg']="Registration File";
      $filetypes['rm']="Real Media";
      $filetypes['s']="Assembly Language";
      $filetypes['shtml']="HTML Document";
      $filetypes['sit']="StuffIT Compressed";
      $filetypes['snd']="Sound File";
      $filetypes['swf']="Flash Movie";
      $filetypes['swp']="Swap Temporary File";
      $filetypes['sys']="System File";
      $filetypes['tar']="Tape Archive";
      $filetypes['tga']="TARGA Graphics";
      $filetypes['tgz']="Tape Archive";
      $filetypes['tif']="TIFF Graphics";
      $filetypes['tiff']="TIFF Graphics";
      $filetypes['tmp']="Temporary File";
      $filetypes['ttf']="TrueType Font";
      $filetypes['txt']="Text File";
      $filetypes['uu']="Uuencode Compressed";
      $filetypes['uue']="Uuencode File";
      $filetypes['vbp']="Visual Basic Project";
      $filetypes['vbx']="Visual Basic Extension";
      $filetypes['wab']="Windows Address Book";
      $filetypes['wav']="Sound File";
      $filetypes['xlm']="Excel Macro";
      $filetypes['xls']="Excel Worksheet";
      $filetypes['xlt']="Excel Template";
      $filetypes['xm']="MOD Music";
      $filetypes['xxe']="Xxencoded File";
      $filetypes['z']="Unix Archive";
      $filetypes['zip']="Archive";
    }
    if ($hidden==true) return "Hidden File";
    if (!empty($advanced) && isset($filetypes[$extension]) && !empty($filetypes[$extension])) return $filetypes[$extension];
    else return strtoupper($extension).' File';
  }

  function row($templates,$type,$path,$title,$adtype)
  { unset($filename);
    $properties=pathinfo($title); if (!isset($properties['extension'])) $properties['extension']=null;
    if (substr($title,0,1)=='.') $hidden=true; else $hidden=false;
    switch($title)
    { case '.':  $filename['title']='[.]';
                 $filename['path']=$path;
                 $filename['type']=(empty($type)?$this->filetypes($properties['extension'],$adtype,$hidden):"Folder");
                 $filename['size']="--- ---";
                 $filename['icon']="folder";
                 $filename['link']=$_SERVER['PHP_SELF']."?dir=".base64_encode(realpath($path));
                 $filename['update']=date("F dS, Y - H:i",filemtime($path."/".$title));
                 break;
      case '..': $filename['title']='[..]';
                 $filename['path']=$path;
                 $filename['type']=(empty($type)?$this->filetypes($properties['extension'],$adtype,$hidden):"Folder");
                 $filename['size']="--- ---";
                 $filename['icon']="folder";
                 $filename['link']=$_SERVER['PHP_SELF']."?dir=".base64_encode(realpath($path.'/'.$title));
                 $filename['update']=date("F dS, Y - H:i",filemtime($path."/".$title));
                 break;
      default:   $filename['title']=$title;
                 $filename['path']=$path;
                 if (empty($type))
                 { $filename['type']=(empty($type)?$this->filetypes($properties['extension'],$adtype,$hidden):"Folder");
                   $filename['size']=$this->bytesize(filesize($path."/".$title));
                   $filename['icon']="filename";
                   $filename['link']=realpath($path.'/'.$filename['title']);
                   $filename['update']=date("F dS, Y - H:i",filemtime($path."/".$title));
                 }
                 else
                 { $filename['type']=(empty($type)?$this->filetypes($properties['extension'],$adtype,$hidden):"Folder");
                   $filename['size']=$this->bytesize(filesize($path."/".$title));
                   $filename['icon']="folder";
                   $filename['link']=$_SERVER['PHP_SELF']."?dir=".base64_encode(realpath($path.'/'.$filename['title']));
                   $filename['update']=date("F dS, Y - H:i",filemtime($path."/".$title));
                 }
                 break;
    }
    $templates->layout_row($filename);
  }

  function file_browser($interface,$template,$chrooted=false,$adtype=true)
  { 
  
  
  if(is_admin() == false){
	error('Must be admin');  
	exit();
  }
  
  
  foreach(array_map('chr',range(ord('a'),ord('z'))) as $letter) $letters[]=$letter;
    $scans=array('folders'=>array(),'files'=>array());
    if (!is_dir($interface)) $this->error('ERR01');
    if (!include($interface.'/template-'.$template.'.php')) $this->error('ERR02');
    $templates=new file_browser_templates($interface,$template,$chrooted,$adtype);
    $templates->interface=$interface;
    $templates->layout_header();
    if (empty($select) && !empty($_GET['select'])) $select=$_GET['select']; else $select=null;
    if (empty($directory) && !empty($_GET['dir'])) $directory=base64_decode($_GET['dir']);
    if (empty($chrooted) OR !is_dir(realpath($chrooted))) $chrooted=realpath('./'); else $chrooted=realpath($chrooted);
    if (empty($directory)) $directory=$chrooted;
    $directory=realpath($directory);
    if (substr($directory,0,strlen($chrooted))!=$chrooted) $directory=$chrooted;
    if (!empty($select)) $filter=true; else $filter=false;
    if (version_compare("5.0",phpversion())<0) $scan=scandir($directory);
    else { $scanning=opendir($directory); while (false!==($filename=readdir($scanning))) $scan[]=$filename; }
    foreach($scan as $scanned)
    { if (is_dir($directory."/".trim($scanned))) $inarray="folders"; else $inarray="files";
      switch($select)
      { case null: $scans[$inarray][]=trim($scanned); break;
        case "numbers": if (!in_array(strtolower(substr(trim($scanned),0,1)),$letters)) $scans[$inarray][]=trim($scanned); break;
        default: if (strtolower(substr(trim($scanned),0,1))==strtolower($select)) $scans[$inarray][]=trim($scanned); break;
      }
    }
    $templates->layout_letters($chrooted,$directory,$select);
    $templates->layout_headings();
    if (count($scans['folders'])+count($scans['files'])==0) $templates->layout_nofiles($filter);
    if ($this->displaydot==false) array_shift($scans['folders']);
    foreach($scans['folders'] as $title) $this->row($templates,true,$directory,$title,$adtype);
    foreach($scans['files']   as $title) $this->row($templates,false,$directory,$title,$adtype);
    $templates->layout_footer($directory,$select,$this->version);
  }
}

?>