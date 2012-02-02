<?php
// Include the information needed for the connection to
// MySQL data base server. 
include("dbconfig.php");
//since we want to use a JSON data we should include
//encoder and decoder for JSON notation
//If you use a php >= 5 this file is not needed
//include("JSON.php");

// create a JSON service
//$json = new Services_JSON();

// to the url parameter are added 4 parameter
// we shuld get these parameter to construct the needed query
// for the pager

// get the requested page
$page = $_REQUEST['page'];
// get how many rows we want to have into the grid
// rowNum parameter in the grid
$limit = $_REQUEST['rows'];
// get index row - i.e. user click to sort
// at first time sortname parameter - after that the index from colModel
$sidx = $_REQUEST['sidx'];
// sorting order - at first time sortorder
$sord = $_REQUEST['sord']; 

// if we not pass at first time index use the first column for the index
if(!$sidx) $sidx =1;
// connect to the MySQL database server
$db = mysql_connect($dbhost, $dbuser, $dbpassword)
or die("Connection Error: " . mysql_error());

// select the database
mysql_select_db($database) or die("Error conecting to db.");

// calculate the number of rows for the query. We need this to paging the result
$result = mysql_query("SELECT COUNT(*) AS count FROM invheader a, clients b WHERE a.client_id=b.client_id");
$row = mysql_fetch_array($result,MYSQL_ASSOC);
$count = $row['count'];

// calculation of total pages for the query
if( $count >0 ) {
	$total_pages = ceil($count/$limit);
} else {
	$total_pages = 0;
}

// if for some reasons the requested page is greater than the total
// set the requested page to total page
if ($page > $total_pages) $page=$total_pages;

// calculate the starting position of the rows
$start = $limit*$page - $limit; // do not put $limit*($page - 1)
// if for some reasons start position is negative set it to 0
// typical case is that the user type 0 for the requested page
if($start <0) $start = 0;

// the actual query for the grid data
$SQL = "SELECT a.id, a.invdate, b.name, a.amount,a.tax,a.total,a.note FROM invheader a, clients b WHERE a.client_id=b.client_id ORDER BY $sidx $sord LIMIT $start , $limit";
$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());

// constructing a JSON
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
    $responce->rows[$i]['id']=$row[id];
    $responce->rows[$i]['cell']=array($row[id],$row[invdate],$row[name],$row[amount],$row[tax],$row[total],$row[note]);
    $i++;
}
// return the formated data
//echo $json->encode($responce);
echo json_encode($responce);
?>
