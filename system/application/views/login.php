<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link href="<?php print_the_static_files_url() ; ?>css/login.css" rel="stylesheet" type="text/css"/>

<title>Login</title>

</head>

<body  id="login">

<div class="main-content">

<div class="branding" align="center">

<h2>Admin Panel</h2>

</div>

<div class="login">

<!--<h1>Control panel login</h1>-->



<form name="loginform" id="loginform" action="<?php print site_url ('login'); ?>" method="post" class="form" >

<table width="550px" border="0" cellpadding="0" cellspacing="0" align="center" id="lgtbl">

  <tr>

    <td align="center">

    <label class="llabel">Email or Username:</label>

    <input name="username" type="text" id="username" tabindex="1" value="" size="40" />

    </td>

    </tr>

  <tr>

    <td align="center">

      <label class="llabel">Password:</label>

      <input name="password" type="password" id="password" tabindex="2" value="" size="40" />

      </td>

    </tr>

  <tr>

    <td align="center">

      <input type="submit" name="submit" id="submit" value="Login" tabindex="3" />

      </td>

    </tr>

</table>



</form>

</div>

</div>

</body>

</html>