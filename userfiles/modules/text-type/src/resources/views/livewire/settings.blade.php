<div>

    <div class="card-body" style="padding:5px;padding-bottom:25px;">
        <div>
            <label class="live-edit-label"><?php _e("Text"); ?></label>
            <livewire:microweber-option::textarea optionKey="text" :optionGroup="$moduleId" :module="$moduleType"  />
            <small>
                <?php _e("Every new line will be seperated as text animation."); ?>
            </small>
        </div>

        <div class="mt-3">
            <livewire:microweber-option::range-slider labelUnit="px" min="9" max="140" label="Text - Size" optionKey="fontSize" :optionGroup="$moduleId" :module="$moduleType"  />
        </div>

        <div class="mt-3">
            <label class="live-edit-label">{{__('Animation speed')}} </label>
            @php
                $speedOptions = [
                    'normal' => 'Normal',
                    'slow' => 'Slow',
                    'medium' => 'Medium',
                    'high' => 'High',
                    'fast' => 'Fast',
                    'ultra_fast' => 'Ultra Fast',
                ];
            @endphp
            <livewire:microweber-option::dropdown :dropdownOptions="$speedOptions" optionKey="animationSpeed" :optionGroup="$moduleId" :module="$moduleType"  />
        </div>

    </div>

</div>
