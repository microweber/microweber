<?php
include('../common.php');

if(isset($_REQUEST['action']) and ($_REQUEST['action'] == t('Apply'))) {
	if (isset($_REQUEST['theme'])) {
		if($sql->getAssoc("SELECT value From ${config['db_prefix']}Setting WHERE name='Theme' AND user_id='$_SESSION[user]'")) {
			$sql->execQuery("UPDATE ${config['db_prefix']}Setting SET value='$_REQUEST[theme]' WHERE name='Theme' AND user_id='$_SESSION[user]'");
		} else { //User don't have a theme - create new one
			$values = array('value'=>$_REQUEST['theme'],'name'=>'Theme','user_id'=>$_SESSION['user']);
			$sql->insertFields($config['db_prefix'].'Setting',array('value','name','user_id'),$values);
		}
		$theme = $_REQUEST['theme'];
	}
	
	if (isset($_REQUEST['locale'])) {
		if($sql->getAssoc("SELECT value From ${config['db_prefix']}Setting WHERE name='Locale' AND user_id='$_SESSION[user]'")) {
			$sql->execQuery("UPDATE ${config['db_prefix']}Setting SET value='$_REQUEST[locale]' WHERE name='Locale' AND user_id='$_SESSION[user]'");
		} else { //User don't have a 'locale' option - create new one
			$values = array('value'=>$_REQUEST['locale'],'name'=>'Locale','user_id'=>$_SESSION['user']);
			$sql->insertFields($config['db_prefix'].'Setting',array('value','name','user_id'),$values);
		}
		// get Misc before changes
		$oldmisc = t('Misc');
		unset($locales);
		$locale = $_REQUEST['locale'];
		if(isset($locale) and $locale and $locale!='en_EN') {
			include(joinPath($config['site_folder'], 'includes', 'locale', $locale . '.php'));
		}
		$newmisc = t('Misc');
		// if changes in locales, changes data base
		if ($oldmisc != $newmisc) {
			// if user has not rename default project
			reset($projects);
			if (current($projects) == $oldmisc) {
				// changes database
				//$Project->edit(key($projects), $newmisc);
				$sql->execQuery("UPDATE ${config['db_prefix']}Project SET name='$newmisc' WHERE id='".key($projects)."'");
				// changes php variables
				// how to ?
				// $projects[key($projects)] = $newmisc;
			}
		}
	}
}

//Find all the folders that can act as themes.
$all_themes = array();
$dir = joinPath($config['site_folder'],'images','themes');
$themes = ls("*", $dir, false, array('return_folders'));
foreach($themes as $thm) {
	if(file_exists($thm . 'theme.css')) { //Use the folder as the theme only if the theme.css file is set.
		$thm = str_replace($dir.DIRECTORY_SEPARATOR,'',$thm);
		$thm = substr($thm, 0, -1); //Remove the trailing '/'
		$all_themes[$thm] = format($thm);
	}
}

$all_locales = array(
	'en_EN'	=> t('English'),
	'fr_FR'	=> t('French'),
	'es_AR'	=> t('Spanish'),
	'ru_RU'	=> t('Russian'),
);

render();