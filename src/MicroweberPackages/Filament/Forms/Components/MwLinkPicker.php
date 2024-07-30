<?php

namespace MicroweberPackages\Filament\Forms\Components;

use Filament\Forms\Components\Field;
use Filament\Forms\Components\TextInput;
use Closure;

class MwLinkPicker extends TextInput
{

    protected string $view = 'filament-forms::components.mw-link-picker';


    protected string | Closure | null $selectedData = null;

    public function selectedData(string | Closure | null $selectedData): static
    {
        $this->selectedData = $selectedData;

        return $this;
    }


    public function getContentId()
    {
        $selectedData = $this->getSelectedData();
        if (isset($selectedData['data']['id'])) {
            if ($selectedData['data']['type'] == 'content') {
                return $selectedData['data']['id'];
            }
        }
        return '';
    }

    public function getCategoryId()
    {
        $selectedData = $this->getSelectedData();
        if (isset($selectedData['data']['id'])) {
            if ($selectedData['data']['type'] == 'category') {
                return $selectedData['data']['id'];
            }
        }
        return '';
    }

    public function getUrl()
    {
        $url = '';
        $selectedData = $this->getSelectedData();
        if (isset($selectedData['data']['id']) && $selectedData['data']['id'] > 0) {
            if ($selectedData['data']['type'] == 'content') {
                $url = content_link($selectedData['data']['id']);
            } else if ($selectedData['data']['type'] == 'category') {
                $url = category_link($selectedData['data']['id']);
            }
        } elseif (isset($selectedData['url'])) {
            $url = $selectedData['url'];
        }
        return $url;
    }

    public function getSelectedData(): ? array
    {
        return $this->evaluate($this->selectedData);
    }
}
