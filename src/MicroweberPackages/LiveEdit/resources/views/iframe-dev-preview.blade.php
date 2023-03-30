<!DOCTYPE html>
<html prefix="og: http://ogp.me/ns#" <?php print lang_attributes(); ?>>
<head>
    <title>Live Edit Development Preview</title>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
</head>
<body>

@vite(['src/MicroweberPackages/LiveEdit/resources/js/ui/live-edit-app.js'])


<div id="live-edit-app">
    Loading...
</div>

</body>
</html>
