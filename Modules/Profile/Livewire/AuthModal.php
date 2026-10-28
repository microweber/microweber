<?php

namespace Modules\Profile\Livewire;

use Livewire\Component;

/**
 * Frontend login / register component mounted inside the header account
 * dropdown's #loginModal (see Modules/Layouts .../menu/parts/profile_link).
 *
 * It replaces the legacy `<module type="users/login">` placeholder, which no
 * longer resolves to a registered module and rendered empty on every template.
 * Plain Livewire (no Filament forms) so it styles from the public site CSS, the
 * same approach the cart dropdown uses with Checkout\Livewire\CartItems.
 */
class AuthModal extends Component
{
    /** 'login' | 'register' | 'forgot' */
    public string $mode = 'login';

    public string $username = '';
    public string $email = '';
    public string $password = '';
    public string $passwordConfirm = '';

    public string $errorMessage = '';
    public string $successMessage = '';

    public function setMode(string $mode): void
    {
        $this->mode = in_array($mode, ['login', 'register', 'forgot'], true) ? $mode : 'login';
        $this->errorMessage = '';
        $this->successMessage = '';
    }

    public function login(): void
    {
        $this->resetMessages();

        $username = trim($this->username);
        if ($username === '' || $this->password === '') {
            $this->errorMessage = _e('Please enter your username and password.', true);
            return;
        }

        $response = app()->user_manager->login([
            'username' => $username,
            'password' => $this->password,
        ]);

        if (user_id()) {
            $this->js('window.location.reload()');
            return;
        }

        $this->errorMessage = $this->extractError($response) ?: _e('Invalid username or password.', true);
    }

    public function register(): void
    {
        $this->resetMessages();

        $email = trim($this->email);
        $username = trim($this->username) ?: $email;

        if ($email === '' || $this->password === '') {
            $this->errorMessage = _e('Please enter an email and a password.', true);
            return;
        }
        if ($this->passwordConfirm !== '' && $this->password !== $this->passwordConfirm) {
            $this->errorMessage = _e('The passwords do not match.', true);
            return;
        }

        $response = app()->user_manager->register([
            'email' => $email,
            'username' => $username,
            'password' => $this->password,
        ]);

        $error = $this->extractError($response);
        if ($error) {
            $this->errorMessage = $error;
            return;
        }

        // Registration went through: try to sign the new account straight in.
        app()->user_manager->login([
            'username' => $username !== '' ? $username : $email,
            'password' => $this->password,
        ]);

        if (user_id()) {
            $this->js('window.location.reload()');
            return;
        }

        // Account created but not auto-logged-in (e.g. email verification on).
        $this->successMessage = _e('Your account was created. Please log in.', true);
        $this->mode = 'login';
        $this->password = '';
        $this->passwordConfirm = '';
    }

    /**
     * Pull a human-readable message out of user_manager's mixed return shapes:
     * ['error' => '...'] or ['errors' => ['field' => ['msg', ...]]].
     */
    protected function extractError($response): string
    {
        if (!is_array($response)) {
            return '';
        }
        if (!empty($response['error']) && is_string($response['error'])) {
            return $response['error'];
        }
        if (!empty($response['errors']) && is_array($response['errors'])) {
            $first = reset($response['errors']);
            return is_array($first) ? (string) reset($first) : (string) $first;
        }
        return '';
    }

    protected function resetMessages(): void
    {
        $this->errorMessage = '';
        $this->successMessage = '';
    }

    public function render()
    {
        return view('modules.profile::livewire.auth-modal');
    }
}
