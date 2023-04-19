@props(['value' => false])

<div>
    <div>icon picker component 1.0.0
        {!! $value !!}
        <button onclick="mw.app.iconPicker.selectIcon('#btn-icon-pick')" type="button">
            <?php _e("Select Icon"); ?>
        </button>
        <button onclick="mw.app.iconPicker.removeIcon('#btn-icon-pick')" type="button">
            <?php _e("Remove icon"); ?>
        </button>
        <textarea id="btn-icon-pick" {{ $attributes->merge([]) }} ></textarea>
    </div>
</div>
