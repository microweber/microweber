@props(['value' => ''])

@php
    $randId = time() . rand(111,999);
@endphp

<div
    x-data="{}"
    x-init="() => {
        let el = document.querySelector('#btn-icon-pick-{{$randId}}');
        let iconPreview = document.querySelector('#btn-icon-preview-{{$randId}}');
        let iconDelete = document.querySelector('#btn-icon-delete-{{$randId}}');
        iconDelete.addEventListener('click', function (event) {
            el.value = '';
            iconPreview.style.display = 'none';
            iconDelete.style.display = 'none';
        })
        el.addEventListener('input', ()=> {
            if (el.value) {
                iconPreview.innerHTML = el.value;
                iconPreview.style.display = 'flex';
                iconDelete.style.display = 'block';
            } else {
                iconPreview.style.display = 'none';
                iconDelete.style.display = 'none';
            }
        });
        if (el.value) {
            iconPreview.innerHTML = el.value;
            iconPreview.style.display = 'flex';
            iconDelete.style.display = 'block';
        } else {
            iconPreview.style.display = 'none';
            iconDelete.style.display = 'none';
        }
    }"

    class="microweber-ui-icon-picker">
    <div class="form-control-live-edit-label-wrapper d-flex justify-content-between flex-wrap gap-2">

        <div class="d-flex justify-content-start gap-2 col-6">
            <button class="btn" x-on:click="()=> {
                        mw.app.iconPicker.selectIcon('#btn-icon-pick-{{$randId}}');
                    }"
                type="button">
                <?php _e("Select Icon"); ?>
            </button>

            <button type="button" style="display:none" id="btn-icon-preview-{{$randId}}" class="btn text-center col-1" x-on:click="()=> {
                        mw.app.iconPicker.selectIcon('#btn-icon-pick-{{$randId}}');
                    }" style="font-size:24px; background-color: #f5f5f5;">
                Loading...
            </button>
        </div>

        <button style="display:none" id="btn-icon-delete-{{$randId}}" class="btn border-0 col-auto px-1"

                x-on:click="()=> {
                        mw.app.iconPicker.removeIcon('#btn-icon-pick-{{$randId}}');
                    }"
                 type="button">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 256 256"><path fill="currentColor" d="M216 52h-44V40a20 20 0 0 0-20-20h-48a20 20 0 0 0-20 20v12H40a4 4 0 0 0 0 8h12v148a12 12 0 0 0 12 12h128a12 12 0 0 0 12-12V60h12a4 4 0 0 0 0-8ZM92 40a12 12 0 0 1 12-12h48a12 12 0 0 1 12 12v12H92Zm104 168a4 4 0 0 1-4 4H64a4 4 0 0 1-4-4V60h136Zm-88-104v64a4 4 0 0 1-8 0v-64a4 4 0 0 1 8 0Zm48 0v64a4 4 0 0 1-8 0v-64a4 4 0 0 1 8 0Z"/></svg>
        </button>

    </div>
    <textarea style="display:none"  {!! $attributes->merge([]) !!} id="btn-icon-pick-{{$randId}}"></textarea>
</div>
