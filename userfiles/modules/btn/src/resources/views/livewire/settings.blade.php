<div>

    <ul class="nav nav-tabs">
        <li class="nav-item" wire:ignore>
            <a href="#text" class="nav-link active" data-bs-toggle="tab">Text</a>
        </li>

        <li class="nav-item" wire:ignore>
            <a href="#design" class="nav-link" data-bs-toggle="tab">Design</a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade show active" wire:ignore.self id="text">


            <div class="mb-3">
                <label class="form-label">Button Text</label>
                <input type="text" class="form-control mb-2" wire:model.debounce.100ms="settings.text"
                       placeholder="Text..">

            </div>
            <script>
                document.addEventListener('livewire:load', function () {
                    mw.app.linkPicker.on('selected', function (data) {

                        if (!data) {
                            return
                        }

                        if ((data.type) && data.type === 'category') {
                            @this.set('settings.url_to_category_id', data.id);
                            @this.set('settings.url_to_content_id', '');
                            @this.set('settings.url', '');
                        } else if ((data.type) && (data.type === 'content')) {
                            @this.set('settings.url_to_content_id', data.id);
                            @this.set('settings.url_to_category_id', '');
                            @this.set('settings.url', '');
                        } else {
                            @this.set('settings.url', data.url);
                            @this.set('settings.url_to_content_id', '');
                            @this.set('settings.url_to_category_id', '');
                        }

                        $('#display-url').val(data.url);
                    });
                })
            </script>




            <div class="mb-3">
                <label class="form-label"><?php _e("Edit url"); ?></label>
                <small class="text-muted d-block mb-3"><?php _e('Link settings for your url.');?></small>
                <button class="btn btn-secondary btn-sm btn-rounded" onclick="mw.app.linkPicker.selectLink('#display-url')"><i class="mdi mdi-link"></i> <?php _e("Edit link"); ?></button>

                <input id="display-url" onclick="mw.app.linkPicker.selectLink('#display-url')" class="form-control-plaintext" type="text" placeholder="Url ..." value="{{ $url }}">

                <input type="hidden" wire:model.debounce.100ms="settings.url">
                <input type="hidden" wire:model.debounce.100ms="settings.url_to_content_id">
                <input type="hidden" wire:model.debounce.100ms="settings.url_to_category_id">

            </div>

        </div>

        <div class="tab-pane fade" wire:ignore.self id="design">


            <div class="mw-btn-settings-align-controls mt-4">
                <div>
                    <livewire:live-edit::module-select-template :moduleId="$moduleId" :moduleType="$moduleType"/>
                </div>
                <div>
                    <label class="form-label">Align</label>
                    <div class="btn-group" role="group" aria-label="Button Align">
                        <button wire:click="$set('settings.align', 'left')" type="button"
                                class="btn btn-primary @if($settings['align'] == 'left'): active @endif"><i
                                class="fa fa-align-left"></i>
                        </button>
                        <button wire:click="$set('settings.align', 'center')" type="button"
                                class="btn btn-primary @if($settings['align'] == 'center'): active @endif"><i
                                class="fa fa-align-center"></i>
                        </button>
                        <button wire:click="$set('settings.align', 'right')" type="button"
                                class="btn btn-primary @if($settings['align'] == 'right'): active @endif"><i
                                class="fa fa-align-right"></i>
                        </button>
                    </div>
                </div>

                <x-mw-ui::icon-picker wire:model="settings.icon" :value="$settings['icon']"/>

            </div>
        </div>
    </div>


</div>

