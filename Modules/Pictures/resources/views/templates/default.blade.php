{{--
type: layout
name: Default
description: Default Picture List
--}}

@if(isset($data))
    @php
        $rand = uniqid();
    @endphp

    <script>mw.moduleCSS("{{ asset('modules/pictures/css/clean.css') }}");</script>

    <div class="mw-module-images{{ isset($no_img) && $no_img ? ' no-image' : '' }}">
        <div class="mw-pictures-clean" id="mw-gallery-{{ $rand }}">
            @php $count = -1; @endphp

            @if(empty($data))
                <p class="mw-pictures-clean">No pictures added. Please add pictures to the module.</p>
            @else
                @foreach($data as $item)
                    @php $count++; @endphp
                    @if(!isset($item['id']))
                        @continue
                    @endif

                    <div class="mw-pictures-clean-item mw-pictures-clean-item-{{ $item['id'] }}">
                        <a href="{{ isset($item['filename']) ? $item['filename'] : '' }}"
                           data-mw-gallery="@php echo base64_encode(json_encode(array_map(function ($it) { return ['image' => $it['filename'] ?? '', 'description' => $it['title'] ?? '']; }, $data ?? []))); @endphp" data-mw-gallery-index="{{ $count }}">
                            {{--
                                img-fluid (Bootstrap 5 helper for
                                max-width:100%; height:auto) lets the
                                rendered image scale down with its column
                                instead of bursting out of narrow
                                containers at its native pixel size.
                            --}}
                            {{-- task-2026-05-05-90021f — drunk-designer audit (pictures.md QW): default lazy-loading on every gallery image. --}}
                            {{-- audit-test 2026-05-08 PM TASK-012 / TICKET-CX (cycle-55): responsive_thumbnail helper. --}}
                            {!! responsive_thumbnail($item['filename'] ?? '', 600, null, ['alt' => __('Image'), 'class' => 'img-fluid']) !!}
                        </a>
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
@endif


