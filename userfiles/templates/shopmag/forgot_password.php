<?php

/*



*/


?>

<?php
    if(is_logged() == true){
        return mw()->url_manager->redirect(site_url().'profile/#section=profile');
    }
?>

<?php include THIS_TEMPLATE_DIR. "header.php"; ?>

<script>
    $(document).ready(function(){
      EqualHeight('.sign-in .item-box');
    });
    $(window).load(function(){
      EqualHeight('.sign-in .item-box');
    });
</script>
<div class="mw-wrapper">
   <div class="mw-ui-row mw-ui-row-drop-on-1024 sign-in">
        <div class="mw-ui-col">
            <div class="mw-ui-col-container">
                <div class="item-box">
                    <module="users/forgot_password" />
                </div>
            </div>
        </div>
        <div class="mw-ui-col">
            <div class="mw-ui-col-container">
                <div class="item-box text-center">
                    <a href="<?php print login_url(); ?>" >
                        <span class="mw-icon-users" style="font-size: 170px;opacity: 0.2"></span>
                        <br>
                        <span class="mw-ui-btn">
                            <?php _e("Sign in"); ?>
                        </span>
                    </a>
                </div>
            </div>
        </div>
   </div>
</div>

<?php include THIS_TEMPLATE_DIR. "footer.php"; ?>
