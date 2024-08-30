<?php if (isset($data['is_deleted']) and $data['is_deleted']) : ?>


    <div class="alert alert-danger" role="alert">

        <i class="mdi mdi-trash-can"></i>

        <?php _e('This content is deleted and it is in trash bin') ?>

        <a class="btn btn-outline-danger btn-sm" id="mw-admin-content-edit-inner-delete-curent-content-btn"
           href="javascript:mw.del_current_page_forever('<?php print ($data['id']) ?>');"><?php _e("Delete Forever"); ?></a>

       <a class="btn btn-warning btn-sm" id="mw-admin-content-edit-inner-delete-curent-content-btn"
           href="javascript:mw.del_current_page_restore('<?php print ($data['id']) ?>');"><?php _e("Restore"); ?></a>

    </div>


<?php else: ?>
    <?php if (isset($data['id']) and $data['id']) : ?>

        <div class="alert alert-danger" role="alert">


            <a class="btn btn-outline-danger btn-sm" id="mw-admin-content-edit-inner-delete-curent-content-btn"
               href="javascript:mw.del_current_page('<?php print ($data['id']) ?>');"><?php _e("Delete Content"); ?></a>

        </div>


    <?php endif; ?>
<?php endif; ?>



<script>
    mw.del_current_page = function (a, callback) {
        mw.admin.content.delete(a, function () {
            window.location.href = window.location.href;
        });
    }

    mw.del_current_page_forever = function (a, callback) {
        mw.admin.content.deleteForever(a, function () {
            window.location.href = window.location.href;
        });
    }


    mw.del_current_page_restore = function (a, callback) {
        mw.admin.content.restoreFromTrash(a, function () {
            window.location.href = window.location.href;
        });
    }
</script>
