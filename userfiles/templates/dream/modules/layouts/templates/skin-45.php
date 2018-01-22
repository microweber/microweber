<?php

/*

type: layout

name: Contacts Form with Features

position: 45

*/

?>

<?php include 'settings_padding_front.php'; ?>

<section class="subscribe safe-mode subscribe-5 bg--secondary nodrop edit <?php print $padding ?>" field="layout-skin-45-<?php print $params['id'] ?>" rel="module">
    <div class="container">
        <div class="row">
            <div class="col-md-5 col-md-offset-1 col-sm-6 voh">
                <module type="contact_form" template="skin-1"/>
            </div>

            <div class="col-md-3 col-md-offset-1 col-sm-4 col-sm-offset-1 allow-drop">
                <div class="subscribe__feature cloneable">
                    <h6><?php _lang("Free Resources", "templates/dream"); ?></h6>
                    <p>
                        <?php _lang("Our team of designers give back with mockups, logos and tons more", "templates/dream"); ?>.
                    </p>
                </div>
                <div class="subscribe__feature cloneable">
                    <h6>Expert Interviews</h6>
                    <p>
                        <?php _lang("We speak with some of the web’s foremost designers and developers on a range of topics", "templates/dream"); ?>.
                    </p>
                </div>
                <div class="subscribe__feature cloneable">
                    <h6><?php _lang("Monthly Prizes", "templates/dream"); ?></h6>
                    <p>
                        <?php _lang("As if the free resources weren’t enough - we give away a sweet design stash each month", "templates/dream"); ?>.
                    </p>
                </div>
                <div class="subscribe__feature cloneable">
                    <h6><?php _lang("Monthly Prizes", "templates/dream"); ?></h6>
                    <p>
                        <?php _lang("As if the free resources weren’t enough - we give away a sweet design stash each month", "templates/dream"); ?>.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>