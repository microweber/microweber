<form class="js-filter-form" method="get">
@foreach($filters as $filterKey=>$filter)
    @includeIf('blog::partials.filters.' . $filter->controlType)
@endforeach

    @if($showApplyFilterButton)
    <div class="card-body px-1">
        <button type="button" class="btn btn-success js-filter-apply">{{_e('Apply Filters')}}</button>
    </div>
    @endif

</form>
