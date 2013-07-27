<?php

 $err = mw_var('numia_error');
  
?>

<script>mw.require("<?php print $config['url_to_module'] ?>numia/style.css", true);</script>


<div class="numia-register">

<?php if(is_array($err )): ?>
<div class="alert alert-error">
 <h4>Error:</h4>
<ul>
  <?php foreach($err  as $item): ?>

<li><?php print $item ?></li>
 <?php endforeach ; ?>
 </ul>
 </div>
<?php endif; ?>


<a class="numia_logo" href="http://numia.biz" target="_blank"></a>

<h2>New Registration </h2>







<div class="well">
    <div>
    <form   method="post" id="numia_user_login" action="<?php print $config['url_main'] ?>"  >
      <input name="numia_register" type="hidden" value="1"  />

      <div class="control-group">
      <label>Email</label>
        <input  class="mw-ui-field"  name="numia_username" type="text" placeholder="<?php _e("email"); ?>"   />
      </div>
      <div class="control-group">
      <label>Password</label>
        <input  class="mw-ui-field"  name="numia_password" type="password" placeholder="<?php _e("Password"); ?>"   />
      </div>
       <div class="control-group">
      <label>First name</label>
        <input  class="mw-ui-field"  name="first_name" type="text" placeholder="<?php _e("First name"); ?>"   />
      </div>
       <div class="control-group">
      <label>Last name</label>
        <input  class="mw-ui-field"  name="last_name" type="text" placeholder="<?php _e("Last name"); ?>"   />
      </div>
       <div class="control-group">
      <label>Company</label>
        <input  class="mw-ui-field"  name="company" type="text" placeholder="<?php _e("Company"); ?>"   />
      </div>


      <p><input type="checkbox" id="numia_terms" /><label for="numia_terms" class="checkbox inline">I agree with <a href="http://numia.biz/terms.php" target="_blank">Terms and Conditions</a></label></p>
        <br>
      <input class="btn btn-large" type="submit" value="<?php _e("Register"); ?>" />
          &nbsp;
      Or &nbsp;<a href="<?php print $config['url_main'] ?>">Login to Numia</a>
    </form>
    </div>
</div>

<div class="numia_powered_by">Powered by <a href="http://www.numia.biz" target="_blank">www.numia.biz</a></div>

</div>
