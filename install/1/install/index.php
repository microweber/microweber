<?php
session_start();
//error_reporting(0);

$rel = '../';
include('../configuration.php');
if($system_installed) {
	header('Location:'.$rel);
}
$QUERY = array();

function buildInput($name,$title='',$type='text',$data='',$extra='') {
	$title = ($title) ? $title : format($name);
	if($type=='checkbox' and $data) $checked = " checked='checked'";
	if($data) $value = " value='$data'";

	print "<label for='$name'>$title</label><input type='$type' name='$name' id='$name'"
		. $value . $checked . $extra . " /><br />\n";
}

function showMessages($message) {
	global $QUERY;
	if(isset($QUERY[$message]) and $QUERY[$message]) {
		print "<ul id='$message-message'>";
		foreach($QUERY[$message] as $msg) {
			print "<li>$msg</li>\n";
		}
		print "</ul>\n\n";
	}
	$QUERY[$message] = array();
}

//If no step is given, it is the first step.
if(!isset($_REQUEST['step'])) $_REQUEST['step'] = 1;

// The First step is Setting up Database connection
//					   (the '2' is NOT a typo)
if($_REQUEST['step'] == 2) {
	//Save the data to the Session
	if(isset($_REQUEST['host'])) $_SESSION['host'] = $_REQUEST['host'];
	if(isset($_REQUEST['db_user'])) $_SESSION['db_user'] = $_REQUEST['db_user'];
	if(isset($_REQUEST['password'])) $_SESSION['password'] = $_REQUEST['password'];
	if(isset($_REQUEST['database'])) $_SESSION['database'] = $_REQUEST['database'];
	if(isset($_REQUEST['db_prefix'])) $_SESSION['db_prefix'] = $_REQUEST['db_prefix'];
	if(isset($_REQUEST['url'])) $_SESSION['url'] = $_REQUEST['url'];
	if(isset($_REQUEST['locale'])) $_SESSION['locale'] = $_REQUEST['locale'];

	if(mysql_connect($_SESSION['host'],$_SESSION['db_user'],$_SESSION['password'])) { //Try to connect to the DB.
		$QUERY['success'][] = 'Connection to Database server successful';

		if(mysql_select_db($_SESSION['database'])) {//Select the provided database.
			$QUERY['success'][] = "Database '$_SESSION[database]' selected";

		} else {
			$QUERY['error'][] = 'The given database('.$_SESSION['database'].') does not exist. Please povide a valid database.';
			$_REQUEST['step'] = 1;
		}
	} else {
		$QUERY['error'][] = 'Unable to connect to the database. Make sure that the entered details are correct';
		$_REQUEST['step'] = 1;
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html><head>
<title>Nexty : Installation : Step <?=$_REQUEST['step']?></title>
<link href="../css/style.css" rel="stylesheet" type="text/css" />
<link href="style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="header">
<h1 id="logo">Nexty</h1>
</div>
<div id="main">
<h1>Nexty Installation : Step <?=$_REQUEST['step']?></h1>

<?php
$php_version = explode('.', phpversion());
$major_version = intval($php_version[0]);
if($major_version < 5) { ?>
<h3 class="error">Nexty requires PHP 5</h3>
<p>... and you have PHP <?=$major_version?>. There is <a href="http://www.bin-co.com/blog/2007/08/nexty-2-will-use-php-5/">a good reason</a> for moving to PHP 5. If you still want to use PHP <?=$major_version?>, you can use an <a href="http://sourceforge.net/project/downloading.php?group_id=188197&amp;filename=nexty_1.00.a.tar.gz">old version of Nexty</a> but, I recommend that you move your server to PHP 5 :-)</p>

<p>Sorry!</p>

<br />
<?php } else { ?>

<form action="" method="post">
<?php
if($_REQUEST['step'] == 1) {
	print 'Please provide the database connection details...';
	showMessages('success');
	showMessages('error');

	?>
	<fieldset>
	<legend>Database Details</legend>
	<?php
	buildInput('host','Database Host','text',($_REQUEST['host']) ? $_REQUEST['host'] : "localhost");
	buildInput('db_user','Database User','text',($_REQUEST['db_user']) ? $_REQUEST['db_user'] : "root");
	buildInput('password','Database Password','text',($_REQUEST['password']) ? $_REQUEST['password'] : "");
	buildInput('database','Database','text',($_REQUEST['database']) ? $_REQUEST['database'] : "nexty");
	buildInput('db_prefix','Table Prefix','text',($_REQUEST['db_prefix']) ? $_REQUEST['db_prefix'] : "");
	print "</fieldset>";
	buildInput('url','Application URL');
	
	print "<label for='locale'>Language</label><select name='locale' id='locale' />\n";
	print "<option value='en_EN' ".((!$_REQUEST['locale'] || ($_REQUEST['locale']=='en_EN')) ? "selected='selected'" : "").">English</option>\n";
	print "<option value='fr_FR'".((!$_REQUEST['locale'] && ($_REQUEST['locale']=='fr_FR')) ? "selected='selected'" : "").">French</option>\n";
	print "</select><br />\n";
?>
<script type="text/javascript">
document.getElementById("url").value = document.location.href.toString().replace(/install\/?/,'');
</script>
<span class="info">The URL to which Nexty is to be installed.</span><br />

<?php
} elseif($_REQUEST['step'] == 2) {
	print 'Populating Database...<br />';
	include('db_install.php');
	showMessages('success');
	showMessages('error');
	
	print 'Creating Configuration File...<br />';

	$parts = parse_url($_SESSION['url']);
	$abs = $parts['path'];
	if($abs[strlen($abs)-1] != '/' and $abs[strlen($abs)-1] != '\\') $abs .= '/';//Insert a '/' at the end if it is not there. 

	//I don't know how to escape the $ charector in heredocs - so I did this...
	$config = '$config';//Heh, Heh ;-)
	$system_installed = '$system_installed';
	$configuration = <<<END
<?php
//Configuration file for Nexty
$system_installed = true;
$config = array(
	'db_host'		=>	'$_SESSION[host]',
	'db_user'		=>	'$_SESSION[db_user]',
	'db_password'	=>	'$_SESSION[password]',
	'db_database'	=>	'$_SESSION[database]',
	'db_prefix'		=>	'$_SESSION[db_prefix]',
	'site_url'			=>	'$_SESSION[url]',
	'site_absolute_path'	=>	'$abs',
	'locale'			=>	'$_SESSION[locale]', // en_EN, fr_FR
	'mode'			=>	'p' //Production mode.
);

END;
	if(is_writable('../configuration.php')) { 
		$OUT = fopen('../configuration.php','w');
		fwrite($OUT,$configuration);
		fclose($OUT);
		$QUERY['success'][] = 'Saved the configuration file. <a href="'.$_REQUEST['url'].'">Go to Nexty</a>';
	} else {
		$QUERY['error'][] = 'Configuration file (configuration.php) is not writable. Please copy the configuration code and enter it into the "configuration.php" file. Then press continue.';
	}

	showMessages('error');
	showMessages('success');
?>
<textarea name="code" rows="15" cols="50"><?=$configuration?></textarea>

<p>After installation, it is recommended that you <strong style="background-color:#ddd;">make the 'configuration.php' file read only and 
remove the 'install' folder for security purposes</strong>. But we all know that you are just going to 
rename the 'install' folder to '1install' or something like that. So I am not even going to 
check for that. Plus, I am lazy ;-).</p>

	<?php
} elseif($_REQUEST['step'] == 3) { ?>

	<p>No, no, no - you got it all wrong.</p>
	
	<p>I said that the configuration file (configuration.php) is not writable. Please copy the configuration code and enter it into the "configuration.php" file <strong>and then</strong> press continue.</p>

	<p>If you had done it, you would not have seen this page. Now <a href="?step=2">go back</a> and do it.</p>
<?php }

if($_REQUEST['step'] < 3) { ?>
<input type="hidden" name="step" value="<?= ($_REQUEST['step'] + 1) ?>" /><br />
<input type="submit" name="action" value="Continue &gt;&gt;" /><br />
<?php } ?>
</form>
<?php } ?>
</div>

<div id="footer"></div>

</body>
</html>