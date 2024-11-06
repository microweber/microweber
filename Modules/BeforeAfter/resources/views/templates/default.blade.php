<div class="mw-before-after" id="mw-before-after-{{ $id }}">
    <style>
        #mw-before-after-{{ $id }} .twentytwenty-container {
            min-height: 100px !important;
        }
    </style>

    <img src="{{ $before }}" alt="{{ __('Before image') }}"/>
    <img src="{{ $after }}" alt="{{ __('After image') }}"/>
</div>

<script>
    mw.moduleCSS('{{ asset('modules/before_after/css/twentytwenty.css') }}');
    mw.moduleJS('{{ asset('modules/before_after/js/jquery.event.move.js') }}');
    mw.moduleJS('{{ asset('modules/before_after/js/jquery.twentytwenty.js') }}');

    $(window).on('load', function () {
        mw.$("#mw-before-after-{{ $id }}").twentytwenty({default_offset_pct: 0.5});
    });
    $(document).ready(function () {
        mw.$("#mw-before-after-{{ $id }}").twentytwenty({default_offset_pct: 0.5});
        /*mw.image.preloadForAll(['{{ $before }}', '{{ $after }}'], undefined, function () {
            mw.$("#mw-before-after-{{ $id }}").twentytwenty({default_offset_pct: 0.5});
        });*/
    });
</script>
