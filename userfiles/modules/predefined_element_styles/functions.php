<?php




event_bind('mw.front', function ($params) {

    template_foot('<link predefined-element-stylesheet-classes="true" type="text/css" rel="stylesheet" href="' . modules_url() . 'predefined_element_styles/scss/main-predefined-element-styles.css"/>');

});


