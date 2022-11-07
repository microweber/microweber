<div>

    @php
    $activeClass = '';
    if ($selectField == 'categories' || $selectField == 'custom_content_data') {
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

            <select class="form-control mt-2" wire:model="category_separator">
                @foreach(\MicroweberPackages\Modules\Admin\ImportExportTool\ImportMapping\Readers\ItemMapReader::$categorySeparators as $separator)
                    <option value="{{$separator}}">Separate with "{{$separator}}"</option>
                @endforeach
            </select>

           <select class="form-control mt-2">
                <option value="">Select</option>
                <option value="seperated">Add categories seperatred</option>
                <option value="tree">Add categories in tree</option>
            </select>

        @endif

        @if ($selectField == 'custom_content_data')
         <input type="text" placeholder="Please enter content data key" class="form-control mt-2" />
        @endif

    </div>

</div>
