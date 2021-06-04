<?php
$randomId = uniqid();
?>

<div class="card-header">
    <a href="#" data-toggle="collapse" data-target="#collapse_{{$randomId}}" aria-expanded="true" class="">
        <h6 class="title"><?php _e('Search') ?></h6>
        <i class="fa fa-chevron-down" style="float:right;margin-top:-18px;"></i>
    </a>
</div>

<div class="collapse show" id="collapse_{{$randomId}}">
    <div class="card-body">
        <div class="input-group mb-3">
            <input type="text" class="form-control js-filter-search-field" value="{{$search}}" placeholder="<?php _e('Search');?>" />
            <div class="input-group-append">
                <button type="submit" class="btn btn-primary btn-icon pl-3 pr-2 js-filter-search-submit"><i class="fa fa-search"></i></button>
            </div>
        </div>
    </div>
</div>
