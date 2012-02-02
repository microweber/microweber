<?php	
	session_start();
	switch ($_REQUEST['employeeId']) {
		case '1':
			$employee = array(
				"employeeId"=>1,
				"firstName"=>"Joe",
				"lastName"=>"Smith",
				"department"=>"Management",
				"title"=>"CEO",
				"telephone"=>"240-555-1287"
			);
			if (true == $_SESSION['isAdmin']) {
				$employee['homeAddress'] = array(
					"street"=>"123 Bluebird Ct.",
					"city"=>"Columbia",
					"state"=>"MD"
				);
			}
			break;
		case '2':
			$employee = array(
				"employeeId"=>2,
				"firstName"=>"George",
				"lastName"=>"Loram",
				"department"=>"Sales",
				"title"=>"VP of Sales",
				"telephone"=>"240-555-1287"
			);
			if (true == $_SESSION['isAdmin']) {
				$employee['homeAddress'] = array(
					"street"=>"283 N. Market St.",
					"city"=>"Frederick",
					"state"=>"MD"
				);
			}
			break;
		case '3':
			$employee = array(
				"employeeId"=>3,
				"firstName"=>"Sally",
				"lastName"=>"Beogagi",
				"department"=>"Human Resources",
				"title"=>"Senior Hiring Agent",
				"telephone"=>"443-555-1220"
			);
			if (true == $_SESSION['isAdmin']) {
				$employee['homeAddress'] = array(
					"street"=>"8923 Redwood St.",
					"city"=>"Ellicott City",
					"state"=>"MD"
				);
			}
			break;
		case '4':
			$employee = array(
				"employeeId"=>4,
				"firstName"=>"Billy",
				"lastName"=>"Diarmaid",
				"department"=>"Human Resources",
				"title"=>"Hiring Agent",
				"telephone"=>"443-555-2890"
			);
			if (true == $_SESSION['isAdmin']) {
				$employee['homeAddress'] = array(
					"street"=>"893 Madison St.",
					"city"=>"Mt. Airy",
					"state"=>"MD"
				);
			}
			break;
		case '5':
			$employee = array(
				"employeeId"=>5,
				"firstName"=>"Bruno",
				"lastName"=>"Domingos",
				"department"=>"Technology",
				"title"=>"CTO",
				"telephone"=>"443-555-2890"
			);
			if (true == $_SESSION['isAdmin']) {
				$employee['homeAddress'] = array(
					"street"=>"123 5th St.",
					"city"=>"Washington",
					"state"=>"DC"
				);
			}
			break;		
	}
	echo json_encode($employee);
?>
