<?php

/*

 

*/


?>
<?php include THIS_TEMPLATE_DIR . "header.php"; ?>
<div id="content">
    <div class="container">
        <div class="small-layout headed-box" id="sign-box">

            <?php if (is_logged()) { ?>

                <module type="users/profile" />

            <?php } else { ?>
                <module type="users/login" />

            <?php } ?>


        </div>
    </div>
</div>
<?php include THIS_TEMPLATE_DIR . "footer.php"; ?>
