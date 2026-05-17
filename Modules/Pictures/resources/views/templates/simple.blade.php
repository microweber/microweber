{{--
type: layout
name: Simple
description: Simple Pictures List Template
--}}

@if(isset($data))
    @php
        $rand = uniqid();
    @endphp

    <script>mw.moduleCSS("{{ asset('modules/pictures/css/style.css') }}");</script>

    <div class="mw-module-images{{ isset($no_img) && $no_img ? ' no-image' : '' }}">
        <div class="mw-pictures-list mw-images-template-default-grid" id="mw-gallery-{{ $rand }}">
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
                @foreach($data as $item)
                    @php $count++; @endphp
                    <div class="mw-pictures-item mw-pictures-item-{{ $item['id'] ?? '' }}">
                        <div class="thumbnail"
                             data-mw-gallery="@php echo base64_encode(json_encode(array_map(function ($it) { return ['image' => $it['filename'] ?? '', 'description' => $it['title'] ?? '']; }, $data ?? []))); @endphp" data-mw-gallery-index="{{ $count }}">
                            <span class="pic-valign">
                                <span class="pic-valign-cell">
                                    {{-- task-2026-05-05-d71799 — lazy-load gallery image. --}}
                                    {{-- audit-test 2026-05-08 PM TASK-012 / TICKET-CX (cycle-55): responsive_thumbnail helper. --}}
                                    {!! responsive_thumbnail($item['filename'] ?? '', 300, null, [
                                        'alt' => isset($item['title']) ? $item['title'] : __('Image'),
                                        'class' => 'img-fluid',
                                    ]) !!}
                                </span>
                            </span>
                        </div>
                    </div>
                @endforeach
            @endif

            <script>
                gallery{{ $rand }} = [
                    @foreach($data as $item)
                        {
                            image: "{{ isset($item['filename']) ? $item['filename'] : '' }}",
                            description: "{{ isset($item['title']) ? $item['title'] : '' }}"
                        },
                    @endforeach
                ];
            </script>
        </div>
    </div>
@else
    @include('modules.pictures::partials.no-pictures')
@endif
