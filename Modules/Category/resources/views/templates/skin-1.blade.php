<?php

/*

type: layout

name: Skin - 1

description: List Navigation 1

*/

?>

<?php
    $params['ul_class'] = 'nav-list';
    $params['active_class'] = 'active';
	$params['ul_class_deep'] = 'nav-list';

?>


{{-- audit-test 2026-05-07 Categories audit findings #1 + #2 + #4:
     #1: wrap in <nav> + aria-labelledby for landmark navigation.
     #2: drop the duplicate `module-categories module-categories-template-default`
         classes that shipped via copy-paste (live-render confirmed
         class="module-categories module-categories-template-skin-1
         module-categories module-categories-template-default") — that
         caused skin-1 to inherit any .module-categories-template-default
         CSS rules unintentionally.
     #4: visually-hidden <h2> announce-only label. --}}
<nav class="module-categories module-categories-template-skin-1"
     aria-labelledby="cat-{{ $params['id'] ?? 'skin-1' }}-h">
    <h2 id="cat-{{ $params['id'] ?? 'skin-1' }}-h" class="visually-hidden">{{ __('Product categories') }}</h2>
    <?php  category_tree($params);  ?>
</nav>

