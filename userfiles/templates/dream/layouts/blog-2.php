<?php

/*

type: layout
content_type: dynamic
name: Blog 2
position: 3
description: Blog 2

*/


?>
<?php include template_dir() . "header.php"; ?>

    <div class="edit" rel="content" field="dream_content">
        <module type="layouts" template="skin-68"/>

        <module data-type="posts" template="skin-3" id="blog-2-posts-<?php print PAGE_ID; ?>"/>

    </div>

<?php include template_dir() . "footer.php"; ?>