<div class="form-control-live-edit-label-wrapper">

    @php
    $options = \MicroweberPackages\Utils\Misc\GoogleFonts::getEnabledFonts();
    @endphp

    <select {!! $attributes->merge(['class'=>'form-select form-control-live-edit-input']) !!} >
        <option value="" disabled="disabled">Select Font</option>
        @if(!empty($options))
            @foreach($options as $option)
                <option value="{{ $option }}">{{ $option }}</option>
            @endforeach
        @endif
    </select>

    <div class="mt-1 mb-3">
        <button type="button" class="btn btn-outline-dark btn-sm" onclick="Livewire.emit('openModal', 'font-picker-modal')">
            <i class="mdi mdi-format-font"></i> Add More Fonts
        </button>
    </div>
</div>
