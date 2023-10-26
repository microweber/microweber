<?php
$randomId = uniqid();
?>


    <h6>{{$filter->controlName}}</h6>


    <div class="card-body px-1 filter-max-scroll">
        <div class="mb-3">
            <div data-bs-toggle="filter-buttons">
                @foreach($filter->options as $option)
                    @php
                        $randIdForCheck = uniqid();
                    @endphp
                    <label class="btn px-0 mx-1 btn-sm border rounded-circle @if ($option->active) active @endif" for="{{$randIdForCheck}}" style="width: 30px; height: 30px; background:{{$option->value}};">
                        <input type="checkbox" autocomplete="off" class="js-filter-option-select hidden" id="{{$randIdForCheck}}" @if ($option->active) checked @endif name="filters[{{$filterKey}}][]" value="{{$option->value}}">
                    </label>
                @endforeach
            </div>
        </div>

    </div>

