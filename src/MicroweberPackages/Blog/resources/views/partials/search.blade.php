<?php
$randomId = uniqid();
?>

<div class="card-header bg-white pt-md-0 px-1">
    <div data-bs-toggle="collapse" data-bs-target="#collapse_{{$randomId}}"  aria-expanded="true" class="d-flex">
        <h6><?php _e('Search') ?></h6>
        <i class="mdi mdi-plus ms-auto align-self-center" ></i>
    </div>
</div>

<div class="collapse show" id="collapse_{{$randomId}}">
    <div class="card-body px-1">
        <div class="input-group mb-3">
            <input type="text" class="form-control js-filter-search-field" value="{{$search}}" placeholder="<?php _e('Search');?>" />
            <div class="input-group-append">
                <button type="submit" class="btn btn-primary btn-icon p-3 js-filter-search-submit"><i class="mdi mdi-magnify"></i></button>
            </div>
        </div>
    </div>
</div>
