<div>

    <script>
        window.selectModule = function ($moduleId) {
            var liveEditIframeWindow = mw.top().app.canvas.getWindow();
            var firstModule = liveEditIframeWindow.document.getElementById($moduleId);
            window.scrollToModule($moduleId);
            mw.top().app.editor.dispatch('onModuleSettingsRequest', firstModule);
        }

        window.scrollToModule = function ($moduleId) {
            var liveEditIframeWindow = mw.top().app.canvas.getWindow();
            var firstModule = liveEditIframeWindow.document.getElementById($moduleId);
            mw.top().app.liveEdit.handles.get('module').set(firstModule)
            //  mw.top().app.liveEdit.handles.get('module').position(firstModule)

            liveEditIframeWindow.mw.tools.scrollTo(firstModule, 100);
        }
    </script>


    <style>
        .sidebar-admin-modules-list-tree-wrapper ul {
            list-style: none;
        }
    </style>


    <div class="sidebar-admin-modules-list-tree-wrapper">
        @if($modulesMenuRender)
            {!! $modulesMenuRender !!}
        @endif

    </div>


    <?php

    /* @if($modulesData && isset($modulesData['modules']))
     * @foreach($modulesData['modules'] as $module)
     *
     * @if(isset($module['data-type']) && $module['data-type'] == 'layouts')
     * @continue
     * @endif
     *
     * @if(isset($module['data-type']) && $module['data-type'] !== 'layouts')
     *
     *
     * @endif
     *
     * @endforeach
     * @endif
     */

    ?>

</div>




