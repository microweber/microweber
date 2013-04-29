<?php include "header.php"; ?>

<div id="report-frame" class="frame">


<span class="ico ireport"></span>
<div>
  <span id="username"></span>, report the Bug. <br>
  Please describe that you find in details, so we can fix it.
</div>



<form action="query.php" class="form" id="bug-form">
    <div class="mw-ui-field-holder">
        <input type="text" class="mw-ui-field" placeholder="Your Email" />
    </div>
    <div class="mw-ui-field-holder">
        <input type="text" class="mw-ui-field" placeholder="Bug Title" />
    </div>

    <div class="mw-ui-field-holder">
        <textarea class="mw-ui-field" placeholder="Describe your issue"></textarea>
    </div>

    <input type="submit" value="Send Message" class="mw-ui-btn mw-ui-btn-blue right" />

</form>



</div>







<?php include "footer.php"; ?>