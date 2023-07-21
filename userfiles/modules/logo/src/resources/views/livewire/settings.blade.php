<div>
    <div>
        <div x-data="{'logoType': '{{get_option('logotype', $moduleId)}}'}" class="card-body" style="padding:5px;padding-bottom:25px;">

            <div @mw-option-saved.window="function() {
                if ($event.detail.optionKey == 'logotype') {
                    logoType = $event.detail.optionValue;
                }
                if ($event.detail.optionGroup === '{{$moduleId}}') {
                    mw.top().reload_module_everywhere('{{$moduleType}}');
                }
                }">
            </div>

            <div>
                @php
                    $logoTypeOptions = [
                        'image' => 'Image',
                        'text' => 'Text',
                    ];
                @endphp
                <livewire:microweber-option::radio-modern :options="$logoTypeOptions" optionKey="logotype" :optionGroup="$moduleId" :module="$moduleType"  />
            </div>

            <div x-show="logoType == 'image'">
                <div>
                    <label class="live-edit-label"><?php _e("Main Logo"); ?></label>
                    <small class="text-muted d-block mb-2"><?php _e("This logo image will appear every time"); ?></small>
                    <livewire:microweber-option::media-picker optionKey="logoimage" :optionGroup="$moduleId" :module="$moduleType"  />
                </div>

                <div>
                    <livewire:microweber-option::range-slider labelUnit="px" min="8" max="500" label="Logo Image - Size" optionKey="size" :optionGroup="$moduleId" :module="$moduleType"  />
                </div>

                <div>
                    <label class="live-edit-label"><?php _e("Inverse Logo"); ?></label>
                    <small class="text-muted d-block mb-2"><?php _e("This inverse logo image will appear on black theme"); ?></small>
                    <livewire:microweber-option::media-picker optionKey="logoimage_inverse" :optionGroup="$moduleId" :module="$moduleType"  />
                </div>
            </div>


            <div x-show="logoType == 'text'">
                <div>
                    <label class="live-edit-label"><?php _e("Logo Text"); ?></label>
                    <small class="text-muted d-block mb-2"><?php _e("This logo text will appear when image not applied"); ?></small>
                    <livewire:microweber-option::text optionKey="text" :optionGroup="$moduleId" :module="$moduleType"  />
                </div>

                <div class="mt-3">
                    <label class="live-edit-label"><?php _e("Font Family Text"); ?></label>
                    <small class="text-muted d-block mb-2"><?php _e("Select font family for your logo"); ?></small>

                    <livewire:microweber-option::font-picker label="Select font" optionKey="font_family" :optionGroup="$moduleId" :module="$moduleType"  />
                </div>

                <div class="mt-3">
                    <livewire:microweber-option::range-slider labelUnit="px" min="8" max="45" label="Logo Text - Size" optionKey="font_size" :optionGroup="$moduleId" :module="$moduleType"  />
                </div>
            </div>

        </div>
    </div>

</div>
