<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $lang ?>" lang="<?php echo $lang ?>" dir="ltr"><head>
<title><?php echo $title?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="<?php echo $abs?>css/style.css" rel="stylesheet" media="all" type="text/css" />
<link href="<?php echo $abs?>images/themes/<?php echo $theme?>/theme.css" rel="stylesheet" media="screen" type="text/css" />
<!--[if IE]>
<link rel="stylesheet" href="<?php echo $abs?>css/style_ie.css" type="text/css" media="all" />
<![endif]-->
<link href="<?php echo $abs?>css/print.css" rel="stylesheet" media="print" type="text/css" />
<script src="<?php echo $abs?>js/libraries/jsl.js" type="text/javascript"></script>
<?php
// User wants a non-english version of the page.
if ($locale != 'en_EN') {
	print '<script src="'.$abs.'includes/locale/'.$locale.'.js" type="text/javascript"></script>'."\n";
}
?>
<script src="<?php echo $abs?>js/application.js" type="text/javascript"></script>
<?php echo $includes?>
</head>
<body>
<div id="loading"><?php echo t("Loading...")?></div>
<div id="header">
<?php if(isset($_SESSION['user'])) { ?><div id="top-new-task">
<a href="<?php echo $abs?>tasks/next.php" class="with-icon next-task"><?php echo t("Next Action")?>...</a><br />
<a href="<?php echo $abs?>tasks/new.php" class="with-icon tasks"><?php echo t("New Task")?>...</a><br />
<a href="<?php echo $abs?>tasks/inbox.php" class="with-icon inbox"><?php echo t("Inbox")?>...</a></div>
<?php } ?>
<h1 id="logo"><a href="<?php echo $abs?>">Nexty</a></h1>

<?php if(isset($_SESSION['user'])) { ?>
<ul id="menu">
<li><a href="<?php echo $rel?>index.php" class="home with-icon"><?php echo t("Home")?></a></li>

<li class="dropdown"><a href="<?php echo $rel?>tasks/list.php" class="section with-icon"><?php echo t("Sections")?></a>
<ul class="menu-with-icon sections">
<?php foreach($all_types as $id=>$name) { ?>
<li><a href="<?php echo $abs?>tasks/list.php?type=<?php echo urlencode($id)?>"><?php echo $name?></a></li>
<?php } ?>
</ul>
</li>

<li class="dropdown"><a href="<?php echo $rel?>projects/" class="project with-icon"><?php echo t("Projects")?></a>
<ul class="menu-with-icon projects">
<?php foreach($pending_projects as $id=>$name) { ?>
<li><a href="<?php echo $abs?>tasks/list.php?project=<?php echo $id?>"><?php echo $name?></a></li>
<?php } ?>
</ul></li>

<li class="dropdown"><a href="<?php echo $rel?>contexts/" class="context with-icon"><?php echo t("Contexts")?></a>
<ul class="menu-with-icon contexts">
<?php foreach($contexts as $id=>$name) { ?>
<li><a href="<?php echo $abs?>tasks/list.php?contexts[]=<?php echo $id?>"><?php echo $name?></a></li>
<?php } ?>
</ul></li>

<li><a href="<?php echo $rel?>calendar.php" class="calendar with-icon"><?php echo t("Calendar")?></a></li>
<li><a href="<?php echo $rel?>reminders/" class="reminder with-icon"><?php echo t("Reminders")?></a></li>
<li><a href="<?php echo $rel?>settings/" class="settings with-icon"><?php echo t("Settings")?></a></li>
<?php loadPlugins(); ?>
<?php if($single_user['status'] == '0') { ?>
<li><a href="<?php echo $rel?>users/logout.php" class="logout with-icon"><?php echo t("Logout")?></a></li>
<?php } ?>
<li id="about"><a href="<?php echo $rel?>about.php" class="info with-icon"><?php echo t("About")?></a></li>
</ul>
<?php } ?>

</div><br />

