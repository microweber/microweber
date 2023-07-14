<!DOCTYPE html>
<html prefix="og: http://ogp.me/ns#" <?php print lang_attributes(); ?>>
<head>
    <title>Live Edit Development Preview</title>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    @livewireScripts
    @livewireStyles
    <?php print \MicroweberPackages\Admin\Facades\AdminManager::headTags();    ?>

    <script>


        mw.lib.require('jqueryui');
        mw.require('components.css')
        mw.require('liveedit_widgets.js')

        mw.require('icon_selector.js');
        mw.iconLoader()

            .addIconSet('iconsMindLine')
            .addIconSet('iconsMindSolid')
            .addIconSet('fontAwesome')
            .addIconSet('materialDesignIcons')

    </script>

    <?php

    $bodyDarkClass = '';

    if(isset($_COOKIE['admin_theme_dark'])){
        $bodyDarkClass = 'theme-dark';
    }
    ?>

    @vite('src/MicroweberPackages/LiveEdit/resources/js/ui/live-edit-app.js')
</head>
<body class="{{ $bodyDarkClass }} mw-admin-live-edit-page">

<script>
    mw.quickSettings = {};



    @php
        $templateColors = get_template_colors_settings();
    @endphp
    @if(!empty($templateColors))
    mw.tools.colorPickerColors = [
        @foreach($templateColors as $color)
        '{{ $color['value'] }}',
        @endforeach
    ];
    @endif
</script>

<div id="live-edit-app">
    Loading...
</div>

<div id="live-edit-frame-holder">

</div>

<?php print \MicroweberPackages\LiveEdit\Facades\LiveEditManager::headTags(); ?>

</body>
</html>
