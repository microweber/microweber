<?php
$randomId = uniqid();
?>

<h5 class="title"><?php _e('Active filters') ?></h5>


<div class="card-body px-1">
    @foreach($filtersActive as $filter)
       <button type="button" data-key="{{$filter->key}}" data-value="{{$filter->value}}" class="btn btn-primary btn-sm mb-2 px-1 js-filter-picked"> &nbsp;&nbsp; {{$filter->name}} <i class="mdi mdi-close ml-2"></i></button>
    @endforeach
</div>

