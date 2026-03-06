<?php

namespace MicroweberPackages\Filament\Forms\Components;

use Filament\Forms\Components\Field;
use Filament\Forms\Components\TextInput;
use Closure;

class MwLinkPicker extends TextInput
{

    protected string $view = 'mw-filament::components.mw-link-picker';


    protected string|Closure|null $selectedData = null;

    protected bool $simpleMode = false;
    protected string|Closure|null $suffixIcon = 'heroicon-m-globe-alt';
    protected Closure|bool $isReadOnly = true;

    public function selectedData(string|Closure|null $selectedData): static
    {
        $this->selectedData = $selectedData;

        return $this;
    }

    public function getSimpleMode(): bool
    {
        return $this->simpleMode;
    }

    public function setSimpleMode(bool $isSimpleMode = true): static
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

        if ($this->getSimpleMode()) {

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

        if (!$url and isset($this->hasDefaultState)) {
            if ($this->defaultState) {

                $urlData = $this->getDefaultState();

                if (is_string($urlData)) {
                    $urlData = @json_decode($urlData, true);
                }


                if (!$url and isset($urlData['data']['type']) && $urlData['data']['type'] == 'category') {
                    if (isset($urlData['data']['id']) && $urlData['data']['id'] > 0) {
                        $url = category_link($urlData['data']['id']);
                    }
                } else if (!$url and isset($urlData['data']['type']) && $urlData['data']['type'] == 'content') {

                    if (isset($urlData['data']['id']) && $urlData['data']['id'] > 0) {
                        $url = content_link($urlData['data']['id']);
                    }
                } else if (!$url and isset($urlData['url'])) {
                    $url = $urlData['url'];
                } else if (!$url and isset($urlData['data']['url'])) {
                    $url = $urlData['data']['url'];
                }

            }
        }


        return $url;
    }

    public function getSelectedData(): ?array
    {
        return $this->evaluate($this->selectedData);
    }
}
