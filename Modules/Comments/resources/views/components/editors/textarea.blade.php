<div>
    <textarea 
        wire:model="{{ $model }}" 
        @isset($autofocus) autofocus @endisset 
        class="form-control"
        placeholder="{{ $placeholder }}"
    ></textarea>
</div>
