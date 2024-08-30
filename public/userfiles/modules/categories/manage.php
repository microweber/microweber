<?php
$attributes = [];
if (isset($params['is_shop']) and $params['is_shop']) {
    $attributes['isShop'] = $params['is_shop'];
}
echo \Livewire\Livewire::mount('admin-category-manage', $attributes)->html();
?>
    
