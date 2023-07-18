@props(['value' => ''])

@php
    $randId = time() . rand(111,999);
@endphp
<div class="microweber-ui-icon-picker">
    <div class="form-control-live-edit-label-wrapper d-flex justify-content-between flex-wrap gap-2">

        <div class="d-flex justify-content-start gap-2 col-6">
            <button class="btn" onclick="mw.app.iconPicker.selectIcon('#btn-icon-pick-{{$randId}}')" type="button">
                <?php _e("Select Icon"); ?>
            </button>

            @if(!empty($value))
                <button class="btn border-0 text-center col-1" onclick="mw.app.iconPicker.selectIcon('#btn-icon-pick-{{$randId}}')" style="font-size:24px; background-color: #f5f5f5;">
                    {!! $value !!}
                </button>
            @endif
        </div>

        @if(!empty($value))
            <button class="btn border-0 col-auto px-1" onclick="mw.app.iconPicker.removeIcon('#btn-icon-pick-{{$randId}}')" type="button">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 256 256"><path fill="currentColor" d="M216 52h-44V40a20 20 0 0 0-20-20h-48a20 20 0 0 0-20 20v12H40a4 4 0 0 0 0 8h12v148a12 12 0 0 0 12 12h128a12 12 0 0 0 12-12V60h12a4 4 0 0 0 0-8ZM92 40a12 12 0 0 1 12-12h48a12 12 0 0 1 12 12v12H92Zm104 168a4 4 0 0 1-4 4H64a4 4 0 0 1-4-4V60h136Zm-88-104v64a4 4 0 0 1-8 0v-64a4 4 0 0 1 8 0Zm48 0v64a4 4 0 0 1-8 0v-64a4 4 0 0 1 8 0Z"/></svg>
            </button>
        @endif

    </div>
    <textarea style="display:none" {!! $attributes->merge([]) !!} id="btn-icon-pick-{{$randId}}"></textarea>
</div>
