<?php
if(!isset($_GET['1'])){
	
	?> 
    <iframe src="http://www.statsmix.com/d/985b178dc36a67ab08fa" frameborder="0" width="1000" height="3000" ></iframe>
    <?php
	exit('');
}
$uip = $_SERVER['REMOTE_ADDR'];

$stats = dirname(__FILE__).DIRECTORY_SEPARATOR.'db'.DIRECTORY_SEPARATOR;
if(!is_dir($stats)){
	mkdir($stats);
}

$stats = $stats.'stats'.DIRECTORY_SEPARATOR;
if(!is_dir($stats)){
	mkdir($stats);
}

 $all = 0;
 $ips = array();
$st = glob($stats.'*');
 
 if(!empty($st)){
	 foreach($st as $sti){
		 $st_c1 = file_get_contents($sti);
		 $fn = basename($sti);
		 $fn = str_replace('.txt','',$fn);
		  $ips[$fn] = $st_c1 ;
		  $all =  $all+$st_c1;
	 }
	 
 }
?>
<meta name="robots" content="noindex">
  
<meta name="googlebot" content="noindex">
<h1>All downloads: <? print $all ?></h1>
<hr />

<table width=" 100%" border="0">
  <tr valign="top">
    <td valign="top"><? if(!empty($ips)): ?>
<ol>
<? foreach($ips as $k => $item): ?>
<li> <img src="http://api.hostip.info/flag.php?ip=<? print $k ?>" alt="" width="20" /> - <? print $k ?> - <? print $item ?></li>
<? endforeach; ?>
</ol>
<? endif; ?></td>
    <td><iframe src="http://api.microweber.net/service/update/stats.php" height="1500" width="450" frameborder="0"></iframe></td>
  </tr>
</table>






