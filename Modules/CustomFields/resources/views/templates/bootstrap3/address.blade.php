<div class="col-md-{{ $settings['field_size'] }}">
    <div class="mw-ui-field-holder">
        @if($settings['show_label'])
            <label class="form-label">
                {{ $data['name'] }}
                @if($settings['required'])
                    <span style="color: red;">*</span>
                @endif
            </label>
        @endif

        @foreach($data['values'] as $key => $value)
            <div class="form-group">
                @if($settings['show_label'])
                    <label class="form-label">
                        {{ $value }}
                        @if($settings['required'])
                            <span style="color:red;">*</span>
                        @endif
                    </label>
                @endif

                @if($key == 'country')
                    @if($data['countries'])
                        <select name="{{ $data['name'] }}[country]" class="form-control">
                            <option value="">{{ __('Choose country') }}</option>
                            @foreach($data['countries'] as $country)
                                <option value="{{ $country }}">{{ $country }}</option>
                            @endforeach
                        </select>
                    @else
                        <input type="text" 
                            class="form-control" 
                            @if(!$settings['show_label']) placeholder="{{ $value }}" @endif
                            name="{{ $data['name'] }}[{{ $key }}]"
                            @if($settings['required']) required @endif
                            data-custom-field-id="{{ $data['id'] }}"/>
                    @endif
                @else
                    <input type="text" 
                        class="form-control" 
                        @if(!$settings['show_label']) placeholder="{{ $value }}" @endif
                        name="{{ $data['name'] }}[{{ $key }}]"
                        @if($settings['required']) required @endif
                        data-custom-field-id="{{ $data['id'] }}"/>
                @endif
            </div>
        @endforeach

        @if($data['help'])
            <span class="help-block">{{ $data['help'] }}</span>
        @endif
    </div>
</div>
