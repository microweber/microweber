<?php if ($profile_link): ?>
    <script>
        var $window = $(window), $document = $(document);
        $document.ready(function () {
            $('.js-register-modal').on('click', function () {
                $(".js-login-window").hide();
                $(".js-forgot-window").hide();
                $(".js-register-window").show();
            });
            $('.js-login-modal').on('click', function () {
                $(".js-register-window").hide();
                $(".js-forgot-window").hide();
                $(".js-login-window").show();
            });
        });
    </script>
    {{-- AI-295: drop `href="#"` from Bootstrap dropdown / modal triggers.
         An <a> without href is not a link semantically — it's a focusable
         button (we already carry `role="button"` and `aria-*` attrs). The
         old `href="#"` triggered the audit's "empty anchor" finding and
         was announced as "link, #" by screen readers. Adding tabindex="0"
         keeps keyboard navigation working. Bootstrap 5 dropdown/modal
         data-* handlers don't require an `<a href>` to fire. --}}
    <li class="dropdown btn-member ps-md-3">
        <a class="nav-link dropdown px-0" data-bs-toggle="dropdown" role="button" tabindex="0" aria-haspopup="true" aria-expanded="false" aria-label="{{ _e('Account', true) }}">
            <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M234-276q51-39 114-61.5T480-360q69 0 132 22.5T726-276q35-41 54.5-93T800-480q0-133-93.5-226.5T480-800q-133 0-226.5 93.5T160-480q0 59 19.5 111t54.5 93Zm246-164q-59 0-99.5-40.5T340-580q0-59 40.5-99.5T480-720q59 0 99.5 40.5T620-580q0 59-40.5 99.5T480-440Zm0 360q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q53 0 100-15.5t86-44.5q-39-29-86-44.5T480-280q-53 0-100 15.5T294-220q39 29 86 44.5T480-160Zm0-360q26 0 43-17t17-43q0-26-17-43t-43-17q-26 0-43 17t-17 43q0 26 17 43t43 17Zm0-60Zm0 360Z"/></svg>
        </a>
        <ul class="mw-big-dropdown dropdown-menu">
            <?php if (user_id()): ?>
                <li><a role="button" tabindex="0" data-bs-toggle="modal" data-bs-target="#loginModal"><?php _lang("Profile", "templates/wine"); ?></a></li>
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
<?php endif; ?>
