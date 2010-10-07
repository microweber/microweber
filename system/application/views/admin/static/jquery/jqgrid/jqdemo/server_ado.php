<?php
include("../ap/phps/adodb/adodb.inc.php");
include("dbconfig.php");
$examp = $_GET["q"]; //example number
$page = $_GET["page"];
$recs = $_GET["rows"];
$sidx = $_GET["sidx"];
$sord = $_GET["sord"];

if(!$sidx) $sidx =1;
$db = ADONewConnection($dbdriver);
$db->Connect($dbhost, $dbuser,$dbpassword,$database);
switch ($examp) {
    case 1:
        $SQL = "SELECT a.id, a.invdate, b.name, a.amount,a.tax,a.total,a.note FROM invheader a, clients b WHERE a.client_id=b.client_id ORDER BY ".$sidx." ".$sord;
        $stmt = $db->Prepare($SQL);
        $rs = $db->PageExecute($stmt, $recs ,$page);
        if ($rs) {
			rs2xml( $rs, $page, true);
		}
        break;
    case 2:
        break;
}

function rs2xml( &$rs, $page, $id=false)
{
	if ( stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml") ) {
  		header("Content-type: application/xhtml+xml;charset=utf-8"); } else {
  		header("Content-type: text/xml;charset=utf-8");
	}
  $et = ">";
  echo "<?xml version='1.0' encoding='utf-8'?$et\n";
  if( $page > $rs->LastPageNo())$page=$rs->LastPageNo();
  echo "<rows>";
	echo "<page>".$page."</page>";
	echo "<total>".$rs->LastPageNo()."</total>";
	echo "<records>".$rs->MaxRecordCount()."</records>";
  $rs->MoveFirst();    
  $fldcnt = $rs->FieldCount();
  if( $id ) $start = 0; else $start=1;
  while (!$rs->EOF) {
    echo "<row id='",$rs->fields[0],"'>";
    for ($i=$start; $i<$fldcnt; $i++) 
    {
        $v = $rs->fields[$i];
        $fld = $rs->FetchField($i);
        $type = $rs->MetaType($fld->type);
        switch($type) {
        case 'N':
            echo "<cell>",number_format($v,2,'.',' '),'</cell>';
            break;
        case 'I':
            echo "<cell>",$v,'</cell>';
            break;
        case 'D':
            echo "<cell>",$rs->UserDate($v,'Y-m-d'),"</cell>";
            break;
        case 'T':
            echo "<cell>",$rs->UserTimeStamp($v,$fmtd.' '.$fmtt),"</cell>";
            break;
        default :
            echo "<cell><![CDATA[",$v,"]]></cell>";
        }
    }
    $rs->MoveNext();
    echo '</row>';
  }
  echo '</rows>';  
}

?>