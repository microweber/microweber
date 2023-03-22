<!DOCTYPE html>
<html prefix="og: http://ogp.me/ns#" <?php print lang_attributes(); ?>>
<head>
    <title>Live edit</title>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>

    @viteReactRefresh
    @vite('userfiles/modules/microweber/api/live-edit-app/app.tsx')
    <?php print \MicroweberPackages\LiveEdit\Facades\LiveEditManager::headTags(); ?>

</head>
<body>
<div id="live-edit-root"></div>
<script>
    mw.quickSettings = {}
</script>
</body>
