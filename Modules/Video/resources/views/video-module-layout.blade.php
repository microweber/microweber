@if(!$upload && !$code)
    {!! lnotif('Click to edit video') !!}
@else
    <div class="mw-prevent-interaction">
        @include('modules.video::templates.default')
    </div>
@endif
