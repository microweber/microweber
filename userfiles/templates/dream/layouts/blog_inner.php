<?php include template_dir() . "header.php"; ?>

    <div id="blog-content-<?php print CONTENT_ID; ?>">
        <?php $post = get_content_by_id(CONTENT_ID); ?>

        <section class="blog-post">
            <div class="blog-post__title bg--secondary">
                <div class="container">
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 text-center">
                            <h1><?php print content_title(); ?></h1>

                            <?php if ($post['created_by']): ?>
                                <div class="blog-post__author">
                                    <span>
                                        <em><?php _lang("by", "templates/dream"); ?> </em>
                                    </span>
                                    <span class="h6"><?php print user_name($post['created_by']); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container">
                <div class="row">
                    <div class="col-sm-10 col-sm-offset-1">
                        <module type="pictures" rel="content"/>
                    </div>
                    <div class="col-sm-8 col-sm-offset-2">
                        <div class="edit dropcap" field="content" rel="content">
                            <div class="element">
                                <p align="justify"><?php _lang("This text is set by default and is suitable for edit in real time. By default the drag and drop core feature will allow you to position it anywhere on the site. Get creative, Make Web.", "templates/dream"); ?></p>
                            </div>
                        </div>
                        <hr/>
                    </div>
                </div>


                <div class="row">
                    <div class="col-sm-12 text-center">
                        <div class="blog-post__share">
                            <h5><?php _lang("Share this article", "templates/dream"); ?></h5>

                            <module type="sharer" id="share-post"/>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1">
                        <div class="blog-post__comments">
                            <hr>
                            <h6><?php print get_comments('count=1&content_id=' . content_id()) ?> <?php _lang("Comments", "templates/dream"); ?>:</h6>

                            <div class="edit" rel="content" field="comments">
                                <module type="comments" data-content-id="<?php print CONTENT_ID; ?>"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>


<?php include template_dir() . "footer.php"; ?>