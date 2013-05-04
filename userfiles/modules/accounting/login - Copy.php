<?php 
$numia_api = new \accounting\numia\api();


//GETTING OF ACCESS TOKEN//
$params = array();
$params['email'] = 'cj_wilson2001@yahoo.com';
$params['password'] = 'test1234';
$result = $numia_api -> call('user_login', $params);

echo "<pre>";
print($result);

print('<hr>');
//SAMPLE TOKEN//
$api_token 	= '0db427c6b57ce54ae4331ea184ed1ba3';
$params = array(
	"api_token" 		=> $api_token,
	"email"				=> 'cj_wilson2001@yahoo.com',
	"password"			=> 'test1234',
	"first_name"		=> 'John Wilson',
	"last_name"			=> 'Chua',
	"company"			=> 'Yinyang' 	
);

// PARAM : company
// This accepts numeric( company id ) or string ( company name ) : 
// if string company will be register together with the user( no access token required ), 
// if numeric the user will be register to the company( requires access token ).

$result = $numia_api -> call( 'user_register', $params );
echo "<pre>";
print($result);

?>

<h2>Login to Numia Accounting</h2>

