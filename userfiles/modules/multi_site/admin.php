<?php 







?>

Create new site

domain<?php print $config['module_api'] ?>
 
<form action="<?php print $config['module_api'] ?>/create">


domain: 
<input type="text" class="mw-ui-field" name="domain">


username: 
<input type="text" class="mw-ui-field" name="username">

password: 
<input type="text" class="mw-ui-field" name="password">

email: 
<input type="text" class="mw-ui-field" name="email">


<input type="submit" class="mw-ui-btn" value="Submit">


</form>