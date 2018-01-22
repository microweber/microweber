<?php

/*

  type: layout
  content_type: static
  name: Register
  position: 11
  description: Register page

*/

?>
<?php include template_dir() . "header.php"; ?>

<div class="edit" rel="content" field="dream_content">
    <section class="height-100 cover cover-8">
        <div class="col-md-7 col-sm-5">
            <div class="background-image-holder" style="background-image: url('<?php print template_url('assets/img/'); ?>hero24.jpg');"></div>
        </div>

        <div class="col-md-5 col-sm-7 bg--white text-center">
            <div class="pos-vertical-center">
                <img class="logo" alt="<?php _lang("Dream", "templates/dream"); ?>" src="<?php print template_url('assets/img/'); ?>logo-large-dark.png">
                <br /><br />
                <p class="lead"><?php _lang("Register"); ?></p>
                <div class="text-left">
                    <module type="users/register" />
                </div>
            </div>
        </div>
    </section>
</div>

<?php include template_dir() . "footer.php"; ?>
