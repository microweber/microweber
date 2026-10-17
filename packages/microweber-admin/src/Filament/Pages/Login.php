<?php

namespace MicroweberPackages\Admin\Filament\Pages;

use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Schemas\Components\Component;

class Login extends BaseLogin
{
    protected function getEmailFormComponent(): Component
    {
        return parent::getEmailFormComponent()
            ->placeholder(__('you@example.com'))
            ->extraInputAttributes([
                'name' => 'email',
                'autocomplete' => 'username',
                'inputmode' => 'email',
            ]);
    }

    protected function getPasswordFormComponent(): Component
    {
        return parent::getPasswordFormComponent()
            ->placeholder(__('Your password'))
            ->extraInputAttributes([
                'name' => 'password',
                'autocomplete' => 'current-password',
            ]);
    }
}
