<?php

$styles_attr = '';
$height = '';

if(isset($params['height'])){
    $height = trim($params['height']);
}
if(!$height ){
    $height = '50px';
}
$optionHeight = get_option('height', $params['id']);
if($optionHeight){
    $height = $optionHeight;
}

$styles = [];
if($height){
    $styles[] = 'height: '.$height.'';
}
if(!empty($styles)) {
    $styles_attr = 'style="' . implode(';', $styles) . '"';
}



?>
<style>
    #<?php print $params['id'] ?>, .mw-spacer-disable-settings--<?php print $params['id'] ?>{
        pointer-events: none;
    }
    .mw-spacer-disable-settings--<?php print $params['id'] ?> > *{
        pointer-events: all;
    }
</style>

<div class="mw-le-spacer noelement nodrop inaccessibleModuleIfFirstParentIsLayout" data-for-module-id="<?php print $params['id'] ?>" contenteditable="false" <?php print $styles_attr ?> id="spacer-item-<?php print $params['id'] ?>"></div>
<?php


/*
 *
 * <script>
    ;(function(){

        function init() {
            // todo: move to service

            var spacer = document.getElementById('spacer-item-<?php print $params['id'] ?>');
            if(spacer) {

                if(mw && mw.top && mw.top().app && mw.top().app.liveEdit && mw.top().app.liveEdit.elementAnalyzer && !mw.top().app.liveEdit.elementAnalyzer.isInEdit(spacer.parentElement))  {
                    spacer.classList.add('mw-spacer-disable-settings--<?php print $params['id'] ?>')
                    spacer.parentElement.classList.add('no-settings', 'no-element')
               //     spacer.parentElement.classList.remove('module')
                }
            }
        }

        if(!mw.top().win.__spacerDisableSettingsEvent) {
            mw.top().win.__spacerDisableSettingsEvent = true;
            mw.top().win.addEventListener('load', init)
        }



        init()


    })();
</script>*/
?>
