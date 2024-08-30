<?php

exit('This file is not used anymore. See userfiles/modules/microweber/toolbar/editor_tools/module_dialog/index.php');
?>

<!DOCTYPE HTML>
<html <?php print lang_attributes(); ?>>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php $module_info = false;
    if (isset($params['module'])): ?>
        <?php $module_info = mw()->module_manager->get('one=1&ui=any&module=' . $params['module']); ?>
    <?php endif; ?>


    <?php //template_stack_display('default'); ?>


    <?php if (isset($params['live_edit_sidebar'])): ?>

        <script type="text/javascript">
            window.live_edit_sidebar = true;
        </script>
    <?php endif; ?>


    <script type="text/javascript">
        liveEditSettings = true;

        <?php if(_lang_is_rtl()){ ?>
        mw.require('<?php print mw_includes_url(); ?>css/rtl.css');
        <?php } ?>

        mw.lib.require('font_awesome5');
    </script>


    <style>
        #settings-main {
            min-height: 200px;
            overflow-x: hidden;
            /*padding: 10px 25px;*/
        }

        #settings-container {
            overflow: hidden;
            position: relative;
            min-height: 300px;
            padding: 25px;
        }

        #settings-container:after {
            content: ".";
            display: block;
            clear: both;
            visibility: hidden;
            line-height: 0;
            height: 0;
        }

    </style>




</head>
<body>
<div id="settings-main">
    <div id="settings-container">
        <?php
            if(isset($_GET['module'])) {
                $module =  $_GET['module'];
            } else if (isset($_GET['type'])) {
                $module =  $_GET['type'];
            }

            if(isset($_GET['template'])) {
                $template =  $_GET['template'];
            } else {
                $template =  'default';
            }
            ?>
        <module type="<?php print $module; ?>" template="<?php print $template; ?>"/>
    </div>
</div>



</body>
</html>
