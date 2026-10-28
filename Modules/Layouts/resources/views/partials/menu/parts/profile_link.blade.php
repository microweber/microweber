<?php if ($profile_link): ?>
    <li class="dropdown btn-member ps-md-3">
        <a class="nav-link dropdown px-0" data-bs-toggle="dropdown" role="button" tabindex="0" aria-haspopup="true" aria-expanded="false" aria-label="{{ _e('Account', true) }}">
            <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M234-276q51-39 114-61.5T480-360q69 0 132 22.5T726-276q35-41 54.5-93T800-480q0-133-93.5-226.5T480-800q-133 0-226.5 93.5T160-480q0 59 19.5 111t54.5 93Zm246-164q-59 0-99.5-40.5T340-580q0-59 40.5-99.5T480-720q59 0 99.5 40.5T620-580q0 59-40.5 99.5T480-440Zm0 360q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q53 0 100-15.5t86-44.5q-39-29-86-44.5T480-280q-53 0-100 15.5T294-220q39 29 86 44.5T480-160Zm0-360q26 0 43-17t17-43q0-26-17-43t-43-17q-26 0-43 17t-17 43q0 26 17 43t43 17Zm0-60Zm0 360Z"/></svg>
        </a>
        <ul class="mw-big-dropdown dropdown-menu">
            <?php if (user_id()): ?>
                <li><a role="button" tabindex="0" href="<?php print profile_url(); ?>"><?php _lang("Profile", "templates/wine"); ?></a></li>
                <li><a role="button" tabindex="0" data-bs-toggle="modal" data-bs-target="#ordersModal"><?php _lang("My Orders"); ?></a></li>
            <?php else: ?>
                <li><a role="button" tabindex="0" class="js-login-modal" data-bs-toggle="modal" data-bs-target="#loginModal"><?php _lang("Login", "templates/wine"); ?></a></li>
                <li><a role="button" tabindex="0" class="js-register-modal" data-bs-toggle="modal" data-bs-target="#loginModal"><?php _lang("Register", "templates/wine"); ?></a></li>
            <?php endif; ?>

            <?php if (is_admin()): ?>
                <li><a href="<?php print admin_url() ?>"><?php _lang("Admin panel", "templates/wine"); ?></a></li>
            <?php endif; ?>

            <?php if (user_id()): ?>
                <li><a href="<?php print logout_url() ?>"><?php _lang("Logout", "templates/wine"); ?></a></li>
            <?php endif; ?>
        </ul>
    </li>

    <?php if (!user_id()): ?>
        {{-- Login / register modal + component. Lives next to its trigger so it
             is present on every template that shows the account dropdown. The
             legacy footer_cart `#loginModal` (with the dead `users/login`
             module) is rendered nowhere, so there is no duplicate id. --}}
        @once
            <div class="modal mw-auth-modal" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2 class="modal-title mw-auth-modal__title" id="loginModalLabel">{{ _e('Welcome', true) }}</h2>
                            <button type="button" class="btn-close mw-auth-modal__close" data-bs-dismiss="modal" aria-label="{{ _e('Close', true) }}">&times;</button>
                        </div>
                        <div class="modal-body">
                            @livewire('modules.profile.livewire.auth-modal')
                        </div>
                    </div>
                </div>
            </div>
            <script>
                // The "Register" menu item opens the same modal; flip the
                // component to its register tab once the modal is shown.
                document.addEventListener('click', function (e) {
                    if (!e.target.closest('.js-register-modal')) return;
                    setTimeout(function () {
                        var modal = document.getElementById('loginModal');
                        var tab = modal && modal.querySelector('[data-mw-auth-tab="register"]');
                        if (tab && tab.getAttribute('aria-selected') !== 'true') tab.click();
                    }, 350);
                });
            </script>
        @endonce
    <?php endif; ?>
<?php endif; ?>