<div id="main">
<!-- The left sidebar -->
<div id="sidebar_1" class="sidebar">
<?php if(isset($_SESSION['user'])) { ?>
<h2><?php echo t("Projects")?>...</h2>
<ul class="list-with-icon projects">
<?php foreach($pending_projects as $id=>$name) { 
	$params['project'] = $id;
?>
<li><a href="<?php echo getLink($abs.'tasks/list.php',$params)?>"><?php echo $name?></a> &nbsp;
<a class="icon add" href="<?php echo $abs?>tasks/new.php?project_id=<?php echo $id?>"><?php echo t("New Task in %s",$name)?></a></li>
<?php } ?>
</ul>

<a class="with-icon edit-projects" href="<?php echo $abs?>projects/"><?php echo t("Projects")?>...</a>


<?php if($reminder_alerts or $todays_reminder) { ?>
<h2><?php echo t("Reminders")?></h2>

<ul class="list-with-icon reminders todays-reminder">
<?php foreach($todays_reminder as $rem) { ?>
<li><a href="<?php echo $abs.'reminders/edit.php?reminder='.$rem['id']?>"><?php echo $rem['name']?></a></li>
<?php } ?>
</ul>

<ul class="list-with-icon reminders">
<?php foreach($reminder_alerts as $rem) { ?>
<li><a href="<?php echo $abs.'reminders/edit.php?reminder='.$rem['id']?>"><?php echo $rem['name']?></a> - <?php echo t("%d days left",$rem['until'])?></li>
<?php } ?>
</ul>
<?php } ?>

<?php if($due_tommorow_alerts or $due_today) { ?>
<h2><?php echo t("Due Today")?></h2>

<ul class="due-on-today">
<?php foreach($due_today as $tsk) { ?>
<li><a href="<?php echo $abs.'tasks/edit.php?task='.$tsk['id']?>"><?php echo $tsk['name']?></a></li>
<?php } ?>
</ul>

<ul class="due-tommorow">
<?php foreach($due_tommorow_alerts as $tsk) { ?>
<li><a href="<?php echo $abs.'tasks/edit.php?task='.$tsk['id']?>"><?php echo $tsk['name']?></a> - <?php echo t("due tommorow")?></li>
<?php } ?>
</ul>
<?php } ?>


<?php } ?>&nbsp;
</div>

<div id="contents">
<div class="with-icon" id="error-message" <?php echo (isset($QUERY['error']) and $QUERY['error']) ? '':'style="display:none;"';?>><?php echo stripslashes($QUERY['error'])?></div>
<div class="with-icon" id="success-message" <?php echo (isset($QUERY['success']) and $QUERY['success']) ? '':'style="display:none;"';?>><?php echo stripslashes($QUERY['success'])?></div>
<!-- Begin Content -->
<?php 
/////////////////////////////////// The Template file will appear here ////////////////////////////

include($GLOBALS['template']->template); 

/////////////////////////////////// The Template file will appear here ////////////////////////////
?>
<!-- End Content -->
</div>

<div id="sidebar_2" class="sidebar">
<?php if(isset($_SESSION['user'])) { ?>
<h2 class="with-icon search"><?php echo t("Search")?>...</h2>
<?php if(isset($_GET['search-status'])) { 
	print "<div class='error with-icon'>". strip_tags($_GET['search-status']) . "</div>";
} ?>
<form action="<?php echo $rel?>search.php" method="post">
<input type="text" name="search" id="search" />
<input type="submit" name="action" value="<?php echo t("Go")?>" id="search-button" />
</form>

<h2><?php echo t("Contexts")?>...</h2>

<ul class="list-with-icon contexts">
<?php foreach($contexts as $id=>$name) { ?>
<li><a href="<?php echo getLink($abs.'tasks/list.php?contexts[]='.$id)?>"><?php echo $name?></a>&nbsp;
<a class="icon add" href="<?php echo $abs?>tasks/new.php?context=<?php echo $id?>"><?php echo t("New Task in %s",$name)?></a></li>
<?php } ?>
</ul>

<a class="with-icon edit-contexts" href="<?php echo $abs?>contexts/"><?php echo t("Contexts")?>...</a>
<?php } ?>&nbsp;
</div>
</div>

<div id="footer"></div>

</body>
</html>