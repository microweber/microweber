<?php
must_have_access();
?>

<div>
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
                    try {
                        mw.top().reload_module_everywhere('shop/cart_add');
                    } catch (e) {
                    }
                    try {
                        mw.top().reload_module_everywhere('shop/products');
                    } catch (e) {
                    }
                    try {
                        mw.top().reload_module_everywhere('posts');
                    } catch (e) {
                    }
                    try {
                        mw.top().reload_module_everywhere('blog');
                    } catch (e) {
                    }

                }

            }
        }

        addEventListener('DOMContentLoaded', function () {
            if (mw && mw.top && typeof mw.top === 'function' && mw.top().app) {
                mw.top().app.on('customFieldUpdatedGlobal', () => {
                    Livewire.emit('customFieldListRefresh');
                    reloadParentModule();
                });
            }
        });

    </script>

    <script>
        $(document).ready(function () {
            if(mw.top() && mw.top().dialog && mw.top().dialog.get('.mw_modal_live_edit_settings')) {
                mw.top().dialog.get('.mw_modal_live_edit_settings').resize(900);
            }
        });
    </script>

    <?php
    echo view('microweber-module-custom-fields::admin-module',
        ['params'=>$params]
    );
    ?>
</div>
