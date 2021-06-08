<form class="js-filter-form" method="get">
@foreach($filters as $filterKey=>$filter)
    @include('blog::partials.filters.' . $filter->controlType)
@endforeach

    @if($showApplyFilterButton)
    <div class="card-body">
        <button type="button" class="btn btn-success js-filter-apply">{{_e('Apply Filters')}}</button>
    </div>
    @endif

</form>
