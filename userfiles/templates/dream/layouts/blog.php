<?php

/*

type: layout
content_type: dynamic
name: Blog
position: 3
description: Blog

*/


?>
<?php include template_dir() . "header.php"; ?>

    <div class="edit" rel="content" field="dream_content">
        <module type="layouts" template="skin-68"/>
    </div>

    <section class="nodrop">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <module data-type="posts" template="skin-2" id="blog-posts-<?php print PAGE_ID; ?>"/>
                </div>
                <div class="col-md-3 col-md-offset-1 hidden-sm hidden-xs">
                    <?php include_once "blog_sidebar.php"; ?>
                </div>
            </div>
        </div>
    </section>

<?php include template_dir() . "footer.php"; ?>