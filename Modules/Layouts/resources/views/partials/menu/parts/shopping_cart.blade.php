<?php if ($shopping_cart): ?>
    <?php
        // TASK-022 / TICKET-CZ / AI-40 (cycle-59 2026-05-08): hide the
        // cart-count badge when the cart is empty so the public store
        // never renders a misleading "0". Mirror update in shop.js on
        // cartModify keeps it in sync after add-to-cart / remove. The
        // span MUST stay in the DOM (jQuery line 230 in
        // packages/frontend-assets/resources/assets/api-core/core/core/shop.js
        // selects `.js-shopping-cart-quantity` to update its content),
        // so we use the HTML5 `hidden` attribute and an aria pair to
        // remove it from the AT tree when the count is 0.
        $cart_qty = (int) cart_sum(false);
    ?>
    {{-- AI-295: see profile_link.blade.php — drop href="#" from
         the dropdown toggle; role + tabindex carry the semantics. --}}
    <li class="nav-item dropdown btn-shopping-cart ps-md-3">
        <a class="nav-link px-0 d-flex align-items-center" data-bs-toggle="dropdown" role="button" tabindex="0" aria-haspopup="true" aria-expanded="false" aria-label="{{ _e('Shopping cart', true) }}">
            <span class="btn btn-outline-primary btn-sm mx-2 js-shopping-cart-quantity"
                  data-cart-count="{{ $cart_qty }}"
                  @if ($cart_qty <= 0) hidden aria-hidden="true" @endif><?php print $cart_qty; ?></span>
            <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="28" viewBox="0 -960 960 960" width="28"><path d="M240-80q-33 0-56.5-23.5T160-160v-480q0-33 23.5-56.5T240-720h80q0-66 47-113t113-47q66 0 113 47t47 113h80q33 0 56.5 23.5T800-640v480q0 33-23.5 56.5T720-80H240Zm0-80h480v-480h-80v80q0 17-11.5 28.5T600-520q-17 0-28.5-11.5T560-560v-80H400v80q0 17-11.5 28.5T360-520q-17 0-28.5-11.5T320-560v-80h-80v480Zm160-560h160q0-33-23.5-56.5T480-800q-33 0-56.5 23.5T400-720ZM240-160v-480 480Z"/></svg>
        </a>
        <div class="mw-big-dropdown-cart dropdown-menu shopping-cart px-2">
            <module type="shop/cart" template="small_modal" class="no-settings"/>
        </div>
    </li>
<?php endif; ?>
