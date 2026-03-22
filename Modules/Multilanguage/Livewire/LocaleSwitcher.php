<?php

namespace Modules\Multilanguage\Livewire;

use Illuminate\Support\Facades\Cookie;
use Livewire\Component;

class LocaleSwitcher extends Component
{
    public $currentLocale;
    public $supportedLanguages = [];
    public $isOpen = false;

    public function mount()
    {
        $this->currentLocale = app()->getLocale();
        $this->loadSupportedLanguages();
    }

    protected function loadSupportedLanguages()
    {
        if (!function_exists('get_supported_languages')) {
            $this->supportedLanguages = [];
            return;
        }

        $languages = get_supported_languages(true);
        $currentLanguageData = null;
        $otherLanguages = [];

        foreach ($languages as $lang) {
            $langData = [
                'locale' => $lang['locale'],
                'display_name' => $lang['display_name'] ?? $lang['language'] ?? $lang['locale'],
                'icon' => $lang['icon'] ?? null,
                'iconUrl' => $lang['iconUrl'] ?? null,
                'abr' => $lang['abr'] ?? substr($lang['locale'], 0, 2),
            ];

            if ($lang['locale'] === $this->currentLocale) {
                $currentLanguageData = $langData;
            } else {
                $otherLanguages[] = $langData;
            }
        }

        $this->supportedLanguages = [
            'current' => $currentLanguageData,
            'others' => $otherLanguages,
        ];
    }

    public function toggleDropdown()
    {
        $this->isOpen = !$this->isOpen;
    }

    public function closeDropdown()
    {
        $this->isOpen = false;
    }

    public function changeLocale($locale)
    {
        if (!function_exists('change_language_by_locale')) {
            return;
        }

        // Check if locale is supported
        $isSupported = false;
        if (!empty($this->supportedLanguages['others'])) {
            foreach ($this->supportedLanguages['others'] as $lang) {
                if ($lang['locale'] === $locale) {
                    $isSupported = true;
                    break;
                }
            }
        }

        if ($this->supportedLanguages['current']['locale'] === $locale) {
            $isSupported = true;
        }

        if (!$isSupported) {
            return;
        }

        // Change the locale
        change_language_by_locale($locale, true);

        // Update current locale
        $this->currentLocale = $locale;
        $this->loadSupportedLanguages();
        $this->isOpen = false;

        // Dispatch event for Alpine.js to handle redirect
        $this->dispatch('localeChanged', locale: $locale);
    }

    public function render()
    {
        return view('modules.multilanguage::livewire.locale-switcher');
    }
}
