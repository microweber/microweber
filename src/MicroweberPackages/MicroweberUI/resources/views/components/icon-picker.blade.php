@props(['value' => false])

<div class="microweber-ui-icon-picker">
    <div>
        {!! $value !!}
        <button class="btn btn-dark btn-sm" onclick="mw.app.iconPicker.selectIcon('#btn-icon-pick')" type="button">
            <?php _e("Select Icon"); ?>
        </button>
        <button class="btn btn-dark btn-sm" onclick="mw.app.iconPicker.removeIcon('#btn-icon-pick')" type="button">
            <?php _e("Remove icon"); ?>
        </button>
        <textarea style="display: none" id="btn-icon-pick" {{ $attributes->merge([]) }} ></textarea>
    </div>
</div>
