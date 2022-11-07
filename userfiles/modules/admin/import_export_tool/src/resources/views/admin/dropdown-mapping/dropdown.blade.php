<div>

    @php
    $activeClass = '';
    if ($selectField == 'categories') {
        $activeClass = 'active';
    }
    @endphp

    <div class="js-dropdown-select-wrapper {{$activeClass}}">
        <select class="form-control" wire:model="selectField">

            <option value="none">Select</option>

            @foreach($dropdowns as $groupName=>$groupItems)
                <optgroup label="{{$groupName}}">
                    @foreach($groupItems as $groupItem)
                    <option value="{{$groupItem['value']}}">{{$groupItem['name']}}</option>
                    @endforeach
                </optgroup>
            @endforeach

            <optgroup label="Add as new field">
                <option value="custom_content_data">Content Data</option>
            </optgroup>
        </select>

        @if ($selectField == 'categories')
           <input type="text" placeholder="Please enter category seperator symbol" class="form-control mt-2" />
           <select class="form-control mt-2" />
            <option class="">Select</option>
            <option class="seperated">Add as seperatred</option>
            <option class="seperated">Add with childs</option>
            </select>';

        @endif

        <input type="text" placeholder="Please enter content data key" class="form-control mt-2" />


    </div>

</div>
