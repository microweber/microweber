<? 



$numia_api = new \accounting\numia\api();
$contoller_action = false;
$sess_numia_token = session_get('numia_token');
if($sess_numia_token  != false){
	$contoller_action = 'dashboard';
}
if(!isset($_POST['numia_register']) and isset($_POST['numia_username']) and isset($_POST['numia_password']) ){
$user = $_POST['numia_username'];
$pass = $_POST['numia_password'];
$user = db_escape_string($user);
$pass = db_escape_string($pass);
//GETTING OF ACCESS TOKEN//
	if($user != '' and $user != ''){
		$params = array();
		$params['email'] =$user;
		$params['password'] = $pass;
		$result = $numia_api -> call('user_login', $params);
		if(isset($result['api_token'])){
			$sess_numia_token = $result['api_token'];
			 session_set('numia_token',$result['api_token']);
			
			 if(isset($result['company_id'])){
			 session_set('numia_company_id',$result['company_id']);
			 }
		}
	}
}  else if(isset($_POST['numia_register']) and isset($_POST['numia_username']) and isset($_POST['numia_password']) ){
$user = $_POST['numia_username'];
$pass = $_POST['numia_password'];
$user = db_escape_string($user);
$pass = db_escape_string($pass);
$first_name = '';
$last_name = '';
$company = '';
if(isset($_POST['first_name'])){
	$first_name = db_escape_string($_POST['first_name']);
}
if(isset($_POST['company'])){
	$company = db_escape_string($_POST['company']);
}
if(isset($_POST['last_name'])){
	$last_name = db_escape_string($_POST['last_name']);
}





//GETTING OF ACCESS TOKEN//
	if($user != '' and $user != ''){
 
 $params = array(
	 "company"				=> $company,
	 "first_name"				=> $first_name,
	 "last_name"				=> $last_name,
	"email"				=> $user,
	"password"			=> $pass,
	 
);

// PARAM : company
// This accepts numeric( company id ) or string ( company name ) : 
// if string company will be register together with the user( no access token required ), 
// if numeric the user will be register to the company( requires access token ).

$result = $numia_api -> call( 'user_register', $params );
 
		if(isset($result['error'])){
			mw_var('numia_error', $result['error']);
			  $contoller_action = 'register';
		} else {
			
			
			
		 
		
			if(isset($result['api_token'])){
				$sess_numia_token = $result['api_token'];
				 session_set('numia_token',$result['api_token']);
				
				 if(isset($result['company_id'])){
				 session_set('numia_company_id',$result['company_id']);
				 }
				 
				 mw_var('numia_success_msg', "You have been successfully registered and logged in.");
			  $contoller_action = 'dashboard';
			  
			  
			}
		
		
		
		
		}
 
		
	}
} else if(isset($_POST['numia_logout'])){
	$sess_numia_token =false;
	  $contoller_action = 'login';
	  session_set('numia_token',false);
}else if(isset($_GET['register'])){
	$sess_numia_token =false;
	  session_set('numia_token',false);
	  $contoller_action = 'register';
}








switch($contoller_action){
	case 'dashboard':
	 print '<module type="accounting/dashboard"  id="accounting_dashboard" />'	;
	break;
	
		case 'register':
	 print '<module type="accounting/register"  id="accounting_register" />'	;
	break;
	

	 
		case 'login':
	default:
	print '<module type="accounting/login" id="accounting_login" />'	;
	break;	
}
 



/*
print('<hr>');
$params = array();
$params['email'] = 'login@email';
$params['password'] = 'loginpass';
$result = $numia_api -> call('user_login', $params);
print($result);

print('<hr>');
$params = array();
$result = $numia_api -> call('customer_list', $params);
print($result);

print('<hr>');
$params = array();

$params['invoice_number'] = '123';
$params['currency'] = 'USD';

$params['customer_name'] = 'New customer';
$params['email'] = 'customer@example.com';
$params['phone'] = '123-456-54';

$params['country'] = 'Bulgaria';
$params['city'] = 'Sofia';
$params['state'] = '';
$params['zip'] = '1000';

$params['address'] = 'Pimen Zografski 10';
$params['products'] = array();
$params['products'][] = array('title' => 'T-Shirt', 'amount' => '5', 'quantity' => '3');
$params['products'][] = array('title' => 'Notebook', 'amount' => '1500', 'quantity' => '1');

$result = $numia_api -> call('create_invoice', $params);
print($result);

print('<hr>');
$params = array();
$params['invoice_number'] = '123';
$result = $numia_api -> call('get_invoice', $params);
print($result);


print('<hr>');
$params = array();
$params['keyword'] = '123';
$result = $numia_api -> call('invoice_list', $params);
print($result);



print('<hr>');
$params = array();
$params['invoice_number'] = '123';
$params['email'] = 'customer@example.com';
$params['email_content'] = 'HTML STRING';


$result = $numia_api -> call('send_invoice', $params);
print($result);
*/


 