<h1><?=t('Settings')?></h1>

<h2><?=t('User Settings')?></h2>

<?php if(!$single_user['status']) { ?>
<ul>
<li><a href="../users/profile.php"><?=t('Edit Profile')?></a></li>
<li><a href="../users/profile.php"><?=t('Change Password')?></a></li>
</ul>
<?php } ?>

<?=t('Mode')?> : <a href="user.php"><?php if($single_user['status']) { ?><strong><?=t('Single User Mode')?></strong>
<?php } else { ?><strong><?=t('Multi User Mode')?></strong>
<?php } ?></a>

<h2><?=t('User Interface')?></h2>

<form action="" method="post">
<label for="theme" style="width:80px;"><?=t('Icon Theme')?></label> :
<?php
$html->buildDropdownArray($all_themes,'theme',$theme,array('style'=>"float:none;")); ?> &nbsp;<br/>
<label for="theme" style="width:80px;"><?=t('Langage')?></label> :
<?php 
$html->buildDropdownArray($all_locales,'locale',$locale,array('style'=>"float:none;")); ?> &nbsp;
<input type="submit" name="action" value=<?=t('Apply')?> />

</form>

<?php
//If user is on a Linux/Unix system, give him the option to create the script
$user_agent = strtolower($_SERVER["HTTP_USER_AGENT"]);
if((strpos($user_agent, 'linux') !== false) or (strpos($user_agent, 'macintosh') !== false)) { 
?>
<h2><?=t('Nexty Shell Script')?></h2>

<p><?=t('Are you a big fan of the Command line? Then you need the Nexty Command Line edition (only for Linux and Mac).')?></p>

<h3><?=t('Installation Instructions')?></h3>

<ul>
<li><?=t('Download')?> <a href="create_script.php"><?=t('Nexty Shell Script')?></a></li>
<li><?=t('Save the file to any of the folder in you')?> <code>$PATH</code>(<?=t('for eg,')?> <code>/usr/local/bin</code>). </li>
<li><?=t('Give it execute permission')?> (<code>chmod +x nexty</code>).</li>
</ul>

<p class="warning with-icon"><?=t('This script has your nexty <strong>username and password in clear text</strong> - do not create this script if you are on a public access system.')?></p>

<?php
}
