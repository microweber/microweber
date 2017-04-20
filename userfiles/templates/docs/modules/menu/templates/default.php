<?php

/*

type: layout

name: Default

description: Default Menu skin

*/

?>

<script>mw.moduleCSS("<?php print $config['url_to_module']; ?>style.css", true);</script>


<div class="module-navigation module-navigation-default">
    <?php
    $mt = menu_tree($menu_filter);
    if ($mt != false) {
        print ($mt);
    } else {
        print lnotif(_e('There are no items in the menu', true) . " <b>" . $params['menu-name'] . '</b>');
    }
    ?>
</div>

<script>
    $(document).ready(function () {
        $(".module-navigation ul li a").first().html('<span class="mw-icon-mw" style="font-size: 34px;"></span>');

        $(".module-navigation").append('<div class="search-button"><div id="search_content_top"></div></div>')
        $("#search_content_top").attr('type', 'search');
        $("#search_content_top").attr('class', 'module');
        $("#search_content_top").attr('template', 'autocomplete');
        $("#search_content_top").attr('hide_paging', 'true');
        mw.reload_module('#search_content_top');
    });
</script>