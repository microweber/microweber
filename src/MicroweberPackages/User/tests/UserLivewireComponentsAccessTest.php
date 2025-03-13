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
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

#[RunTestsInSeparateProcesses]
class UserLivewireComponentsAccessTest extends TestCase
{
    use \MicroweberPackages\User\tests\UserTestHelperTrait;

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




        $this->actingAsAdmin();

        foreach ($this->componentsList as $component) {

            try {
                Livewire::test($component)->assertOk();
                $this->assertTrue(true, 'Component access success ' . $component);

            } catch (\Exception $e) {

                $this->assertTrue(false, 'Component access error ' . $component . ' ' . $e->getMessage());
            }
        }

    }

    public function testIfCannotViewComponentAsUser()
    {
        $option = array();
        $option['option_value'] = 'n';
        $option['option_key'] = 'is_active';
        $option['option_group'] = 'multilanguage_settings';
        save_option($option);




        $this->actingAsUser();
        foreach ($this->componentsList as $component) {


            try {
                Livewire::test($component)->assertUnauthorized();
                $this->assertTrue(true, 'Component access success ' . $component);

            } catch (\Exception $e) {
                // continue;
                $this->assertTrue(false, 'Component access error ' . $component . ' ' . $e->getMessage());
            }


        }
     }
}
