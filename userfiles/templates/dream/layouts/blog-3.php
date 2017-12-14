<?php

/*

type: layout
content_type: dynamic
name: Blog 3
position: 3
description: Blog 3

*/


?>
<?php include template_dir() . "header.php"; ?>

    <div class="edit" rel="content" field="dream_content">
        <module type="layouts" template="skin-68"/>

        <module data-type="posts" template="skin-4" id="blog-3-posts-<?php print PAGE_ID; ?>"/>
    </div>

<?php include template_dir() . "footer.php"; ?>