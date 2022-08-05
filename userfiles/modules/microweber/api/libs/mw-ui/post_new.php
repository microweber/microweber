<?php include('partials/header.php'); ?>


<div class="tree">
    DURVO
</div>

<script>
    $(document).ready(function () {
//        $('body > .main').addClass('show-sidebar-tree');
    });
</script>

<main>
    <div class="row">
        <div class="col-md-8">
            <div class="card style-1 mb-3">
                <div class="card-header">
                    <h5><i class="mdi mdi-text text-primary mr-3"></i> <strong><?php _e("Add post"); ?></strong></h5>
                    <div>
                        <button type="button" class="btn btn-sm btn-success"><?php _e("Save"); ?></button>
                    </div>
                </div>

                <div class="card-body pt-3">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label><?php _e("Post title"); ?></label>
                                <input type="text" class="form-control" placeholder="e.g. Share your last story">
                                <span><i class="mdi mdi-link mdi-20px lh-1_3 mr-1 text-silver float-left"></i> <small><span class="text-silver">http://localhost/microweber/</span><span class="contenteditable" data-bs-toggle="tooltip" data-title="edit" data-placement="right" contenteditable="true"><?php _e("shop"); ?></span></small></span>
                            </div>

                            <div class="form-group">
                                <label><?php _e("Content"); ?></label>
                                <textarea class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card style-1 mb-3">
                <div class="card-body pt-3">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label><?php _e("Short description"); ?></label>
                                <small class="text-muted d-block mb-2"><?php _e("Add a summery of your post to appear on your home page or blog"); ?></small>
                                <textarea class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php include 'partials/new_content_media.php'; ?>

            <?php include 'partials/new_content_advanced.php'; ?>
        </div>

        <?php include 'partials/new_content_sidebar.php'; ?>
    </div>


    <div class="row copyright mt-3">
        <div class="col-12">
            <p class="text-muted text-center small">Open-source website builder and CMS Microweber 2020. Version: 1.18</p>
        </div>
    </div>
</main>


<?php include('partials/footer.php'); ?>
