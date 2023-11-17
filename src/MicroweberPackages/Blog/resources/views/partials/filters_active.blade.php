<?php
$randomId = uniqid();
?>

<h5 class="title"><?php _e('Active filters') ?></h5>


<div class="card-body px-1">
    @foreach($filtersActive as $filter)
       <button type="button" data-key="{{$filter->key}}" data-value="{{$filter->value}}" class="btn btn-outline-primary mb-2 btn-sm px-1 js-filter-picked"> &nbsp;&nbsp; {{$filter->name}}
           <svg class="me-2" style="position: relative; bottom: 2px;" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="18" viewBox="0 -960 960 960" width="18"><path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/></svg>
       </button>
    @endforeach
</div>

