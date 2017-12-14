<?php

/*

type: layout

name: Text with Button in Blue background

position: 9

*/

?>

<?php include 'settings_padding_front.php'; ?>

<section class="nodrop safe-mode bg--primary cta cta-5 edit <?php print $padding ?>" field="layout-skin-9-<?php print $params['id'] ?>" rel="module">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 text-center allow-drop">
                <h4>Interested in working with Dream?</h4><br/><br/>
                <module type="btn" template="bootstrap" button_style="btn-simple" text="Lets Talk"/>
            </div>
        </div>
    </div>
</section>