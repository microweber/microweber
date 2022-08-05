<?php
$randomId = uniqid();
?>
<div class="card-header bg-white px-1">
    <div data-bs-toggle="collapse" data-bs-target="#collapse_{{$randomId}}"  aria-expanded="true" class="d-flex">
        <h5 class="title"><?php _e('Active filters') ?></h5>
        <i class="mdi mdi-plus ms-auto align-self-center"></i>
    </div>
</div>

<div class="collapse show" id="collapse_{{$randomId}}">
    <div class="card-body px-1">
        @foreach($filtersActive as $filter)
           <button type="button" data-key="{{$filter->key}}" data-value="{{$filter->value}}" class="btn btn-primary btn-sm mb-2 px-1 js-filter-picked"> &nbsp;&nbsp; {{$filter->name}} <i class="mdi mdi-close ml-2"></i></button>
        @endforeach
    </div>
</div>
