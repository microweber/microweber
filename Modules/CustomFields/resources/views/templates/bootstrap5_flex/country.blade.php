<div class="col-sm-{{ $settings['field_size_mobile'] }} col-md-{{ $settings['field_size_tablet'] }} col-lg-{{ $settings['field_size_desktop'] }}">
    <div class="mb-3 d-flex gap-3 flex-wrap">
        @if($settings['show_label'])
            <label class="form-label">
                {{ $data['name'] }}
                @if($settings['required'])
                    <span style="color: red;">*</span>
                @endif
            </label>
        @endif

        <select class="form-control">
            <option>{{ __($data['placeholder']) }}</option>
            @foreach($data['values'] as $country)
                <option value="{{ $country }}">{{ $country }}</option>
            @endforeach
        </select>

        @if($data['help'])
            <small class="form-text text-muted">{{ $data['help'] }}</small>
        @endif
    </div>
</div>
