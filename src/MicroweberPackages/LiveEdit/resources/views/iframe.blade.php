<!DOCTYPE html>
<html prefix="og: http://ogp.me/ns#" <?php print lang_attributes(); ?>>
<head>
    <title>Live Edit Development Preview</title>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <script src="https://scaleflex.cloudimg.io/v7/plugins/filerobot-image-editor/latest/filerobot-image-editor.min.js"></script>

    @livewireScripts
    @livewireStyles
    <?php print \MicroweberPackages\Admin\Facades\AdminManager::headTags();    ?>
    <link rel="stylesheet" href="<?php print site_url() ?>userfiles/modules/microweber/css/ui.css">

    <script>
        mw.lib.require('microweber_ui');

        mw.lib.require('jqueryui');
    </script>

    @vite('src/MicroweberPackages/LiveEdit/resources/js/ui/live-edit-app.js')
</head>
<body>

<script>
    mw.quickSettings = {}
</script>

<div id="live-edit-app">
    Loading...
</div>

<div id="live-edit-frame-holder">

</div>

<?php print \MicroweberPackages\LiveEdit\Facades\LiveEditManager::headTags(); ?>

</body>
</html>
