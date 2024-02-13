<?php
must_have_access();
?>

<div>
    <script>
        $(document).ready(function () {
            if(mw.top() && mw.top().dialog && mw.top().dialog.get('.mw_modal_live_edit_settings')) {
                mw.top().dialog.get('.mw_modal_live_edit_settings').resize(900);
            }
        });
    </script>

    <script>
        function reloadParentModule() {
            if (typeof mw !== 'undefined' && mw.top().app && mw.top().app.editor) {

                <?php if (isset($params['id'])): ?>
                mw.top().app.editor.dispatch('onModuleSettingsChanged', ({'moduleId': '<?php print $params['id']  ?>'} || {}));
                <?php endif; ?>

                <?php if (isset($params['for-id'])): ?>
                mw.top().app.editor.dispatch('onModuleSettingsChanged', ({'moduleId': '<?php print $params['for-id']  ?>'} || {}));
                <?php endif; ?>

                if (self !== top) {
                    //mw.reload_module_everywhere('shop/cart_add');
                    // mw.reload_module_everywhere('shop/products');
                    // mw.reload_module_everywhere('posts');
                    // mw.reload_module_everywhere('blog');
                }

            }
        }
        if (mw && mw.top && typeof mw.top === 'function' && mw.top().app) {
            mw.top().app.on('customFieldUpdatedGlobal', function () {
                alert('polucheno');
                 reloadParentModule();
            });
        }
    </script>

    <?php
    echo view('microweber-module-custom-fields::admin-module',
        ['params'=>$params]
    );
    ?>
</div>
