<div>

<script>
    window.selectModule = function ($moduleId){
        var liveEditIframeWindow = mw.top().app.canvas.getWindow();
        var firstModule = liveEditIframeWindow.document.getElementById($moduleId);
        liveEditIframeWindow.mw.tools.scrollTo(firstModule);
        mw.top().app.editor.dispatch('onModuleSettingsRequest', firstModule);
    }
</script>





    @if($modulesMenuRender)
        {!! $modulesMenuRender !!}
    @endif




    <?php

    /* @if($modulesData && isset($modulesData['modules']))
    @foreach($modulesData['modules'] as $module)

    @if(isset($module['data-type']) && $module['data-type'] == 'layouts')
    @continue
    @endif

    @if(isset($module['data-type']) && $module['data-type'] !== 'layouts')


    @endif

    @endforeach
    @endif*/

    ?>

</div>




