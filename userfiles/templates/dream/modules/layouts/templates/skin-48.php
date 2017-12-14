<?php

/*

type: layout

name: Google Map

position: 48

*/

?>

<?php include 'settings_padding_front.php'; ?>

<section class="map-1 safe-mode nodrop edit <?php print $padding ?>" field="layout-skin-48-<?php print $params['id'] ?>" rel="module">
    <div class="map-container">
        <module type="google_maps"/>
    </div>
</section>