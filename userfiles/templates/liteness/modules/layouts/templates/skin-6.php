<?php

/*

type: layout

name: Contacts

position: 5

*/

?>

<?php
if (!$classes['padding_top']) {
    $classes['padding_top'] = '';
}
if (!$classes['padding_bottom']) {
    $classes['padding_bottom'] = '';
}

$layout_classes = ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
?>

<div class="nodrop safe-mode edit <?php print $layout_classes; ?>" field="layout-skin-6-<?php print $params['id'] ?>" rel="module">
    <div class="container">
        <div class="box-container">
            <div class="mw-row contacts-block">
                <div class="mw-col" style="width: 50%;">
                    <div class="mw-col-container">
                        <module type="contact_form" />
                    </div>
                </div>

                <div class="mw-col" style="width: 50%;">
                    <div class="mw-col-container">
                        <div class="well contacts-info">
                            <h3 class="border-title"><?php print _lang('Address', 'templates/liteness'); ?></h3>
                            <div class="contacts-icons allow-drop">
                                <p>
                                    <span class="symbol">&#xe041;</span><?php print _lang('Sofia 1700, Bulgaria, My place #10 str. , bl. B, fl. 3', 'templates/liteness'); ?>
                                </p>
                                <p>
                                    <span class="glyphicon glyphicon-phone"></span>+1 234-567-890
                                </p>
                            </div>

                            <h3 class="border-title"><?php print _lang('Follow Us', 'templates/liteness'); ?></h3>
                            <div class="contacts-icons allow-drop">
                                <p>
                                    <span class="symbol">&#xe027;</span>
                                    <a href="https://facebook.com/Microweber">https://facebook.com/Microweber</a>
                                </p>
                                <p>
                                    <span class="symbol">&#xe086;</span>
                                    <a href="https://twitter.com/Microweber">https://twitter.com/Microweber</a>
                                </p>
                                <p>
                                    <span class="symbol">&#xe039;</span>
                                    <a href="https://plus.google.com/+Microweber/">https://plus.google.com/+Microweber/</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>