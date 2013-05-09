<meta name="robots" content="noindex">
  
<meta name="googlebot" content="noindex">
<?php 
error_reporting(0);

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
		 $st_c1 = json_decode($st_c1, true);
		 $fn = basename($sti);
		 $fn = str_replace('.txt','',$fn);
		  $ips[$fn] = $st_c1 ;
		  $all++;
	 }
	 
 }
?>

<h1>All update checks: <? print $all ?></h1>
<hr />
<table width=" 100%" border="0">
  <tr>
    <th scope="col">IP</th>
    <th scope="col">Site</th>
    <th scope="col">Version</th>
    <th scope="col">Count</th>
    <!--<th scope="col">themes count</th>
    <th scope="col">modules count</th>
    <th scope="col">elements count</th>--> 
  </tr>
  <? if(!empty($ips)): ?>
  <? foreach($ips as $k => $item): ?>
  <tr>
    <td><img src="http://api.hostip.info/flag.php?ip=<? print $k ?>" alt="" width="20" /><? print $k ?></td>
    <td><? print($item['request']['site_url']) ?></td>
    <td><? print($item['request']['mw_version']) ?></td>
    <td><? print($item['count']) ?></td>
    <!--    <td><? print count($item['request']['modules']) ?></td>
    <td><? print count($item['request']['modules']) ?></td>
    <td><? print count($item['request']['templates']) ?></td>--> 
  </tr>
  <? endforeach; ?>
  <? endif; ?>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
