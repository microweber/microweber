<?php

namespace MicroweberPackages\Filament\Forms\Components;

use Filament\Forms\Components\Field;
use Filament\Forms\Components\TextInput;
use Closure;

class MwLinkPicker extends TextInput
{

    protected string $view = 'filament-forms::components.mw-link-picker';


    protected string|Closure|null $selectedData = null;

    protected bool $simpleMode = false;

    public function selectedData(string|Closure|null $selectedData): static
    {
        $this->selectedData = $selectedData;

        return $this;
    }

    public function getSimpleMode():bool
    {
        return $this->simpleMode;
    }

    public function setSimpleMode( bool $isSimpleMode = true): static
    {
        $this->simpleMode = $isSimpleMode;

        return $this;
    }

    public function getContentId()
    {
        $selectedData = $this->getSelectedData();
        if (isset($selectedData['data']['id'])) {
            return $selectedData['data']['id'];
        }
        return '';
    }

    public function getContentType()
    {
        $selectedData = $this->getSelectedData();
        if (isset($selectedData['data']['id'])) {
            return $selectedData['data']['type'];
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

       if($this->getSimpleMode()) {

       }

        $url = '';
        $selectedData = $this->getSelectedData();
        if (isset($selectedData['data']['id']) && $selectedData['data']['id'] > 0) {
            if ($selectedData['data']['type'] == 'category') {
                $url = category_link($selectedData['data']['id']);
            } else {
                $url = content_link($selectedData['data']['id']);
            }
        } elseif (isset($selectedData['url'])) {
            $url = $selectedData['url'];
        }
        return $url;
    }

    public function getSelectedData(): ?array
    {
        return $this->evaluate($this->selectedData);
    }
}
