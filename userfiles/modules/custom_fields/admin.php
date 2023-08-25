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
            }
        }
        Livewire.on('customFieldDeleted', (e) => {
            reloadParentModule();
        });
        Livewire.on('customFieldUpdated', (e) => {
            reloadParentModule();
        });
        Livewire.on('customFieldAdded', (e) => {
            reloadParentModule();
        });
    </script>

    <?php
    echo view('microweber-module-custom-fields::admin-module',
        ['params'=>$params]
    );
    ?>
</div>
