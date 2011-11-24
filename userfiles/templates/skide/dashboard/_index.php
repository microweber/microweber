<h1>Dashboard:</h1>

<h2>Latest status updates:</h2>
<ul>
<?php foreach($status_updates as $status_update) { 
	include "status_update_item.php";
} ?>
</ul>

<br /><br /><br />

<h2>Latest friends' posts:</h2>

<ul>
<?php foreach($latest_posts as $the_post) { 
	include TEMPLATES_DIR . "/articles_list_single_post_item.php";
} ?>
</ul>