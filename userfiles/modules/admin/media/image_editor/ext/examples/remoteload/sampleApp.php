<?php
	session_start();
	$subitems = array();
	$subitems[] = array(
		"xtype"=>"panel",
		"region"=>"north",
		"border"=>false,
		"bodyStyle"=>"padding: 5px; background-color: #cfcfcf; font-family: Arial; font-weight: bold;",
		"html"=>"Employee Database",
		"height"=>32
	);
	
	$subitems[] = array(
		"xtype"=>"panel",
		"region"=>"south",
		"height"=>200,
		"collapsed"=>true,
		"collapsible"=>true,
		"title"=>"Purpose",
		"bodyStyle"=>"padding: 5px;",
		"html"=>"This example demonstrates how to remotely load component configurations with a sample extension <a href='ComponentLoader.js'>Ext.ux.ComponentLoader</a>. By changing the session isAdmin flag you can see how different component configurations can be sent over the wire to improve security. "
	);	

	$subitems[] = array(
		"id"=>"employeeDetailsCt",
        "region"=>"east",
        "title"=>"Employee Details",
        "collapsible"=>true,
        "split"=>true,
        "width"=>275,
        "minSize"=>175,
        "maxSize"=>400,
        "layout"=>"fit",
        "margins"=>"0 5 0 0"		
	);

	$subitems[] = array(
		"region"=>"center",
		"id"=>"employeeGrid",
		"xtype"=>"employeegrid",
		"store"=>"employeeStore"
	);	

	
	$components = array();
	$components[] = array(
		"layout"=>"border",
		"xtype"=>"viewport",
		"items"=>$subitems
	);
	$tabs = array();
	$tabs[] = array(
		"xtype"=>"employeedetails",
		"title"=>"Information",
		"url"=>"loadEmployeeInfo.php"
	);
	if (true == $_SESSION['isAdmin']) {
		 $tabs[] = array(
			"title"=>"Edit",
			"xtype"=>"employeepropertygrid",
			"url"=>"loadEmployeePropGrid.php"
		);		
	}	
	
	$components[] = array(
		"xtype"=>"employeedetailstab",	
		"id"=>"employeeDetails",
		"container"=>"employeeDetailsCt",
		"border"=>false,
		"activeTab"=>0,
		"tabPosition"=>"bottom",
		"items"=>$tabs
	);
		
	$jsonPacket = array("components"=>$components, "success"=>true);
	echo json_encode($jsonPacket);	
?>



