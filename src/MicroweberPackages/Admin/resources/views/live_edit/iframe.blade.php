<!DOCTYPE html>
<html prefix="og: http://ogp.me/ns#" <?php print lang_attributes(); ?>>
<head>
    <title></title>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>


    <script>
        mw.require("<?php print(mw_includes_url()); ?>css/ui.css");
        mw.lib.require("bootstrap5");
        mw.lib.require('bootstrap_select');
        mw.require('icon_selector.js');


        mw.iconLoader()
            .addIconSet('materialDesignIcons')
            .addIconSet('fontAwesome')
            .addIconSet('iconsMindLine')
            .addIconSet('iconsMindSolid')
            .addIconSet('mwIcons')
            .addIconSet('materialIcons');
    </script>

    <script>
        $(document).ready(function () {
            $('.selectpicker').selectpicker();
        });
    </script>


</head>
<body>
<div>
    <iframe id="inlineFrameExample"
            title="Inline Frame Example"
            width="100%"
            height="2000"
            referrerpolicy="no-referrer"
            src="<?php print site_url(); ?>">
    </iframe>
</div>
</body>
</html>
