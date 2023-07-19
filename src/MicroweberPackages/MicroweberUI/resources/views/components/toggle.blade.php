@props(['disabled' => false])

<div class="form-control-live-edit-label-wrapper">
    <label class="form-switch">
        <div>
            <input type="checkbox" class="form-check-input" {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge() !!} />
        </div>
        <div>
            {{$slot}}
        </div>
    </label>
</div>
