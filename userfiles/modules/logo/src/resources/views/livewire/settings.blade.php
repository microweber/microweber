<div>

    <div class="card">
        <div class="card-body" style="padding:5px">

            <div class="form-group">
                <label class="form-label font-weight-bold"><?php _e("Main Logo"); ?></label>
                <small class="text-muted d-block mb-2"><?php _e("This logo image will appear every time"); ?></small>
            </div>

            <div>
                <label class="live-edit-label">Logo</label>
                <livewire:microweber-option::media-picker label="Add logo" optionKey="logoimage" :optionGroup="$moduleId" :module="$moduleType"  />
            </div>

            <br />
            <br />

        </div>
    </div>

</div>
