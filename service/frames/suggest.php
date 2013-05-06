<?php

if(!isset($_GET['user'])){
header('Location: http://microweber.com/frames/suggest.php');
} else {
header('Location: http://microweber.com/frames/suggest.php?user='.$_GET['user']);
	
}
exit();

 include "header.php"; ?>


<script>

$(document).ready(function(){
   mw.$("#bug-form").submit(function(){
     mw.post(this, function(){

     });
      return false;
   });
});

</script>

<div id="report-frame" class="frame">


<span class="ico isuggest" style="margin: 6px 0 0 14px;"></span>
<div style="float: left;padding: 0 0 20px 36px;">
  <span id="username"><?php  if(array_key_exists('user', $_GET)){ print $_GET['user']; }  ?></span>, suggest a feature. <br>
  Please describe that you find in details, so we can fix it.
</div>



<form action="query.php" class="form" id="bug-form">
    <div class="mw-ui-field-holder">
        <input type="email" required class="mw-ui-field" placeholder="Your Email" name="Email" />
    </div>
    <div class="mw-ui-field-holder">
        <input type="text" class="mw-ui-field" required placeholder="Bug Title" name="Title" />
    </div>

    <div class="mw-ui-field-holder">
        <textarea class="mw-ui-field" placeholder="Describe your issue" required name="Message"></textarea>
    </div>

    <input type="hidden" name="Subject" value="Report a Bug" />

    <input type="submit" value="Send Message" class="mw-ui-btn mw-ui-btn-blue right" />

</form>



</div>







<?php include "footer.php"; ?>