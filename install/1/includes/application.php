<?php
$config['site_title']	= "Nexty";
$html = new HTML;

$single_user = $sql->getAssoc("SELECT value,status FROM ${config['db_prefix']}Setting WHERE name='SingleUser'");
if(!isset($_SESSION['user']) or !is_numeric($_SESSION['user']) or $_SESSION['user']<=0) {
	if($single_user['status'] == '1') {
		$QUERY['user_id'] = $_SESSION['user'] = $single_user['value'];
		
	//See if the user have got the 'Remember Me' Option - the username and password will be stored in the cookie
	} elseif(isset($_COOKIE['user'])) {
		$user_info = $sql->getAssoc("SELECT id,name,username,password FROM ${config['db_prefix']}User WHERE username='$_COOKIE[user]'");
		$hash_string = $user_info['password'] . 'Xr' . $user_info['id'] . '@t5';
		if(sha1($hash_string) == $_COOKIE['hash']) {
			$QUERY['user_id'] = $_SESSION['user'] = $user_info['id'];
		}
	}
}

//If the user don't have a valid cookie, redirect to login page.
if(!isset($_SESSION['user']) 
		and basename(dirname($_SERVER['PHP_SELF'])) != 'users' //'users' folder has the login/register/logout pages - so don't redirect
		and basename($_SERVER['PHP_SELF']) != 'about.php' //About page must be accessable without login for SEO reasons
		and $_SERVER['PHP_SELF'] != $config['site_absolute_path'].'index.php'
		and $_SERVER['PHP_SELF'] != $config['site_absolute_path'].'tasks/add.php') {//The Add page has a basic authentication - don't need the session
	header("Location:${rel}users/login.php");
	exit;
}

$user = (isset($_SESSION['user'])) ? $_SESSION['user'] : 0;
$theme = $sql->getOne("SELECT value FROM ${config['db_prefix']}Setting WHERE name='Theme' AND user_id=$user");
if(!$theme) { //If the user has not set a theme, it uses the default theme
	$theme = $sql->getOne("SELECT value FROM ${config['db_prefix']}Setting WHERE name='Theme' AND user_id=0");
}
$locale = $sql->getOne("SELECT value FROM ${config['db_prefix']}Setting WHERE name='Locale' AND user_id=$user");
if(!$locale) { //If the user has not set a locale, it uses the default locale set in the configuration.php file
	if(!isset($config['locale'])) $locale = 'en_EN';
	else $locale = $config['locale'];
}
$lang = reset(explode("_", $locale));
if(!$lang) $lang = 'en';


// User wants a non-english version of the page.
if(isset($locale) and $locale and $locale!='en_EN') {
	include(joinPath($config['site_folder'], 'includes', 'locale', $locale . '.php'));
}

$all_types = array(
	'Immediately'	=> t('Immediately'),
	'Someday/Maybe'	=> t('Someday/Maybe'),
	'Waiting'		=> t('Waiting'),
	'Idea'			=> t('Idea'),
	'Done'			=> t('Done')
);

$pending_projects = array();
$contexts = array();
$projects = array();
if(isset($_SESSION['user']) and is_numeric($_SESSION['user'])) {
	$QUERY['user_id'] = $_SESSION['user'];

	//Get active projects only - projects with tasks in them
	$qry_active_projects = "SELECT Project.id,Project.name FROM ${config['db_prefix']}Project AS Project"
			. " INNER JOIN ${config['db_prefix']}Task AS Task ON Task.project_id=Project.id WHERE Task.type='Immediately' AND Project.user_id=$_SESSION[user]"
			. " GROUP BY Project.id LIMIT 0,10 ";
	$pending_projects = $sql->getById($qry_active_projects);

	//All Contexts for this user
	$contexts = $sql->getById("SELECT id,name FROM ${config['db_prefix']}Context WHERE user_id=$_SESSION[user]");

	//All Projects for this user
	$projects = $sql->getById("SELECT id,name FROM ${config['db_prefix']}Project WHERE user_id='$_SESSION[user]'");

	//All the reminders for TODAY	
	$todays_reminder = $sql->getAll("SELECT id,name,day,description FROM ${config['db_prefix']}Reminder "
			. " WHERE user_id='$_SESSION[user]' AND day=CURDATE()");

	//Get the reminders for the next 10 days as well
	$reminder_alerts = $sql->getAll("SELECT id,name,day FROM ${config['db_prefix']}Reminder "
			. " WHERE user_id='$_SESSION[user]' AND day>CURDATE() AND day<=DATE_ADD(CURDATE(),INTERVAL 10 DAY)");
			
	$due_tommorow_alerts = $sql->getAll("SELECT id,name FROM ${config['db_prefix']}Task "
			. " WHERE user_id='$_SESSION[user]' AND type='Immediately' AND due_on=DATE_ADD(CURDATE(),INTERVAL 1 DAY)");
	$due_today = $sql->getAll("SELECT id,name,description FROM ${config['db_prefix']}Task "
			. " WHERE user_id='$_SESSION[user]' AND type='Immediately' AND due_on<=CURDATE() AND due_on!='0000-00-00 00:00:00'");

	//The manual way of doing what DATEDIFF would have done if we had MySQL 5
	for($i=0; $i<count($reminder_alerts); $i++) {
		$difference = strtotime($reminder_alerts[$i]['day']) - time() + 1; //Find the number of seconds from now to the reminder date
		$reminder_alerts[$i]['until'] = ceil($difference / (60*60*24)) ;  //Find how many days that is
	}
}

if(isset($_REQUEST['layout']) and $_REQUEST['layout'] == 'iframe') {
	$template->layout = 'iframe.php';
}


function summary($text) {
	if(strlen($text) <= 140) return $text; // If the text is less that 140 chars, just return it as it is.
	
	// If its bigger, return just the first sentance. Up until the first '.'. If the first sentance is bigger that 140 chars, just return the first 140 chars.
	return substr(reset(explode(".", $text)), 0, 140);
}
