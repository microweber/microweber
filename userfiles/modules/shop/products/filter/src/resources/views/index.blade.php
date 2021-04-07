<div class="js-shop-product-filter">
    <form method="get" action="" class="form-horizontal">
        <div class="row">

            @include('productFilter::filters/price')
            @include('productFilter::filters/limit')
            @include('productFilter::filters/order')

            @foreach($filters as $filterKey=>$filter)

            <div class="col-md-12 pb-3">

               @if ($filter['type'] == 'dropdown' || $filter['type'] == 'radio')

                <b>{{$filter['name']}}</b>

                @foreach($filter['options'] as $options)
                @php
                $checked = false;
                if (!empty($customFields)) {
                    foreach ($customFields as $customFieldKey=>$customFieldValues) {
                        foreach($customFieldValues as $customFieldValue) {
                            if ($customFieldKey == $filterKey && $customFieldValue == $options['value']) {
                                $checked = true;
                            }
                        }
                    }
                }
                @endphp

                <div class="form-check">
                    <input class="form-check-input" @if ($checked) checked @endif type="checkbox" name="customFields[{{$filterKey}}][]" value="{{$options['value']}}" id="customFieldFilterCheck{{$options['id']}}">
                    <label class="form-check-label" for="customFieldFilterCheck{{ $options['id'] }}">
                        {{ $options['value'] }}
                    </label>
                </div>
                @endforeach
                @endif
            </div>

            @endforeach

            <div class="col-md-12">
                <button type="submit" class="btn btn-primary btn-block mt-2"><i class="fa fa-filter"></i> Filter</button>
            </div>
        </div>
    </form>
</div>
