<form class="js-filter-form" method="get">
@foreach($filters as $filterKey=>$filter)
    @include('blog::partials.filters.' . $filter->controlType)
@endforeach
</form>
