<?php
namespace MicroweberPackages\Modules\Posts\Http\Livewire;

use MicroweberPackages\LiveEdit\Http\Livewire\ModuleSettingsComponent;

class PostsSettingsComponent extends ModuleSettingsComponent
{

    public $postListFilters = [];

    protected $listeners = [
        'mwOptionSave' => 'mwOptionSaveListener',
    ];

    public function mwOptionSaveListener($e)
    {
        if (isset($e['optionKey'])) {

            if (!is_array($e['optionValue'])) {
                $e['optionValue'] = trim($e['optionValue']);
            }

            if ($e['optionKey'] == 'data-page-id') {
                $this->emit('autoCompleteSelectItem', 'page', $e['optionValue']);
            }
            if ($e['optionKey'] == 'data-tags') {
                $this->emit('autoCompleteSelectItem', 'tags', $e['optionValue']);
            }
        }
    }

    public function render()
    {
        $pageId = get_option('data-page-id', $this->moduleId);
        $pageId = trim($pageId);

        $tags = get_option('data-tags', $this->moduleId);
        $tags = trim($tags);

        $this->postListFilters = [];
        if (!empty($pageId)) {
            $this->postListFilters['page'] = $pageId;
        }
        if (!empty($tags)) {
            $this->postListFilters['tags'] = $tags;
        }

       return view('microweber-module-posts::livewire.settings');
    }
}
