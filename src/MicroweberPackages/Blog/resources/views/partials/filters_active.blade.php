<?php
$randomId = uniqid();
?>
<div class="card-header bg-white">
    <div data-toggle="collapse" data-target="#collapse_{{$randomId}}" aria-expanded="true" class="d-flex">
        <h5 class="title"><?php _e('Active filters') ?></h5>
        <i class="mdi mdi-plus ml-auto align-self-center"></i>
    </div>
</div>

<div class="collapse show" id="collapse_{{$randomId}}">
    <div class="card-body">
        @foreach($filtersActive as $filter)
           <button type="button" data-key="{{$filter->key}}" data-value="{{$filter->value}}" class="btn btn-outline-primary btn-sm mb-2 js-filter-picked"><i class="fa fa-times-circle"></i> &nbsp;&nbsp; {{$filter->name}} </button>
        @endforeach
    </div>
</div>
