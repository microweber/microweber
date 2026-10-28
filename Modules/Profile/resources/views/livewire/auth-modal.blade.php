<div class="mw-auth">
    {{-- Self-contained styling so the modal looks right on every template it
         mounts in (the header account dropdown is shared across all templates,
         not just Plain). The modal's fixed-overlay base (.modal/.modal-backdrop)
         comes from Bootstrap on BS templates and from plain.css on Plain. --}}
    @once
        <style>
            .mw-auth-modal .modal-dialog { max-width: 420px; margin: 7vh auto; padding: 0 16px; }
            .mw-auth-modal .modal-content { background: #fff; border-radius: 16px; box-shadow: 0 24px 60px rgba(30, 27, 46, .28); overflow: hidden; border: 0; }
            .mw-auth-modal .modal-header { display: flex; align-items: center; justify-content: space-between; padding: 18px 20px; border-bottom: 1px solid rgba(30, 27, 46, .08); }
            .mw-auth-modal__title { margin: 0; font-size: 1.15rem; font-weight: 700; color: #1e1b2e; }
            .mw-auth-modal__close { background: none; border: 0; font-size: 1.6rem; line-height: 1; cursor: pointer; color: #6b7280; min-width: 44px; min-height: 44px; opacity: 1; }
            .mw-auth-modal .modal-body { padding: 20px; }
            .mw-auth__tabs { display: flex; gap: 8px; margin-bottom: 16px; }
            .mw-auth__tab { flex: 1; padding: 10px; border: 0; background: rgba(30, 27, 46, .05); border-radius: 10px; font-weight: 700; color: #6b7280; cursor: pointer; min-height: 44px; }
            .mw-auth__tab.is-active { background: #1e1b2e; color: #fff; }
            .mw-auth__form { display: flex; flex-direction: column; }
            .mw-auth__label { font-size: .85rem; font-weight: 600; color: #374151; margin-top: 10px; margin-bottom: 4px; }
            .mw-auth__input { width: 100%; padding: 11px 12px; border: 1px solid rgba(30, 27, 46, .15); border-radius: 10px; font-size: .95rem; background: #fff; color: #1e1b2e; }
            .mw-auth__input:focus { outline: none; border-color: #1e1b2e; box-shadow: 0 0 0 3px rgba(30, 27, 46, .12); }
            .mw-auth__submit { margin-top: 18px; padding: 12px; border: 0; border-radius: 10px; background: #1e1b2e; color: #fff; font-weight: 700; cursor: pointer; min-height: 44px; }
            .mw-auth__submit:hover { opacity: .92; }
            .mw-auth__alert { padding: 10px 12px; border-radius: 10px; font-size: .9rem; margin-bottom: 12px; }
            .mw-auth__alert--error { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
            .mw-auth__alert--success { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
        </style>
    @endonce

    <div class="mw-auth__tabs" role="tablist">
        <button type="button" data-mw-auth-tab="login"
                class="mw-auth__tab @if ($mode === 'login') is-active @endif"
                wire:click="setMode('login')"
                aria-selected="{{ $mode === 'login' ? 'true' : 'false' }}">{{ _e('Login', true) }}</button>
        <button type="button" data-mw-auth-tab="register"
                class="mw-auth__tab @if ($mode === 'register') is-active @endif"
                wire:click="setMode('register')"
                aria-selected="{{ $mode === 'register' ? 'true' : 'false' }}">{{ _e('Register', true) }}</button>
    </div>

    @if ($errorMessage)
        <div class="mw-auth__alert mw-auth__alert--error" role="alert">{{ $errorMessage }}</div>
    @endif
    @if ($successMessage)
        <div class="mw-auth__alert mw-auth__alert--success" role="status">{{ $successMessage }}</div>
    @endif

    @if ($mode === 'register')
        <form class="mw-auth__form" wire:submit.prevent="register">
            <label class="mw-auth__label" for="mw-auth-reg-email">{{ _e('Email', true) }}</label>
            <input id="mw-auth-reg-email" type="email" class="mw-auth__input" wire:model="email"
                   autocomplete="email" required>

            <label class="mw-auth__label" for="mw-auth-reg-pass">{{ _e('Password', true) }}</label>
            <input id="mw-auth-reg-pass" type="password" class="mw-auth__input" wire:model="password"
                   autocomplete="new-password" required>

            <label class="mw-auth__label" for="mw-auth-reg-pass2">{{ _e('Confirm password', true) }}</label>
            <input id="mw-auth-reg-pass2" type="password" class="mw-auth__input" wire:model="passwordConfirm"
                   autocomplete="new-password">

            <button type="submit" class="mw-auth__submit">
                <span wire:loading.remove wire:target="register">{{ _e('Create account', true) }}</span>
                <span wire:loading wire:target="register">{{ _e('Creating…', true) }}</span>
            </button>
        </form>
    @else
        <form class="mw-auth__form" wire:submit.prevent="login">
            <label class="mw-auth__label" for="mw-auth-user">{{ _e('Username or email', true) }}</label>
            <input id="mw-auth-user" type="text" class="mw-auth__input" wire:model="username"
                   autocomplete="username" required>

            <label class="mw-auth__label" for="mw-auth-pass">{{ _e('Password', true) }}</label>
            <input id="mw-auth-pass" type="password" class="mw-auth__input" wire:model="password"
                   autocomplete="current-password" required>

            <button type="submit" class="mw-auth__submit">
                <span wire:loading.remove wire:target="login">{{ _e('Login', true) }}</span>
                <span wire:loading wire:target="login">{{ _e('Logging in…', true) }}</span>
            </button>
        </form>
    @endif
</div>
