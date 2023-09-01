<div wire:ignore>

  @php
      $selectedRange = '';
      if(isset($this->state['settings'])) {
           $selectedRange = $this->state['settings'][$this->optionKey];
       }
  @endphp

    <x-microweber-ui::range-slider

        label="{{$label}}"
        labelUnit="{{$labelUnit}}"
        min="{{$min}}"
        max="{{$max}}"
        :selectedRange="$selectedRange"
        wire:model.debounce.100ms="state.settings.{{ $this->optionKey }}"

    />

</div>
