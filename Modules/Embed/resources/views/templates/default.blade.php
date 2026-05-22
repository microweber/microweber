{{-- task-2026-05-22-ce249e / AI-920 — gate output with code_type wrapper tags.
     CSS embeds need <style> so styles apply; JavaScript embeds need <script>
     so code executes; HTML embeds output raw. --}}
<div class="embed-module" id="embed-{{ $params['id'] }}">
    @if(isset($source_code) && $source_code != '')
        @if(($code_type ?? 'html') === 'css')
            <style>{!! $source_code !!}</style>
        @elseif(($code_type ?? 'html') === 'javascript')
            <script>{!! $source_code !!}</script>
        @else
            <div class="embed-content">
                {!! $source_code !!}
            </div>
        @endif
    @else
        <p>{{ __('No embed code provided.') }}</p>
    @endif
</div>
