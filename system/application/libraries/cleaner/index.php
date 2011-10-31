<?
	if(count($_POST) && trim($_POST[ocode])){
		if (get_magic_quotes_gpc()) {
			$ocode = stripslashes($_POST[ocode]);
		}else{
			$ocode = $_POST[ocode];
		}
		
		set_time_limit(0);

		include('class.cleaner.php');
		
		$cleanerObj = new codeCleaner;
		
		if($_POST[tabspace]=="Y") $cleanerObj->tabSpaces = true;
		else	$cleanerObj->tabSpaces = false;
		
		if($_POST[commfunc]=="Y") $cleanerObj->functionComm = true;
		else	$cleanerObj->functionComm = false;
		
		if($_POST[commclass]=="Y") $cleanerObj->classComm = true;
		else	$cleanerObj->classComm = false;
		
		if($_POST[altcurly]=="Y") $cleanerObj->standardCurly = true;
		else	$cleanerObj->standardCurly = false;
		
		$newcontent = $cleanerObj->cleanFile("", $ocode);		
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>PHP CODE CLEANER 0.7</title>
<link href="styles.css" rel="stylesheet" type="text/css" />
</head>
<body>
<form id="form1" name="form1" method="post" action="">
  <table width="100%" border="0" cellspacing="3" cellpadding="3">
    <tr>
      <td colspan="3" align="center" bgcolor="#0099FF"><h1>PHP Code Cleaner<br />
      </h1></td>
    </tr>
    <tr>
      <td width="9%" valign="top">Your Code: </td>
      <td colspan="2" valign="top"><textarea name="ocode" style="width:95%;height:300px" wrap="off"><? echo htmlentities($ocode) ?></textarea></td>
    </tr>
    <tr>
      <td></td>
      <td colspan="2"><!--
	  <input type="checkbox" name="removeempty" value="Y"  <? if($_POST[removeempty]=="Y") echo "checked"; ?>/>
        Remove empty lines &nbsp;&nbsp;&nbsp;-->
	  <input type="checkbox" name="tabspace" value="Y"  <? if($_POST[tabspace]=="Y") echo "checked"; ?>/>
        Use 4 spaces instead of Tab &nbsp;&nbsp;&nbsp;
	  	<input type="checkbox" name="commclass" value="Y"  <? if($_POST[commclass]=="Y") echo "checked"; ?>/>
        Add Comments on Class Start&nbsp;&nbsp;&nbsp;
		<input type="checkbox" name="commfunc" value="Y" <? if($_POST[commfunc]=="Y") echo "checked"; ?>/>
        Add Comments on Function Start &nbsp;&nbsp;&nbsp;
        <input type="checkbox" name="altcurly" value="Y"  <? if($_POST[altcurly]=="Y") echo "checked"; ?>/>
        Use Alternate Curly Brackets Style</td>
    </tr>
    <tr>
      <td></td>
      <td width="58%"><input type="submit" name="Submit" value="Format My Code" /></td>
      <td width="33%">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
      <td valign="top"><? if($newcontent){ echo "Formatted Code" ; } ?></td>
      <td colspan="2"><?
	if($newcontent){
		echo "<textarea style='width:95%;height:400px'  wrap='off'>".htmlentities($newcontent)."</textarea>";
	}
?>
      </td>
    </tr>
    <tr>
      <td colspan="3" bgcolor="#0099FF"><span class="style2"></span></td>
    </tr>
  </table>
</form>
</body>
</html>
