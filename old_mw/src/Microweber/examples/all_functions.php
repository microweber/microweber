<?php
exit('To run this example remove line ' . __LINE__ . ' in file ' . basename(__FILE__));

require_once ('../bootstrap.php');


//get stuff from db
$get_params = array('table'=>'content');
//$data = get($get_params);




$segs =url_segment(1);
//var_dump($segs);

$url_string = url_string();
//var_dump($url_string);

$url_param = url_param('my_param');
//var_dump($url_param);

$login_data = array();
$login_data['username'] = 'boris';
$login_data['password'] = '123456';
//$login = user_login($login_data);
//d($login);

$register_user = array();
$register_user['username'] = 'username';
$register_user['password'] = '123456';
//$register = user_register($register_user);

//d($register );

//set a session variable
session_set('something', 'some_value');

//get a session variable
$something = session_get('something');
var_dump($something);


//delete a session variable
//session_del('something');

//destroy the whole session
//session_end();