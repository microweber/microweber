<?php

namespace MicroweberPackages\Modules\Teamcard\Http\Livewire;

use MicroweberPackages\LiveEdit\Http\Livewire\ModuleSettingsComponent;

class TeamcardEditItemComponent extends ModuleSettingsComponent
{

    public string $name = '';
    public string $role = '';
    public string $bio = '';
    public string $file = '';
    public string $itemId = '';


    public function mount()
    {
        if ($this->itemId) {
            $settings = get_module_option('settings', $this->moduleId);
            $json = @json_decode($settings, true);
            if ($json) {
                foreach ($json as $item) {
                    if (isset($item['itemId']) and $item['itemId'] == $this->itemId) {
                        if (isset($item['name'])) {
                            $this->name = $item['name'];
                        }
                        if (isset($item['role'])) {
                            $this->role = $item['role'];
                        }
                        if (isset($item['bio'])) {
                            $this->bio = $item['bio'];
                        }
                        if (isset($item['file'])) {
                            $this->file = $item['file'];
                        }
                    }
                }
            }
        }
    }


    public function render()
    {
        return view('microweber-module-teamcard::livewire.edit-teamcard-item');
    }

    public function submit()
    {
        $settings = get_module_option('settings', $this->moduleId);
        $defaults = array(
            'name' => '',
            'role' => '',
            'bio' => '',
            'file' => '',
            'itemId' => $this->moduleId . '_' . uniqid(),
        );

        $json = @json_decode($settings, true);

        if (isset($json) == false or count($json) == 0) {
            $json = array(0 => $defaults);
        }
        $isNewItem = false;

        $newItem = [];
        $newItem['name'] = $this->name;
        $newItem['role'] = $this->role;
        $newItem['bio'] = $this->bio;
        $newItem['file'] = $this->file;
        if ($this->itemId) {
            $newItem['itemId'] = $this->itemId;
        } else {
            $isNewItem = true;
            $newItem['itemId'] = $this->moduleId . '_' . uniqid();
        }

        $allItems = [];
        $allItems[] = $newItem;
        $sortIds = [];
        if (!empty($json)) {
            foreach ($json as $item) {
                if (isset($item['itemId']) and $newItem['itemId']) {
                    $sortIds[] = $item['itemId'];
                }
                if (isset($item['itemId']) and $newItem['itemId'] != $item['itemId']) {
                    $allItems[] = $item;
                }
            }
        }

        //sots $allItems by $sortIds
        if (!$isNewItem && $sortIds && $allItems) {
            array_multisort(array_column($allItems, 'itemId'), SORT_ASC, $allItems);
        }


        save_option(array(
            'option_group' => $this->moduleId,
            'module' => $this->moduleType,
            'option_key' => 'settings',
            'option_value' => json_encode($allItems)
        ));

        $this->emitTo('microweber-module-teamcard::list-items', 'onItemChanged', ['moduleId' => $this->moduleId]);

        $this->emit('switchToMainTab');


    }

    public function updatedSettings($settings)
    {
        $this->emit('settingsChanged', ['moduleId' => $this->moduleId, 'settings' => $this->settings]);
    }

}
