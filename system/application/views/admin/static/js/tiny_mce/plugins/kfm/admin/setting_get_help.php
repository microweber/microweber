<?php
require_once('initialise.php');
$sn=$_REQUEST['name'];
$help=file_get_contents('http://kfmdoc.companytools.nl/kfm_setting_help.php?name='.$sn);
//echo $help;
//$help = implode('', explode("\n", $help));
$help = preg_replace("/[\n\r]/", '', $help);
?>
$('<div title="<?php echo $sn;?>"><?php echo $help ;?></div>').dialog();
