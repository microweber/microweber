<?php

/*

type: layout

name: Tab Features 2

position: 64

*/

?>

<?php include 'settings_padding_front.php'; ?>

<section class="nodrop safe-mode edit <?php print $padding ?>" field="layout-skin-64-<?php print $params['id'] ?>" rel="module">
    <div class="container">
        <div class="row">
            <div class="col-sm-10 col-sm-offset-1 text-center allow-drop">
                <h4>Text Tabs</h4>
                <module type="tabs" template="skin-1"/>
            </div>
        </div>
    </div>
</section>
