<div class="col-md-{{ $settings['field_size'] }}">
    @foreach($data['options'] as $key => $value)
        @if($settings['show_label'])
            <label class="col-form-label" for="inputDefault">{{ __($value) }}</label>
        @endif

        @if($key == 'address')
            @if($data['countries'])
                <div class="mb-3 d-flex gap-3 flex-wrap">
                    <select class="form-control">
                        <option>{{ __('Choose address') }}</option>
                        @foreach($data['countries'] as $country)
                            <option value="{{ $country }}">{{ $country }}</option>
                        @endforeach
                    </select>
                </div>
            @else
                <div class="mb-3 d-flex gap-3 flex-wrap">
                    <input type="text" 
                        class="mw-ui-field" 
                        name="{{ $data['name'] }}[{{ $key }}]" 
                        @if($settings['required']) required @endif 
                        data-custom-field-id="{{ $data['id'] }}"
                    />
                </div>
            @endif
        @else
            <div class="mb-3 d-flex gap-3 flex-wrap">
                <input type="text" 
                    class="form-control" 
                    name="{{ $data['name'] }}[{{ $key }}]" 
                    @if($settings['required']) required @endif 
                    data-custom-field-id="{{ $data['id'] }}" 
                    placeholder="" 
                    id="inputDefault"
                />
            </div>
        @endif
    @endforeach
</div>
