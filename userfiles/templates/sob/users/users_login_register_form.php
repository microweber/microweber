
<div id="login-form" style="display: none">
    <form method="post" action="#">

        <label>Username</label>
        <input type="text" class="" /><br />

        <label>Password</label>
        <input type="text" class="" />

        <a href="#" class="btn submit">Login</a>
    </form>

    <form action="#" method="post">
        <h3>Forgotten Your password? </h3>
        <p>Enter your username below and the system will assign you a new password that will be delivered to the email address you signed up with.</p>

        <label>Username</label>
        <input type="text" />

        <a href="#" class="btn submit">Remind</a>

    </form>

</div>


<div id="register-form" style="display: none">
<form method="post" action="#">
    <label> Name</label>
<input type="text" name="name" class="user_registation_trigger" size="20"/><br />

<label>Company</label>
<input type="text" name="company"  class="user_registation_trigger"  size="20"/><br />
<label>Checks payable to</label>
<input type="text" name="payee" class="user_registation_trigger"  size="20"/><br />

<label>Address 1</label>
<input type="text" name="address1" class="user_registation_trigger"  size="20"/> <br />

<label>Address 2</label>
<input type="text" name="address2"  class="user_registation_trigger"  size="20"/> <br />

<label>Town/City</label>
<input type="text" name="city"  class="user_registation_trigger"  size="20"/> <br />

<label> Area/Province</label>

<input type="text" name="area" class="user_registation_trigger"   size="20"/>  <br />


<label>Post Code/Zip</label>

<input type="text" name="postcode" class="user_registation_trigger"  size="20"/><br />

<label> Country</label>

<input type="text" name="country" class="user_registation_trigger"   size="20"/> <br />

<label>Website URL</label>
<input type="text" value="http://" name="website" size="20"/> <br />
<label>E-mail</label>
<input type="text" name="email" size="20"/>  <br />

<label> PayPal E-mail</label>

<input type="text" name="paypal" size="20"/>  <br />


<select name="paymethod">
<option selected="" value="Cheque">Cheque</option>
<option value="PayPal">PayPal</option>
</select>    <br />

<label>phone number</label>

<input type="text" name="u1" size="20"/>   <br />


<label>UserName</label>

<input type="text" name="username" size="20" maxlength="20"/>  <br />


<label>Password</label>

<input type="text" name="password" size="20" maxlength="20"/>  <br />


<label>Type</label>
<select name="type">
<option value="comm">Commission</option>
</select>


  <label>capcha</label>
  <img src="<?php print site_url('ajax_helpers/security_captcha'); ?>" />
  <input type="text" name="captcha" class="user_registation_trigger" size="20"/><br />


<a href="#" class="btn submit">Register</a> 
</form>

</div>