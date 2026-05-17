{{--
type: layout
name: Button gallery
description: Button gallery
--}}

@if(isset($data))
    @php
        $rand = uniqid();
    @endphp

    <div class="mw-module-images{{ isset($no_img) && $no_img ? ' no-image' : '' }}">
        <div class="mw-pictures-clean" id="mw-gallery-{{ $rand }}">
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
                @if($count == 1)
                    <a href="{{ isset($item['filename']) ? $item['filename'] : '' }}" data-mw-gallery="@php echo base64_encode(json_encode(array_map(function ($it) { return ['image' => $it['filename'] ?? '', 'description' => $it['title'] ?? '']; }, $data ?? []))); @endphp" data-mw-gallery-index="{{ $count }}" class="btn btn-default">{{ _e("View photos") }}</a>
                @endif
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
