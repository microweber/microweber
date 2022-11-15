<div>

    @php
    $activeClass = '';
    if ($selectField == 'categories'
        || $selectField == 'id'
        || $selectField == 'parent'
        || $selectField == 'parent_id'
        || $selectField == 'is_active'
        || $selectField == 'tags'
        || $selectField == 'category_ids'
        || $selectField == 'media_urls'
        || $selectField == 'custom_content_data') {
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

        @if ($selectField == 'id')
            <p class="text-success"><i class="fa fa-info"></i> The feed item id will be mapped with the same content id in website database.</p>
        @endif

        @if ($selectField == 'parent' || $selectField == 'parent_id')
            <p class="text-success"><i class="fa fa-info"></i> The feed item parent id will be mapped with the same content parent id in website database.</p>
        @endif

        @if ($selectField == 'tags')
            <select class="form-control mt-2" wire:model="tagsSeparator">
                <option value="">No seperation</option>
                @foreach(\MicroweberPackages\Modules\Admin\ImportExportTool\ImportMapping\Readers\ItemMapReader::$categorySeparators as $separator)
                    <option value="{{$separator}}">Separate with "{{$separator}}"</option>
                @endforeach
            </select>
        @endif

        @if ($selectField == 'is_active')
            <p class="text-success">
                <i class="fa fa-info"></i> This will be parsed as "active" when item value is a: [1,enabled,true,active or yes]
            </p>
        @endif

        @if ($selectField == 'category_ids')
            <select class="form-control mt-2" wire:model="categoryIdSeparator">
                <option value="">No seperation</option>
                @foreach(\MicroweberPackages\Modules\Admin\ImportExportTool\ImportMapping\Readers\ItemMapReader::$categorySeparators as $separator)
                    <option value="{{$separator}}">Separate with "{{$separator}}"</option>
                @endforeach
            </select>
            <p class="text-success"><i class="fa fa-info"></i> The feed category ids will be mapped with the same category id in website database.</p>
        @endif

        @if ($selectField == 'media_urls')
            <select class="form-control mt-2" wire:model="mediaUrlSeparator">
                <option value="">No seperation</option>
                @foreach(\MicroweberPackages\Modules\Admin\ImportExportTool\ImportMapping\Readers\ItemMapReader::$categorySeparators as $separator)
                    <option value="{{$separator}}">Separate with "{{$separator}}"</option>
                @endforeach
            </select>
        @endif

        @if ($selectField == 'categories')

            <select class="form-control mt-2" wire:model="categorySeparator">
                <option value="">No seperation</option>
                @foreach(\MicroweberPackages\Modules\Admin\ImportExportTool\ImportMapping\Readers\ItemMapReader::$categorySeparators as $separator)
                    <option value="{{$separator}}">Separate with "{{$separator}}"</option>
                @endforeach
            </select>

           <select class="form-control mt-2" wire:model="categoryAddType">
                <option value="">Select</option>
                <option value="seperated">Add categories seperatred</option>
                <option value="tree">Add categories in tree</option>
            </select>

        @endif

        @if ($selectField == 'custom_content_data')
         <input type="text" wire:model="customContentData" placeholder="Please enter content data key" class="form-control mt-2" />
        @endif

    </div>

</div>
