{{--
type: layout
name: Skin-16 for Logos
description: Skin-16 for Logos
--}}

@php
    $rand = uniqid();
@endphp

<script>
    var gallery{{ $rand }} = function (id) {
        var el = mwd.getElementById(id);
        if(el && !el.__gallery) {
            el.__gallery = [];
            Array.from(el.querySelectorAll('a')).forEach(function (link){
                el.__gallery.push({
                    url: link.href
                })
                link.addEventListener('click', function (e){
                    e.preventDefault()
                    mw.gallery(el.__gallery, Number(this.dataset.index || 0));
                })
            })
        }
    }

    $(window).on('load', function () {
        gallery{{ $rand }}('gallery-{{ $rand }}');
    });
    $(document).ready(function () {
        gallery{{ $rand }}('gallery-{{ $rand }}');
    });
</script>

<style>
    .pictures-16 .background-image-holder {
        width: 100%;
        border-radius: 5px;
        transition: .5s;
        background-color: #fff;
    }
    .pictures-16 .background-image-holder:hover {
        transform: perspective(200px) translateZ(20px);
    }
</style>

@if(isset($data))
    <div class="row text-center text-sm-start col-xl-10 mx-auto d-flex justify-content-center justify-content-lg-center pictures-16"
         id="gallery-{{ $rand }}">
        @if(sizeof($data) > 1)
            @php $count = -1; @endphp
            @if(empty($data))
                {{-- task-2026-05-17-525769 / AI-812 — wrap admin-targeted empty-state copy in is_admin() gate;
                     adopt AI-780a typed empty-state pattern (heading + body + CTA pointing to admin_url('media')).
                     Pre-fix the bare <p> rendered "No pictures added. Please add pictures to the module." to
                     anonymous frontend visitors — admin-targeted copy leaked to public surface. --}}
                @if (is_admin())
                    <div class="mw-canvas-empty-state" data-mw-ai780-content-type="picture">
                        <h3 class="mw-canvas-empty-state__title">{{ __('No pictures yet') }}</h3>
                        <p class="mw-canvas-empty-state__body">{{ __('Add your first picture to fill this gallery.') }}</p>
                        <a class="mw-canvas-empty-state__cta" href="{{ admin_url('media') }}" aria-label="{{ __('+ Add picture') }}">{{ __('+ Add picture') }}</a>
                    </div>
                @endif
            @else
                {{-- audit-test 2026-05-07 PM TICKET-AV bundle: `<div style="background-image: url(...)">`
                     was a CSS-injection sink; migrated to `<img>` with object-fit:contain to preserve
                     the prior background-size:contain visual. Hover transform CSS rule above continues to
                     work since it targets the parent .background-image-holder div which still wraps the img. --}}
                @foreach($data as $item)
                    @php $count++; @endphp
                    <div class="col-sm-6 col-md-4 col-lg-3 pb-3 px-2">
                        <a data-index="{{ $count }}"
                           href="{{ $item['filename'] ?? '' }}">
                            <div class="background-image-holder mh-200">
                                {{-- audit-test 2026-05-08 PM TASK-012 / TICKET-CX (cycle-55): responsive_thumbnail helper. --}}
                                {!! responsive_thumbnail($item['filename'] ?? '', 800, 800, [
                                    'alt' => __('Product image'),
                                    'class' => 'd-block w-100 h-100',
                                    'style' => 'object-fit: contain;',
                                ]) !!}
                            </div>
                        </a>
                    </div>
                @endforeach
            @endif
        @endif
    </div>
@endif
