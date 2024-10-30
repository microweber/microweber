@if ($accordion->count() == 0)
    {{ lnotif(_e('Click to edit accordion', true)) }}
@else

<script>
    $(document).ready(function() {

        function toggleChevron(e) {
            $(e.target)
                .prev('.card-header')
                .find("i.mdi.arrow")
                .toggleClass('mdi-chevron-down mdi-chevron-up')
                .toggleClass('active')
        }
        $('#accordion').on('hidden.bs.collapse', toggleChevron);
        $('#accordion').on('shown.bs.collapse', toggleChevron);

        $(".card").click(function() {
            $(".card").removeClass("active");
            $(this).addClass("active");
        });

    })
</script>

<div id="mw-accordion-module-{{ $params['id'] }}">
    <div class="accordion" id="accordion">

        @foreach ($json as $key => $slide)
            @php
                $edit_field_key = $key;
                if (isset($slide['id'])) {
                    $edit_field_key = $slide['id'];
                }
            @endphp
            <div class="card card-collapse mb-3 @if ($key == 0) active @endif">
                <div class="card-header p-0" id="header-item-{{ $edit_field_key }}">
                    <button class="btn" data-bs-toggle="collapse" data-bs-target="#collapse-accordion-item-{{ $edit_field_key . '-' . $key }}" aria-expanded="true" aria-controls="collapse-accordion-item-{{ $edit_field_key . '-' . $key }}">
                        {{-- module icon --}}
                        {{-- {{ isset($slide['icon']) ? $slide['icon'] . ' ' : '' }} --}}
                        <h4> {{ isset($slide['title']) ? $slide['title'] : '' }} </h4>
                        <i class="mdi arrow border rounded-circle ml-auto mr-0 @if ($key == 0) mdi-chevron-up active @else mdi-chevron-down @endif"></i>
                    </button>
                </div>
                <div id="collapse-accordion-item-{{ $edit_field_key . '-' . $key }}" class="collapse @if ($key == 0) show @endif" aria-labelledby="header-item-{{ $edit_field_key }}" data-parent="#mw-accordion-module-{{ $params['id'] }}">
                    <div class="card-body px-5 pt-0 pb-5">
                        @include('modules.accordion.templates.partials.render_accordion_item_content')
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

@endif
