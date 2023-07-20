

<div class="mw-module-select-template" @if(count($moduleTemplates) == 1) style="display:none" @endif >

    @php



        $currentSkin = 'default';
        if(isset($settings['template'])){
            $currentSkin = $settings['template'];
        }

        $currentSkinName = str_replace('.php', '', $currentSkin);
        $componentNameForModuleSkin = 'microweber-module-'.$moduleType.'::template-settings-'.$currentSkinName;

        $hasSkinSettingsComponent= livewire_component_exists($componentNameForModuleSkin) === true;

         $moduleTypeForComponent = str_replace('/', '.', $moduleType);
         $moduleTypeForComponent = str_replace('_', '-', $moduleType);
    @endphp

   <div>
       <label class="live-edit-label"><?php _ejs("Skin"); ?></label>

       <div class="form-control-live-edit-label-wrapper">

           <select wire:model="settings.template" class="form-select form-control-live-edit-input">
               <option value="default">
                   <?php _e("Default"); ?>
               </option>

               <?php foreach ($moduleTemplates as $item): ?>
                   <?php if ((strtolower($item['name']) != 'default')): ?>
               <option value="<?php print $item['layout_file'] ?>"
                       title="Template: <?php print str_replace('.php', '', $item['layout_file']); ?>"> <?php print $item['name'] ?> </option>
               <?php endif; ?>
               <?php endforeach; ?>
           </select>
       </div>

   </div>

    <div class="mw-module-skin-setting-holder">


        @if($currentSkin and $hasSkinSettingsComponent)
            <div class="mw-module-skin-setting-inner">
                <div>
                    <div>

                        <?php
                        $moduleTypeForComponent = str_replace('/', '-', $moduleType);
                        $hasError = false;
                        $output = false;

                        try {
                            $output = \Livewire\Livewire::mount($componentNameForModuleSkin, [
                                'moduleId' => $moduleId,
                                'moduleType' => $moduleType,
                            ])->html();

                        } catch (\Livewire\Exceptions\ComponentNotFoundException $e) {
                            $hasError = true;
                            $output = $e->getMessage(). ' ' . $e->getFile() . ' ' . $e->getLine();
                        } catch (\Exception $e) {
                            $hasError = true;
                            $output = $e->getMessage(). ' ' . $e->getFile() . ' ' . $e->getLine();
                        }

                        if ($hasError) {
                            print '<div class="alert alert-danger" role="alert">';
                            print $output;
                            print '</div>';
                        } else {
                            print $output;
                        }


                        ?>


                     </div>
                    <script>
                    //    window.livewire.rescan();
                    </script>
                </div>
            </div>

        @endif
    </div>

</div>
