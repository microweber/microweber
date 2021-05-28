<?php
$randomId = uniqid();
?>
<div class="card-header">
    <a href="#" data-toggle="collapse" data-target="#collapse_{{$randomId}}" aria-expanded="true" class="">
        <h6 class="title"><?php _e('Active filters') ?></h6>
        <i class="fa fa-chevron-down" style="float:right;margin-top:-18px;"></i>
    </a>
</div>

<div class="collapse show" id="collapse_{{$randomId}}">
    <div class="card-body">
        @foreach($activeFilters as $filterName=>$filter)
           <a href=""><i class="fa fa-times-circle"></i> &nbsp;&nbsp; {{$filter->value}} </a>
            <br />
        @endforeach
    </div>
</div>
