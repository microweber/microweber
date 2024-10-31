<div class="embed-module" id="embed-{{ $params['id'] }}">
    @if(isset($source_code) && $source_code != '')
        <div class="embed-content">
            {!! $source_code !!}
        </div>
    @else
        <p>{{ __('No embed code provided.') }}</p>
    @endif
</div>
