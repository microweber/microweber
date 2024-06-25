<div>

    <div class="card-body" style="padding:5px;padding-bottom:25px;">
        <div>
            <label class="live-edit-label"><?php _e("Text"); ?></label>
            <livewire:microweber-option::text optionKey="text" :optionGroup="$moduleId" :module="$moduleType"  />

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


        <div class="mt-3">
            <label class="live-edit-label">{{__('Text style')}} </label>
            @php
                $textStyleOptions = [
                    'normal' => 'Normal',
                    'italic' => 'Italic',
                    'underline' => 'Underline',
                    'line-through' => 'Line-through',
                ];
            @endphp
            <livewire:microweber-option::dropdown :dropdownOptions="$textStyleOptions" optionKey="textStyle" :optionGroup="$moduleId" :module="$moduleType"  />
        </div>

        <div class="mt-3">
            <label class="live-edit-label">{{__('Text weight')}} </label>
            @php
                $textWeightOptions = [
                    '300' => 'Thin',
                    '400' => 'Normal',
                    '500' => 'Semi-Bold',
                    '600' => 'Bold',
                    '700' => 'Extra-Bold',
                    '800' => 'Ultra-Bold',
                    '900' => 'Heavy',
                ];
            @endphp
            <livewire:microweber-option::dropdown :dropdownOptions="$textWeightOptions" optionKey="textWeight" :optionGroup="$moduleId" :module="$moduleType"  />
        </div>

        <div class="mt-3">
            <livewire:microweber-option::color-picker label="Text color" optionKey="textColor" :optionGroup="$moduleId" :module="$moduleType"  />
        </div>

        <div class="mt-3">
            <livewire:microweber-option::range-slider labelUnit="px" min="9" max="250" label="Text - Size" optionKey="fontSize" :optionGroup="$moduleId" :module="$moduleType"  />
        </div>
    </div>
</div>
