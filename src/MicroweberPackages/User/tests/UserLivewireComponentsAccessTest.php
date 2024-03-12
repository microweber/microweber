<?php

namespace MicroweberPackages\User\tests;

use Livewire\Livewire;
use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\User\Http\Livewire\Admin\CreateProfileInformationForm;
use MicroweberPackages\User\Http\Livewire\Admin\DeleteUserForm;
use MicroweberPackages\User\Http\Livewire\Admin\LogoutOtherBrowserSessionsForm;
use MicroweberPackages\User\Http\Livewire\Admin\TwoFactorAuthenticationForm;
use MicroweberPackages\User\Http\Livewire\Admin\UpdatePasswordForm;
use MicroweberPackages\User\Http\Livewire\Admin\UpdatePasswordWithoutConfirmFormModal;
use MicroweberPackages\User\Http\Livewire\Admin\UpdateProfileInformationForm;
use MicroweberPackages\User\Http\Livewire\Admin\UpdateStatusAndRoleForm;
use MicroweberPackages\User\Http\Livewire\Admin\UserLoginAttemptsModal;
use MicroweberPackages\User\Http\Livewire\Admin\UsersList;
use MicroweberPackages\User\Http\Livewire\Admin\UserTosLogModal;


class UserLivewireComponentsAccessTest extends TestCase
{
    use UserTestHelperTrait;

    public $componentsList = [
        UsersList::class,
        UpdatePasswordForm::class,
        UpdatePasswordWithoutConfirmFormModal::class,
        TwoFactorAuthenticationForm::class,
        UpdateProfileInformationForm::class,
        UpdateStatusAndRoleForm::class,
        UserLoginAttemptsModal::class,
        UserTosLogModal::class,
        LogoutOtherBrowserSessionsForm::class,
        DeleteUserForm::class,
        CreateProfileInformationForm::class,
    ];

    public function testIfCanViewComponentAsAdmin()
    {
        $option = array();
        $option['option_value'] = 'n';
        $option['option_key'] = 'is_active';
        $option['option_group'] = 'multilanguage_settings';
        save_option($option);

        load_all_service_providers_for_modules();
        load_all_functions_files_for_modules();
        load_service_providers_for_template();
        load_functions_files_for_template();


        $this->actingAsAdmin();

        foreach ($this->componentsList as $component) {
            Livewire::test($component)->assertOk();
        }

    }

    public function testIfCannotViewComponentAsUser()
    {
        $option = array();
        $option['option_value'] = 'n';
        $option['option_key'] = 'is_active';
        $option['option_group'] = 'multilanguage_settings';
        save_option($option);

        load_all_service_providers_for_modules();
        load_all_functions_files_for_modules();
        load_service_providers_for_template();
        load_functions_files_for_template();


        $this->actingAsUser();
        foreach ($this->componentsList as $component) {
             Livewire::test($component)->assertUnauthorized();
        }
     }
}
