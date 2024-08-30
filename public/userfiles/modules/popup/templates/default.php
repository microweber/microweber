<?php

/*

type: layout

name: Default

description: Default

TODO: add option to show accept button instead of close

*/
?>


<div class="bootstrap3ns">
    <?php if ($type == 'on_click'):
        $onclick = (($source == 'existing_page' && !empty($page_id))? 'onclick="return popup_get_content();"' : '');
    ?>
        <a id="#popup-link" <?php print $onclick;?> data-bs-toggle="modal" href="#popup-<?php print $params['id']; ?>" data-backdrop="false"><?php print $link_text;?></a>
    <?php endif; ?>

    <template id="popup-<?php print $params['id']; ?>-template">
        <div class="modal fade" tabindex="-1" role="dialog" id="popup-<?php print $params['id']; ?>" style="display:none">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <?php if($source == 'existing_page'):?>
					<h5 id="modal-title"></h5>
					<?php else: ?>
                    <h5 class="modal-title edit" rel="module" field="module-popup-title-<?php print $params['id']; ?>">Modal title</h5>
                    <?php endif;?>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php _e('Close'); ?>"></button>
                </div>

                <div class="modal-body">
                    <div style="max-height: 50vh; overflow-y: scroll; padding-right:15px">
                        <?php if($source == 'existing_page'): ?>
                              <div id="modal-content"><?php _e("loading ..."); ?></div>
                        <?php else: ?>
                        <div class="edit allow-drop" rel="module"
                             field="module-popup-content-<?php print $params['id']; ?>">
                            <p><?php _e("One fine body"); ?>&hellip;</p>
                        </div>
                        <?php endif;?>
                    </div>
                </div>
                <div class="modal-footer">

                    <?php if (in_live_edit() && $source != 'existing_page'): ?>
                        <button type="button" disabled class="btn btn-success"><?php _e("Accept"); ?></button>
                    <?php endif; ?>

                    <?php if (!in_live_edit() AND $type == 'on_time'): ?>
                        <button type="button" id="popup-<?php print $params['id']; ?>-accept" class="btn btn-success"><?php _e("Accept"); ?></button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    </template>



</div>

<script>

;(function(){
    function rend() {
        var current = document.getElementById('popup-<?php print $params['id']; ?>');
        if(current) {
            current.remove()
        }
        document.body.appendChild(document.createRange().createContextualFragment(document.getElementById('popup-<?php print $params['id']; ?>-template').innerHTML));
    }

    function init() {
        setTimeout(rend, 100);
    }

    init()


})();

</script>

