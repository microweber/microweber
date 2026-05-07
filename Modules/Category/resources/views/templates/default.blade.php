<?php

/*

type: layout

name: Default

description: List Navigation

*/

?>

<?php
    $params['ul_class'] = 'nav-list';
	$params['ul_class_deep'] = 'nav-list';


?>


{{-- audit-test 2026-05-07 Categories audit findings #1 + #4:
     The category list IS navigation — wrap in <nav> + aria-labelledby
     so screen-reader landmark navigation reaches it. visually-hidden
     <h2> is the announce-only label; pair the id via aria-labelledby
     so the heading travels with the landmark even when sighted users
     don't see it. --}}
<nav class="module-categories module-categories-template-default"
     aria-labelledby="cat-{{ $params['id'] ?? 'default' }}-h">
    <h2 id="cat-{{ $params['id'] ?? 'default' }}-h" class="visually-hidden">{{ __('Product categories') }}</h2>
    <?php   category_tree($params);  ?>
</nav>

