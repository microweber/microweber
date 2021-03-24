<?php

use MicroweberPackages\Assets\Facades\Assets;


?>

<script type="text/javascript" src="<?php print(mw()->template->get_apijs_combined_url()); ?>"></script>



<?php

Assets::add(['admin']);

print Assets::all();
?>

<script type="text/javascript">
    if (!window.CanvasRenderingContext2D) {
        var h = "<div id='UnsupportedBrowserMSG'></div>"
            + "<div id='download_browsers_holder'><h2><?php _e("Update your browser"); ?></h2><p id='choose_browsers'>"
            + "<a id='u__ie' target='_blank' href='http://windows.microsoft.com/en-us/internet-explorer/download-ie'></a>"
            + "<a id='u__ff' target='_blank' href='http://www.mozilla.org/en-US/firefox/new/'></a>"
            + "<a id='u__chr' target='_blank' href='https://www.google.com/intl/en/chrome/'></a>"
            + "<a id='u__sf' target='_blank' href='http://support.apple.com/kb/DL1531'></a>"
            + "</p></div>";
        document.write(h);
        document.body.id = 'UnsupportedBrowser';
        document.body.className = 'UnsupportedBrowser';
    }
    mwAdmin = true;
    admin_url = '<?php print admin_url(); ?>';
</script>

<script type="text/javascript">

    <?php if(_lang_is_rtl()){ ?>
    mw.require("<?php print mw_includes_url(); ?>css/rtl.css");
    <?php } ?>


</script>



