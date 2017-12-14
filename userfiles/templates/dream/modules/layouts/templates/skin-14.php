<?php

/*

type: layout

name: Team Card Full Width

position: 14

*/

?>

<?php include 'settings_padding_front.php'; ?>
<?php
if ($padding == '') {
    $padding = 'space--0';
}
?>

<section class="edit safe-mode nodrop <?php print $padding ?>" field="layout-skin-14-<?php print $params['id'] ?>" rel="module">
    <div class="clearfix">
        <module type="teamcard" template="skin-1"/>
    </div>
</section>