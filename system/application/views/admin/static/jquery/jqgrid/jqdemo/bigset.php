<?php
ini_set('max_execution_time', 600);
include("dbconfig.php");
// coment the above lines if php 5
//include("JSON.php");
//$json = new Services_JSON();
// end comment
$examp = $_REQUEST["q"]; //query number

$page = $_REQUEST['page']; // get the requested page
$limit = $_REQUEST['rows']; // get how many rows we want to have into the grid
$sidx = $_REQUEST['sidx']; // get index row - i.e. user click to sort
$sord = $_REQUEST['sord']; // get the direction
if(!$sidx) $sidx =1;

if(isset($_GET["nm_mask"]))
	$nm_mask = $_GET['nm_mask'];
else
	$nm_mask = "";
if(isset($_GET["cd_mask"]))
	$cd_mask = $_GET['cd_mask'];
else
	$cd_mask = "";

$where = "WHERE 1=1";
if($nm_mask!='')
	$where.= " AND item LIKE '$nm_mask%'";
if($cd_mask!='')
	$where.= " AND item_cd LIKE '$cd_mask%'";

// connect to the database
$db = mysql_pconnect($dbhost, $dbuser, $dbpassword)
or die("Connection Error: " . mysql_error());

mysql_select_db($database) or die("Error conecting to db.");
//populateDBRandom();
$result = mysql_query("SELECT COUNT(*) AS count FROM items ".$where);
$row = mysql_fetch_array($result,MYSQL_ASSOC);
$count = $row['count'];

if( $count >0 ) {
	$total_pages = ceil($count/$limit);
} else {
	$total_pages = 0;
}
if ($page > $total_pages) $page=$total_pages;
if ($limit<0) $limit = 0;
$start = $limit*$page - $limit; // do not put $limit*($page - 1)
if ($start<0) $start = 0;
$SQL = "SELECT item_id, item, item_cd FROM items ".$where." ORDER BY $sidx $sord LIMIT $start , $limit";
$result = mysql_query( $SQL ) or die("Couldn’t execute query.".mysql_error());
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
	$responce->rows[$i]['id']=$row[item_id];
    $responce->rows[$i]['cell']=array($row[item_id],$row[item],$row[item_cd]);
    $i++;
} 
//echo $json->encode($responce); // coment if php 5
echo json_encode($responce);
mysql_close($db);

function populateDBRandom(){
$filename = getcwd()."/longtext.txt";
$handle = fopen ($filename, "r");
$contents = fread ($handle, filesize ($filename));
$arWords = split(" ",$contents);
//print(count($arWords));
for($i=0;$i<count($arWords);$i++){
	$nm = $arWords[$i];
	$cd = rand(123456,987654);
	$sql = "INsert into items(item,item_cd) Values('".$nm."','".$cd."')";
	mysql_query ($sql);
	if($i==9999)
		break;
}
fclose ($handle);
}

?>
