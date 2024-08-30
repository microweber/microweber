<?php

namespace MicroweberPackages\Modules\SocialLinks\Http\Livewire;

use MicroweberPackages\LiveEdit\Http\Livewire\ModuleSettingsComponent;

class SocialLinksSettingsComponent extends ModuleSettingsComponent
{

    public $supportedSocialNetworks = array(
        'instagram' => array(
            'icon' => 'mdi-instagram'
        ),
        'facebook' => array(
            'icon' => 'mdi-facebook'
        ),
        'twitter' => array(
            'icon' => 'mdi-twitter'
        ),
        'googleplus' => array(
            'icon' => 'mdi-google-plus'
        ),
        'pinterest' => array(
            'icon' => 'mdi-pinterest'
        ),
        'youtube' => array(
            'icon' => 'mdi-youtube'
        ),
        'linkedin' => array(
            'icon' => 'mdi-linkedin'
        ),
        'github' => array(
            'icon' => 'mdi-github'
        ),
        'soundcloud' => array(
            'icon' => 'mdi-soundcloud'
        ),
        'mixcloud' => array(
            'icon' => 'mdi-mixcloud'
        ),
        'medium' => array(
            'icon' => 'mdi-medium'
        ),
        'discord' => array(
            'icon' => 'mdi-discord'
        ),
        'skype' => array(
            'icon' => 'mdi-skype'
        )
    );

    public function render()
    {
        return view('microweber-module-social-links::livewire.settings');
    }
}
