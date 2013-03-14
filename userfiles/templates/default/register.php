<?php

/*

  type: layout
  content_type: static
  name: Register
  description: User registration layout

*/

?>
<? include THIS_TEMPLATE_DIR. "header.php"; ?>

<div id="content">
    <div class="container">
      <div class="well login-register">
            <div class="head">
              <h4>Create your account</h4>
            </div>
            <div class="form">
              <module type="users/register" />
            </div>
            <div class="form">
              <module type="users/login" />
            </div>
      </div>
    </div>
</div>









<? include THIS_TEMPLATE_DIR. "footer.php"; ?>
