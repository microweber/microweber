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
				$employee['street'] = "123 Bluebird Ct.";
				$employee['city'] = "Columbia";
				$employee['state'] = "MD";
				$employee['salary'] = 118000;				
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
				$employee['street'] = "283 N. Market St.";
				$employee['city'] = "Frederick";
				$employee['state'] = "MD";
				$employee['salary'] = 68000;				
			}
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
				$employee['street'] = "8923 Redwood St.";
				$employee['city'] = "Ellicott City";
				$employee['state'] = "MD";
				$employee['salary'] = 72000;				
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
				$employee['street'] = "893 Madison St.";
				$employee['city'] = "Mt. Airy";
				$employee['state'] = "MD";
				$employee['salary'] = 48000;				
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
				$employee['street'] = "123 5th St.";
				$employee['city'] = "Washington";
				$employee['state'] = "DC";
				$employee['salary'] = 92000;				
			}
			break;		
	}
	echo json_encode($employee);
?>
