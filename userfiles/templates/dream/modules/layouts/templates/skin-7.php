<?php

/*

type: layout

name: Blog posts

position: 7

*/

?>

<?php include 'settings_padding_front.php'; ?>

<section class="nodrop safe-mode blog-snippet-1 edit <?php print $padding ?>" field="layout-skin-7-<?php print $params['id'] ?>" rel="module">
    <div class="container">
        <module type="posts" limit="3"/>

        <div class="row">
            <div class="col-sm-12 text-center allow-drop">
                <module type="btn" text="View the blog" template="bootstrap"/>
            </div>
        </div>
    </div>
</section>