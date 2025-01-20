<?php

/*

type: layout

name: Default

description: Default

*/

?>

<div class="alert alert-success margin-bottom-30" id="msg<?php print $form_id; ?>" style="display:none;">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <strong><?php _lang("Thank You", "templates/guesthouse"); ?>!</strong> <?php _lang("Your message successfully sent", "templates/guesthouse"); ?>!
</div>

<div class="section-42">
    <div class="form">
        <form id="contactform" data-form-id="<?php print $form_id ?>" name="<?php print $form_id ?>" method="post">

            <?php print csrf_form() ?>

            <div class="row">
                <div class="col-12">
                    <module type="custom_fields" default-fields="Full Name[type=text,field_size=12,show_placeholder=true], Email[type=email,field_size=12,show_placeholder=true], Phone Number[type=phone,field_size=12,show_placeholder=true], How many nights?[type=text,field_size=6,show_placeholder=true], How many guests?[type=text,field_size=6,show_placeholder=true], From[type=date,field_size=6,show_placeholder=true], To[type=date,field_size=6,show_placeholder=true], Message[type=textarea,field_size=12,show_placeholder=true]" input_class="form-control"/>
                </div>
            </div>

            <div class="module-custom-fields">
                <div class="row">
                    <div class="col-12">
                        <br><br>
                        <module type="btn"  button_action="submit" button_style="btn-default" button_size="btn-lg btn-block  w-100 justify-content-center" text="Send"/>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>
