<?php

/* RECEIVE VALUE */
$nameValue=$_REQUEST['firstname'];
$userValue=$_REQUEST['user'];


$validateError= "This username is already taken";
$validateSuccess= "This username is available";



	/* RETURN VALUE */
	$arrayToJs = array();
	$arrayToJs[0] = array();
	$arrayToJs[1] = array();

if($userValue =="karnius"){		// validate??
	$arrayToJs[0][0] = 'user';
	$arrayToJs[0][1] = true;			// RETURN TRUE
	$arrayToJs[0][2] = "This user is available";
			// RETURN ARRAY WITH success
}else{
	$arrayToJs[0][0] = 'user';
	$arrayToJs[0][1] = false;
	$arrayToJs[0][2] = "This user is already taken";
}

if($nameValue =="duncan"){		// validate??
	$arrayToJs[1][0] = 'firstname';
	$arrayToJs[1][1] = true;			// RETURN TRUE
			// RETURN ARRAY WITH success
}else{
	$arrayToJs[1][0] = 'firstname';
	$arrayToJs[1][1] = false;
	$arrayToJs[1][2] = "This name is already taken";
}




	echo json_encode($arrayToJs);

?>