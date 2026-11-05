<?php if (get_option('is_active', 'multilanguage_settings')): ?>
    {{-- No wrapping <a>: the switcher template emits a <button> and <a> menu
         items, which are invalid (and get reparented by the HTML parser,
         breaking the toggle↔menu relationship) inside an anchor. --}}
    <li class="nav-item dropdown ps-md-3  my-xl-0 my-1">
        <module type="multilanguage" />
    </li>
<?php endif; ?>
