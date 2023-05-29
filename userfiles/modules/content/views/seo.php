<div class="row">

    <div class="col-md-12">
        <div class="form-group ">
            <label class="form-label"><?php _e("Meta title"); ?></label>
            <small data-bs-toggle="tooltip" title="<?php _e("Title to appear on the search engines results page"); ?>"></small>
            <small class="text-muted d-block mb-2"><?php _e("Title to appear on the search engines results page"); ?></small>

            <?php
            $value = '';
            if (isset($contentModel->content_meta_title)) {
                $value = $contentModel->content_meta_title;
            }

            echo $formBuilder->Text('content_meta_title')
                ->setModel($contentModel)
                ->value($value)
                ->autocomplete(false) ;
            ?>
        </div>
    </div>



    <div class="col-md-12">
        <div class="form-group ">
            <label class="form-label"><?php _e("Meta description"); ?></label>
            <small data-bs-toggle="tooltip" title="Short description for yor content."></small>

            <?php
            $description = '';
            if (isset($contentModel->description)) {
                $description = $contentModel->description;
            }
            echo $formBuilder->textArea('description')
                ->setModel($contentModel)
                ->value($description)
                ->autocomplete(false);
            ?>
        </div>
    </div>

    
    <div class="col-md-12">
        <div class="form-group ">
            <label class="form-label"><?php _e("Meta keywords"); ?></label>
            <small data-bs-toggle="tooltip" title="Short description for yor content."></small>
            <small class="text-muted d-block mb-2"><?php _e('Separate keywords with a comma and space') ?></small>

            <?php
            $keywords = '';
            if (isset($contentModel->content_meta_keywords)) {
                $keywords = $contentModel->content_meta_keywords;
            }
            echo $formBuilder->text('content_meta_keywords')
                ->setModel($contentModel)
                ->value($keywords)
                ->autocomplete(false);
            ?>
        </div>

        <small class="text-muted"><?php _e("Type keywords that describe your content - Example: Blog, Online News, Phones for Sale etc"); ?></small>

    </div>










    <div class="col-md-12">
        <div class="form-group ">
            <label><?php _e("OG Images"); ?></label>
            <small class="text-muted d-block mb-2">
                <?php _e('Those images will be shown as a post image at facebook shares') ?>.<br/>
                <?php _e("If you want to attach og images, you must upload them to gallery from 'Add media'"); ?>.
            </small>
        </div>
    </div>
</div>



