<?php
if(!defined('MW_VERSION')){
    return;
}

?>
<!DOCTYPE HTML>
<html>
    <head>
        <script src="<?php   print(site_url());  ?>apijs"></script>
        <script src="<?php   print(site_url());  ?>apijs_settings?id=<?php print CONTENT_ID; ?>"></script>
     </head>
    <body>
        {content}
    </body>
</html>
