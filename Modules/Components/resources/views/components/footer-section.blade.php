<div {{ $attributes->merge(['class' => 'mw-layout-container no-element container-fluid py-2']) }}>
    <div class="row">
        <div class="col-12 d-sm-flex text-center">
            <div class="col-sm-6 text-md-start text-center edit safe-mode" @if($copyrightField) field="{{ $copyrightField }}-{{ $sectionId }}" rel="module" @endif>
                @if($slot->isNotEmpty())
                    {{ $slot }}
                @else
                    <small>&copy; All Rights Reserved.</small>
                @endif
            </div>
            <div class="col-sm-6 mb-0 noedit text-md-end text-center">
                <small>{!! powered_by_link() !!}</small>
            </div>
        </div>
    </div>
</div>