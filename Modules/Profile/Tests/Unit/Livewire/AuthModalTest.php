<?php

namespace Modules\Profile\Tests\Unit\Livewire;

use Livewire\Livewire;
use Modules\Profile\Livewire\AuthModal;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Pins the frontend login/register component that replaced the dead
 * `users/login` module in the header account dropdown.
 */
class AuthModalTest extends TestCase
{
    #[Test]
    public function it_is_registered_as_a_livewire_component(): void
    {
        $resolved = app('livewire')->new('modules.profile.livewire.auth-modal');
        $this->assertInstanceOf(AuthModal::class, $resolved);
    }

    #[Test]
    public function it_renders_both_login_and_register_tabs(): void
    {
        Livewire::test(AuthModal::class)
            ->assertSet('mode', 'login')
            ->assertSeeHtml('data-mw-auth-tab="login"')
            ->assertSeeHtml('data-mw-auth-tab="register"');
    }

    #[Test]
    public function it_switches_to_the_register_mode(): void
    {
        Livewire::test(AuthModal::class)
            ->call('setMode', 'register')
            ->assertSet('mode', 'register')
            ->assertSeeHtml('wire:submit.prevent="register"');
    }

    #[Test]
    public function it_rejects_an_empty_login_with_a_message(): void
    {
        Livewire::test(AuthModal::class)
            ->set('username', '')
            ->set('password', '')
            ->call('login')
            ->assertSet('errorMessage', function ($value) {
                return is_string($value) && $value !== '';
            });
    }

    #[Test]
    public function it_reports_invalid_credentials_and_stays_a_guest(): void
    {
        Livewire::test(AuthModal::class)
            ->set('username', 'no-such-user-' . uniqid())
            ->set('password', 'definitely-wrong-password')
            ->call('login')
            ->assertSet('errorMessage', function ($value) {
                return is_string($value) && $value !== '';
            });

        $this->assertFalse((bool) user_id());
    }
}
