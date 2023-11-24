<div class="list-group list-group list-group-flush overflow-auto"
     style="max-height: 500px; margin-right: 20px;">

    @if(isset($setting['fieldSettings']['colorPaletteFromTemplateFilesLibrary']))

        @php
            // $setting['fieldType'] = 'colorPalette';
             if(!isset($setting['fieldSettings']['colors'])) {
                 $setting['fieldSettings']['colors'] = [];
             }
                 if (isset($setting['fieldSettings']['colorPaletteFromTemplateFilesLibrary']) and !empty($setting['fieldSettings']['colorPaletteFromTemplateFilesLibrary'])) {
                     $jsonFilesOnTemplateColorPalettes = $setting['fieldSettings']['colorPaletteFromTemplateFilesLibrary'];
                     foreach ($jsonFilesOnTemplateColorPalettes as $jsonFileColor) {
                         $templateColorsFileExits  = templates_dir() . template_name() . DS . $jsonFileColor;
                         $templateColorsFileExits = normalize_path($templateColorsFileExits, false);
                         if(is_file($templateColorsFileExits)){
                             $templateColorsFileExitsContent = @file_get_contents($templateColorsFileExits);
                             $templateColorsFileExitsContent = @json_decode($templateColorsFileExitsContent, true);

                             if(is_array($templateColorsFileExitsContent)){
                                 foreach ($templateColorsFileExitsContent as $templateColorsFileExitsContentItem) {
                                     if(isset($templateColorsFileExitsContentItem['name']) and isset($templateColorsFileExitsContentItem['mainColors'])){
                                         $setting['fieldSettings']['colors'][] = $templateColorsFileExitsContentItem;
                                     }
                                 }
                             }

                         }

                     }

                 }



        @endphp

    @endif


    @if(isset($setting['fieldSettings']['colors']))
        @foreach($setting['fieldSettings']['colors'] as $colorPallete)
            <div class="list-group-item"

                 x-on:click="(e) => {
                                    @foreach($colorPallete['properties'] as $property=>$propertyValue)
                                    mw.top().app.cssEditor.setPropertyForSelector('{{end($setting['selectors'])}}', '{{$property}}', '{{$propertyValue}}');
                                    @endforeach
                                }">
                <div class="row align-items-center">
                    <div class="col text-truncate">
                        <div class="text-reset d-block">{{$colorPallete['name']}}</div>
                        <div class=" d-flex flex-cols gap-1 mt-n1">
                            @foreach($colorPallete['mainColors'] as $mainColors)
                                <div style="border-radius:6px;width:100%;height:40px;background:{{$mainColors}}"></div>
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif

</div>

<?php

/* @if(isset($setting['fieldSettings']['colors']))
 * @foreach($setting['fieldSettings']['colors'] as $colorPallete)
 * <div class="mt-2">
 * <div class="d-flex flex-cols gap-2"
 *
 * x-on:click="(e) => {
 * @foreach($colorPallete['properties'] as $property=>$propertyValue)
 * mw.top().app.cssEditor.setPropertyForSelector('{{end($setting['selectors'])}}', '{{$property}}', '{{$propertyValue}}');
 * @endforeach
 * }"
 *
 * >
 * @if(isset($colorPallete['name']))
 *
 * <div style="border-radius:6px;width:100%;height:40px;background:{{$colorPallete['name']}}"></div>
 * @endif
 *
 * @foreach($colorPallete['mainColors'] as $mainColors)
 * <div style="border-radius:6px;width:100%;height:40px;background:{{$mainColors}}"></div>
 * @endforeach
 * </div>
 * </div>
 * @endforeach
 * @endif
 */

?>
