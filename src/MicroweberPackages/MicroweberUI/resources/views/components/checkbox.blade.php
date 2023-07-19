@props(['options'=>[]])

<div class="form-control-live-edit-label-wrapper">
    @if(!empty($options))
        @foreach($options as $key => $option)
            <label class="form-check">
                <input class="form-check-input" type="checkbox">
                <span class="form-check-label">{{$option}}</span>
            </label>
        @endforeach
    @endif

</div>
