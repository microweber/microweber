<?php
must_have_access();
?>

<div>

    <script>
        function reloadParentModule() {
            if (typeof mw !== 'undefined' && mw.top().app && mw.top().app.editor) {
                mw.top().app.editor.dispatch('onModuleSettingsChanged', ({'moduleId': '<?php print $params['id']  ?>'} || {}))
            }
        }

        Livewire.on('executeCustomFieldDelete', (e) => {
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
