<?php

/*

type: layout

name: Default

description: Default

*/
?>
<script>
    mw.lib.require('bootstrap3ns');
</script>

<div class="bootstrap3ns">
    <?php if ($type == 'on_click'): ?>
        <a data-toggle="modal" href="#popup-<?php print $params['id']; ?>" data-backdrop="false">Click to open modal</a>
    <?php endif; ?>

    <div class="modal fade" tabindex="-1" role="dialog" id="popup-<?php print $params['id']; ?>">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title edit" rel="module" field="module-popup-title-<?php print $params['id']; ?>">
                        Modal title</h4>
                </div>
                <div class="modal-body">
                    <div style="max-height: 50vh; overflow-y: scroll;">
                        <div class="edit allow-drop" rel="module"
                             field="module-popup-content-<?php print $params['id']; ?>">
                            <p>One fine body&hellip;</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                    <?php if (in_live_edit()): ?>
                        <button type="button" class="btn btn-success" onclick="mw.drag.save();">Save</button>
                    <?php endif; ?>

                    <?php if (!in_live_edit() AND $type == 'on_time'): ?>
                        <button type="button" id="popup-<?php print $params['id']; ?>-accept" class="btn btn-success">Accept</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

</div>

