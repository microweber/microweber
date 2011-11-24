<?
// Configure database
$server		= "localhost";
$db_user	= "********";
$db_pass	= "********";
$only_db	= "********";

// Include required classes
require_once("_inc/database.php");
require_once("_inc/class.tree.php");

// Create a new DB instance
$db = new DB;
// Create a new tree instance
$tree = new tree( array( "structure" => array("id" => "id", "parent_id" => "parent_id", "position" => "position") ) );

$languages = array();
$db->query("SELECT * FROM languages");
while($db->nextr()) { $languages[$db->f(0)] = array($db->f(1),$db->f(2)); }

// SERVER SIDE PART
if(isset($_REQUEST["server"])) {
	// Make sure nothing is cached
	header("Cache-Control: must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	header("Expires: ".gmdate("D, d M Y H:i:s", mktime(date("H")-2, date("i"), date("s"), date("m"), date("d"), date("Y")))." GMT");
	header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");

	
	switch($_REQUEST["type"]) {
		 case "list":
			$id = (int)str_replace("node_","",$_REQUEST["id"]);
			$db->query("SELECT s.id, ( SELECT COUNT(*) FROM structure WHERE parent_id = s.id ) AS children, c.language, c.name FROM structure s LEFT JOIN content c ON c.id = s.id WHERE s.parent_id = ".$id." ORDER BY position");
			$data = array();
			$children = array();
			while($db->nextr()) {
				$children[$db->f("id")] = (int)$db->f("children");
				$data[$db->f("id")][$db->f("language")] = $db->f("name");
			}
			echo "[\n";
			$i = 0;
			foreach($data as $k => $v) {
				echo "{\n";
				echo "\tattributes: {\n";
				echo "\t\tid :  'node_".$k."'\n";
				echo "\t},\n";
				if($children[$k]) echo "\tstate: 'closed', \n";
				echo "\tdata: {\n";
				$kf = 0;
				foreach($v as $lang => $name) {
					if($kf > 0)	echo ",\n";
					else		echo "\n";
					$kf ++;
					echo "\t\t'".$languages[$lang][0]."' : { title : '".$name."' }"; 
				}
				echo "\n";
				echo "\t}\n";
				echo "}";
				if(++$i < count($data)) echo ",";
				echo "\n";
			}
			echo "\n]";
			break;
		case "delete":
			$id = (int)str_replace("node_","",$_REQUEST["id"]);
			$tree->remove($id);
			$db->query("DELETE FROM content WHERE id = ".$id);
			echo "OK";
			break;

		case "create":
		case "move":
			$id		= (int)str_replace("node_","",$_REQUEST["id"]);
			$ref_id	= (int)str_replace("node_","",$_REQUEST["ref_id"]);
			$type	= $_REQUEST["move_type"];
			$result = $tree->move($id,$ref_id,$type);
			if($id == 0) {
				foreach($languages as $k => $lang) {
					$db->query("INSERT INTO content (id,language,name,data) VALUES(".$result.",".$k.",'New folder','')");
				}
				echo "node_".$result;
			}
			break;
		case "rename":
			$sql = "UPDATE content SET name = '".addslashes($_REQUEST["data"])."' WHERE id = ".(int)str_replace("node_","",$_REQUEST["id"])." AND language = ".(int)$_REQUEST["lang"];
			$db->query($sql);
			echo $sql;
			break;


		case "loadfile":
			$db->query("SELECT * FROM content WHERE id = ".(int)str_replace("node_","",$_REQUEST["id"])." AND language = ".(int)$_REQUEST["lang"]);
			$db->nextr();
			echo $db->f("data");
			break;
		case "savefile":
			$sql = "UPDATE content SET data = '".addslashes($_REQUEST["data"])."' WHERE id = ".(int)str_replace("node_","",$_REQUEST["id"])." AND language = ".(int)$_REQUEST["lang"];
			$db->query($sql);
			echo $sql;
			break;
	}
	exit();
}
?>