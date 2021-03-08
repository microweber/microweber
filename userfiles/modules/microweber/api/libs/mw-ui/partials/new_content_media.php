<div class="card style-1 mb-3">
    <div class="card-header no-border">
        <h6><strong><?php _e("Media"); ?></strong></h6>
        <div>
            <select class="selectpicker" data-title="<?php _e("Add media from"); ?>" data-style="btn-sm" data-width="auto">
                <option><?php _e("Add image from URL"); ?></option>
                <option><?php _e("Browse uploaded"); ?></option>
                <option><?php _e("Add file"); ?></option>
            </select>
        </div>
    </div>

    <div class="card-body pt-0">
        <div class="row">
            <div class="col-12">
                <div class="dropable-zone">
                    <div class="holder">
                        <div class="dropable-zone-img"></div>

                        <div class="progress progress-silver">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 50%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>

                        <button type="button" class="btn btn-primary btn-rounded"><?php _e("Add file"); ?></button>
                        <p><?php _e("or drop here"); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card style-1 mb-3">
    <div class="card-header no-border">
        <h6>1<?php _e("media selected"); ?> </h6>
        <div>
            <a href="javascript:;" class="btn btn-link btn-link-danger btn-sm"><?php _e("Delete selected"); ?></a>
        </div>
    </div>

    <div class="card-body pt-0">
        <div class="row">
            <div class="col-12">
                <div class="dropable-zone small-zone square-zone">
                    <div class="holder">
                        <button type="button" class="btn btn-link"><?php _e("Add file"); ?></button>
                        <p><?php _e("or drop"); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>