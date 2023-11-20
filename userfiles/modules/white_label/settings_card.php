<div class="card mb-7">
    <div class="card-body">


        <div class="row">
            <div class="col-xl-3 mb-xl-0 mb-3">
                <h5 class="font-weight-bold settings-title-inside"><?php _e("Powered by Microweber"); ?></h5>
                <small class="text-muted"><?php _e('Control whether or not "Powered by Microweber" links display in the footer of your site and products'); ?>.</small>

            </div>
            <div class="col-xl-9">
                <div class="card bg-azure-lt ">
                    <div class="card-body ">
                        <div class="row">
                       <?php

                       $whiteLabeUrl = admin_url().'module/view?type=white_label'

                       //     <module type="settings/group/powered_by_on_off"/>
                       ?>

                            <a class="btn btn-outline-secondary" href="<?php print $whiteLabeUrl; ?>">
                                Click here to edit the branding settings
                            </a>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
