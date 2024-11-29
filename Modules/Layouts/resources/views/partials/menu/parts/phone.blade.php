<?php if ($phone_text == 1): ?>
    <li class="nav-item dropdown btn-phone ps-md-3 my-xl-0 my-1">
        <a href="tel:<?php print $phone_text_value; ?>" class="nav-link text-decoration-none ps-0 pe-2" style="font-size: 16px;">
            <span class="mdi mdi-phone"></span>
            <?php print $phone_text_value; ?>
        </a>

    </li>
<?php endif; ?>
