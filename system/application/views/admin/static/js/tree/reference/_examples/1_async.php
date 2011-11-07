<?
	// Make sure nothing is cached
	header("Cache-Control: must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	header("Expires: ".gmdate("D, d M Y H:i:s", mktime(date("H")-2, date("i"), date("s"), date("m"), date("d"), date("Y")))." GMT");
	header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");

	// So that the loading indicator is visible
	sleep(1);

	// The id of the node being opened
	$id = $_REQUEST["id"];
	//echo $id;
	if($id == "0") {
		echo '['."\n";
		echo "\t".'{ attributes: { id : "pjson_1" }, state: "closed", data: "Root node 1" },'."\n";
		echo "\t".'{ attributes: { id : "pjson_5" }, data: "Root node 2" }'."\n";
		echo ']'."\n";
	}
	else {
		echo '['."\n";
		echo "\t".'{ attributes: { id : "pjson_2" }, data: { title : "Custom icon", icon : "../media/images/ok.png" } },'."\n";
		echo "\t".'{ attributes: { id : "pjson_3" }, data: "Child node 2" },'."\n";
		echo "\t".'{ attributes: { id : "pjson_4" }, data: "Some other child node" }'."\n";
		echo ']'."\n";
	}
	exit();
?>