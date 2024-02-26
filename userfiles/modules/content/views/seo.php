<div class="card mb-5" id="seo-settings">
    <div class="card-body">
        <div class="row">

            <div class="col-md-12">
                <h3 class="main-pages-title"><?php _e('Search engine optimisation (SEO)'); ?></h3>
                <small class="text-muted d-block"><?php _e("Add a title and description to see how this product might appear in a search engine listing"); ?></small>
            </div>

            <div class="col-md-12">
                <div class="form-group ">
                    <label class="form-label mt-4 font-weight-bold"><?php _e("Meta title"); ?></label>
                    <small data-bs-toggle="tooltip" title="<?php _e("Title to appear on the search engines results page"); ?>"></small>
                    <small class="text-muted d-block mb-2"><?php _e("Describe for what is this page about in short title"); ?></small>

                    <?php
                    $value = '';
                    if (isset($contentModel->content_meta_title)) {
                        $value = $contentModel->content_meta_title;
                    }

                    echo $formBuilder->Text('content_meta_title')
                        ->setModel($contentModel)
                        ->value($value)
                        ->autocomplete('off') ;
                    ?>
                </div>
            </div>



            <div class="col-md-12">
                <div class="form-group ">
                    <label class="form-label font-weight-bold"><?php _e("Meta description"); ?></label>
                    <small class="text-muted d-block mb-2" ><?php _e("Please provide a brief summary of this web page"); ?></small>
                    <small data-bs-toggle="tooltip" title="Short description for yor content."></small>

                    <?php
                    $description = '';
                    if (isset($contentModel->description)) {
                        $description = $contentModel->description;
                    }
                    echo $formBuilder->textArea('description')
                        ->setModel($contentModel)
                        ->value($description)
                        ->setAttribute('rows' , '8')
                        ->autocomplete('off');
                    ?>
                </div>
            </div>


            <div class="col-md-12">
                <div class="form-group ">
                    <label class="form-label font-weight-bold"><?php _e("Meta keywords"); ?></label>
                    <small data-bs-toggle="tooltip" title="Short description for yor content."></small>
                    <small class="text-muted d-block mb-2"><?php _e('Separate keywords with a comma and space') ?></small>
                    <small class="text-muted mb-3 d-block"><?php _e("Type keywords that describe your content - Example: Blog, Online News, Phones for sale"); ?></small>

                    <?php
                    $keywords = '';
                    if (isset($contentModel->content_meta_keywords)) {
                        $keywords = $contentModel->content_meta_keywords;
                    }
                    echo $formBuilder->text('content_meta_keywords')
                        ->setModel($contentModel)
                        ->value($keywords)
                        ->autocomplete('off');
                    ?>
                </div>


            </div>

        </div>
    </div>
</div>



