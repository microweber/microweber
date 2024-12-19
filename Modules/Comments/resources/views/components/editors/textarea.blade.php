<div>
    <textarea 
        wire:model.defer="{{ $model }}" 
        @isset($autofocus) autofocus @endisset 
        class="form-control"
        placeholder="{{ $placeholder }}"
    ></textarea>
</div>
