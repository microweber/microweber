<div class="col-md-{{ $settings['field_size'] }}">
    @foreach($data['options'] as $key => $value)
        @if($settings['show_label'])
            <label class="col-form-label" for="inputDefault">{{ __($value) }}</label>
        @endif

        @if($key == 'address')
            @if($data['countries'])
                <div class="mw-text-start my-2">
                    <select class="form-select">
                        <option>{{ __('Choose address') }}</option>
                        @foreach($data['countries'] as $country)
                            <option value="{{ $country }}">{{ $country }}</option>
                        @endforeach
                    </select>
                </div>
            @else
                <input type="text" 
                    class="mw-ui-field" 
                    name="{{ $data['name'] }}[{{ $key }}]" 
                    @if($settings['required']) required @endif
                    data-custom-field-id="{{ $data['id'] }}" />
            @endif
        @else
            <div class="mw-text-start my-2">
                <input type="text" 
                    class="form-control" 
                    name="{{ $data['name'] }}[{{ $key }}]" 
                    @if($settings['required']) required @endif
                    data-custom-field-id="{{ $data['id'] }}" 
                    placeholder="" 
                    id="inputDefault" />
            </div>
        @endif
    @endforeach
</div>
