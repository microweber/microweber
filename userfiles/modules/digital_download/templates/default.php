<div
    style="
    display:flex;
    justify-content:<?php print $button_alignment; ?>;
"
>
    <a class="btn btn-default" data-bs-toggle="modal" href="#digital-download-<?php print $params['id']; ?>"
       data-backdrop="false">
        <?php echo $button_text; ?>
    </a>
</div>

<style>
    .modal-backdrop {
        z-index: 0;
    }
    #digital-download-<?php print $params['id']; ?> {
        z-index: 1100 !important;
        top: 10%;
        color: #000 !important;
    }
    #digital-download-<?php print $params['id']; ?> .modal-title {
        color: #000 !important;
    }
</style>

<div class="modal fade" id="digital-download-<?php print $params['id']; ?>">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Download
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php _e('Close'); ?>"></button>
            </div>

            <div class="modal-body">
                <div class="form-group download_url">
                    <label for="<?php print md5($params['id']); ?>-email" class="form-label font-weight-bold mb-3"><?php _e("Your Email"); ?></label>
                    <input class="form-control" type="email" value="" id="<?php print md5($params['id']); ?>-email" />
                </div>

                <br />

                <button type="button" onclick="<?php print $download_on_click; ?>" class="btn btn-secondary">
                    Download
                </button>
            </div>

        </div>
    </div>
</div>
