<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Saved</title>
</head>

<body>
<h1>Saved: <?php print $form_values['content_title']; ?></h1>

<br />
<ul>
<li>
<a href="javascript:window.close();">Close This Window</a>
</li>

<li>
<a href="<?php print site_url('admin/content/posts_edit/id:'.$form_values['id'])  ?>">Edit again</a>
</li>
</ul>


</body>
</html>
