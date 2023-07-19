@props(['options'=>[]])

<div class="form-control-live-edit-label-wrapper d-flex mw-live-edit-resolutions-wrapper" style="max-height: unset; gap: 5px !important;">


    @if(!empty($options))
        @foreach($options as $key => $option)
            
            <button type="button" class="btn btn-icon live-edit-toolbar-buttons w-100 active">
                {{$option}}
            </button>

        @endforeach
    @endif

</div>


