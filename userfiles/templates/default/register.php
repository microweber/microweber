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
    <div class="span12">
            <div class="well small-layout headed-box" id="sign-box">
                 <module type="users/register" />
             </div>
         </div>

    </div>
</div>









<? include THIS_TEMPLATE_DIR. "footer.php"; ?>
