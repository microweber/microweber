<?php

/*

type: layout

name: Default

description: Default Menu Item skin

*/

?>

<style>
    .mega-menu-item {
        padding: 20px;
        background-color: #f8f9fa;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        display:none;
    }
</style>


<div class="edit mega-menu-item" rel="{{$menu_item_id}}" field="mega_menu_item">
    Here is your mega menu content..
</div>
