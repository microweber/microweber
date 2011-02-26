<?php 
//10 has more priority than 1
function error($msg, $file="", $line="", $priority=5) {
	global $config,$abs;
	
	if($config['mode'] == 'd' or $config['mode'] == 't') {
		print <<<END
<link href="${abs}css/error.css" type="text/css" rel="stylesheet" />
<div class="error-message priority$priority">
<h1>Error!</h1>
<div id="message">$msg</div><br />
END;

		if($file and $line) {
			$line = $line - 1;
			print "In file '$file' at line $line..<br /><pre>";
			
			//Get the 5 lines surronding the error lines - before and after
			$lines = explode("\n",file_get_contents($file));
			for($i=$line-5; $i<$line+5; $i++) {
				if($i == $line) print '<span class="error-line">';
				print "\n<span class='line-number'>$i)</span> ";
				print str_replace(
					array('<',"\t"),
					array('&lt;','  '),
					$lines[$i]
				);//Trim it?
				if($i == $line) print '</span>';
			}
			print '</pre>';
		}
		print '</div>';
		exit();
	} else {
		if($priority >= 10) die($msg);
	}
}

function showMessage($message, $url="?", $status="success",$id=0) {
	//If it is an ajax request, just print the data
	if(isset($_REQUEST['ajax'])) {
		$success = '';
		$error = '';
		$insert_id = '';

		if($status == 'success') $success = addslashes($message);
		if($status == 'error') $error = $message;
		if($id) $insert_id = ',"id":'.$id;

		print '{"success":"'.$success.'","error":"'.$error.'"'.$insert_id.'}';
	} else {
		$url = str_replace('&amp;', '&', getLink($url, array($status=>$message), true));
		header("Location:$url");
	}
	exit;
}

/**
 * Read the plugin folder and put all the plugins found there in the dropdown menu
 */
function loadPlugins() {
	global $config;
	
	$plugins = array();
	// Open plugin directory, and proceed to read its contents
	$dir = joinPath($config['site_folder'],'plugins');
	$files = ls("*", $dir, false, array('return_folders'));
	foreach($files as $file) {
		if($file == 'CVS' . DIRECTORY_SEPARATOR || $file == '.' || $file =='..' || $file == 'api' . DIRECTORY_SEPARATOR  || $file == '.svn' . DIRECTORY_SEPARATOR) continue;
		$plugins[] = substr($file, 0, -1); //Remove the trailing '/'
	}

	//Show the dropdown menu only if there are plugins
	if(count($plugins)) {
		print '<li class="dropdown"><a href="'.joinPath($config['site_relative_path'],'plugins/').'" class="plugin with-icon">Plugins</a>';

		print "\n<ul class='menu-with-icon plugins'>\n";
		foreach($plugins as $plug) {
			print '<li><a href="'.joinPath($config['site_absolute_path'],'plugins/',"$plug/").'">' . format($plug) . '</a></li>'."\n";
		}
		print '</ul></li>';
	}
}

function lsEssential($dir) {
	$files = array();
	if (is_dir($dir)) {
		if ($dh = opendir($dir)) {
			while (($file = readdir($dh)) !== false) {
				//Skip over the folders if we don't need them.
				if($file == 'CVS' || $file == '.' || $file =='..') continue;
				$files[] = $file;
			}
			closedir($dh);
		}
	}
	return $files;
}

function findProject($text) {
	$text = strtolower($text);
	global $projects;
	
	$project_id = 0;
	$biggest_count = 0;
	foreach($projects as $id=>$name) {
		$count = substr_count($text,strtolower($name));
		if($count > $biggest_count) {
			$project_id = $id;
			$biggest_count = $count;
		}
	}
	return $project_id;
}
