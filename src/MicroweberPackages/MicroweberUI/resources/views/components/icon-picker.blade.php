@props(['value' => false])

<div class="microweber-ui-icon-picker">
    <div class="btn-group d-flex">
        <button class="btn btn-white">
            {!! $value !!}
        </button>
        <button class="btn btn-white" onclick="mw.app.iconPicker.selectIcon('#btn-icon-pick')" type="button">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M15.15 21.375q-.575.275-1.15.063t-.85-.788l-3-6.45l-2.325 3.25q-.425.6-1.125.375t-.7-.95V4.05q0-.625.563-.9t1.062.125l10.1 7.95q.575.425.338 1.1T17.1 13h-4.2l2.975 6.375q.275.575.063 1.15t-.788.85Z"/></svg>

            <?php _e("Select Icon"); ?>
        </button>

        @if(!empty($value))
        <button class="btn btn-white" onclick="mw.app.iconPicker.removeIcon('#btn-icon-pick')" type="button">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 256 256"><path fill="currentColor" d="M216 52h-44V40a20 20 0 0 0-20-20h-48a20 20 0 0 0-20 20v12H40a4 4 0 0 0 0 8h12v148a12 12 0 0 0 12 12h128a12 12 0 0 0 12-12V60h12a4 4 0 0 0 0-8ZM92 40a12 12 0 0 1 12-12h48a12 12 0 0 1 12 12v12H92Zm104 168a4 4 0 0 1-4 4H64a4 4 0 0 1-4-4V60h136Zm-88-104v64a4 4 0 0 1-8 0v-64a4 4 0 0 1 8 0Zm48 0v64a4 4 0 0 1-8 0v-64a4 4 0 0 1 8 0Z"/></svg>
            <?php _e("Remove"); ?>
        </button>
        @endif

    </div>
    <textarea style="display: none" id="btn-icon-pick" {{ $attributes->merge([]) }} ></textarea>
</div>
