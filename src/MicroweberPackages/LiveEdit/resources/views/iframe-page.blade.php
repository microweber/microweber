<div class="  mw-admin-live-edit-page">

<script>
mw.required = [] ;
mw.require = function(url, inHead, key, defered) {
    if(!url) return;
    var defer;
    if(defered) {
        defer = ' defer ';
    } else {
        defer = '   '
    }
    if(typeof inHead === 'boolean' || typeof inHead === 'undefined'){
        inHead = inHead || false;
    }
    var keyString;
    if(typeof inHead === 'string'){
        keyString = ''+inHead;
        inHead = key || false;
    }
    if(typeof key === 'string'){
        keyString = key;
    }
    var toPush = url, urlModified = false;
    if (!!keyString) {
        toPush = keyString;
        urlModified = true
    }
    var t = url.split('.').pop();
    url = url.includes('//') ? url : (t !== "css" ? mw.settings.includes_url + "api/" + url  :  mw.settings.includes_url + "css/" + url);
    if(!urlModified) toPush = url;
    if (!~mw.required.indexOf(toPush)) {

    mw.required.push(toPush);
    url = url.includes("?") ?  url + '&mwv=' + mw.version : url + "?mwv=" + mw.version;
    if(document.querySelector('link[href="'+url+'"],script[src="'+url+'"]') !== null){
        return
    }

    var cssRel = " rel='stylesheet' ";

    if(defered){
        cssRel = " rel='preload' as='style' onload='this.onload=null;this.rel=\'stylesheet\'' ";
    }


    var string = t !== "css" ? "<script "+defer+"  src='" + url + "'></s>" : "<link "+cssRel+" href='" + url + "' />";

        if(typeof window.$?.fn === 'object'){
            $(document.head).append(string);
        }
        else{
            var el;
            if( t !== "css")  {
                el = document.createElement('script');
                el.src = url;
                el.defer = !!defer;
                el.setAttribute('type', 'text/javascript');
                document.head.appendChild(el);
            }
            else{

                el = document.createElement('link');
                if(defered) {
                    el.as='style';
                    el.rel='preload';
                    el.addEventListener('load', e => el.rel='stylesheet');
                } else {
                    el.rel='stylesheet';
                }


                el.href = url;
                document.head.appendChild(el);
            }
        }

    }
};


mw.parent = function(){
    if(window === top){
        return window.mw;
    }
    if(mw.tools.canAccessWindow(parent) && parent.mw){
        return parent.mw;
    }
    return window.mw;
};

mw.top = function() {
    if(!!mw.__top){
        return mw.__top;
    }
    var getLastParent = function() {
        var result = window;
        var curr = window;
        while (curr && mw.tools.canAccessWindow(curr) && (curr.mw || curr.parent.mw)){
            result = curr;
            curr = curr.parent;
        }
        mw.__top = curr.mw;
        return result.mw;
    };
    if(window === top){
        mw.__top = window.mw;
        return window.mw;
    } else {
        if(mw.tools.canAccessWindow(top) && top.mw){
            mw.__top = top.mw;
            return top.mw;
        } else{
            if(window.top !== window.parent){
                return getLastParent();
            }
            else{
                mw.__top = window.mw;
                return window.mw;
            }
        }
    }
};


mw._random = new Date().getTime();

mw.random = function() {
    return mw._random++;
};

mw.id = function(prefix) {
    prefix = prefix || 'mw-';
    return prefix + mw.random();
};

</script>


    @vite('src/MicroweberPackages/LiveEdit/resources/js/ui/live-edit-app.js')




    <div>


        <script>





            mw.lib.require('nouislider');
            mw.require('components.css')
            mw.require('liveedit_widgets.js')
            mw.require('admin_package_manager.js');
            mw.require('icon_selector.js');
            mw.lib.require('flag_icons');
            /*mw.iconLoader()

                .addIconSet('iconsMindLine')
                .addIconSet('iconsMindSolid')
                .addIconSet('fontAwesome')
                .addIconSet('materialDesignIcons')*/

        </script>

        <?php

        $bodyDarkClass = '';

        if(isset($_COOKIE['admin_theme_dark'])){
            $bodyDarkClass = 'theme-dark';
        }
        ?>


        <?php event_trigger('mw.live_edit.header'); ?>
    </div>

    <script>
        mw.quickSettings = {};
        mw.layoutQuickSettings = [];

        window.addEventListener('load', function () {
            if (mw.top() && mw.top().app && mw.top().app.liveEdit && mw.top().app.fontManager) {
                mw.top().app.fontManager.addFonts({!! json_encode(\MicroweberPackages\Utils\Misc\GoogleFonts::getEnabledFonts()) !!});
            }

            const scrollContainer = document.querySelector("#live-edit-frame-holder");
            const frame = scrollContainer.querySelector("iframe");

            scrollContainer.addEventListener("wheel", (e) => {
                if (e.target === scrollContainer) {
                    e.preventDefault();
                    const win = mw.top().app.canvas.getWindow();
                    win.scrollTo(0, (win.scrollY + e.deltaY) + (e.deltaY < 0 ? -10 : 10));
                }

            });
        });

        <?php


        /*

         @php
                 $templateColors = [];
                 $getTemplateConfig = mw()->template->get_config();
                 if($getTemplateConfig){
                 $templateColors = get_template_colors_settings();
                 }
                 if(empty($templateColors)){
                 $templateColors =[['value' => '#000000']];
                 }

         @endphp
         @if(!empty($templateColors))
             mw.tools.colorPickerColors = mw.tools.colorPickerColors || [];
             mw.tools.colorPickerColors = [
                 @foreach($templateColors as $color)
                 '{{ $color['value'] }}',
                 @endforeach
             ];
         @endif

         * */


        ?>
    </script>

    <div id="live-edit-app">
        Loading...
    </div>

    <div id="live-edit-frame-holder">

    </div>

    <?php print \MicroweberPackages\LiveEdit\Facades\LiveEditManager::headTags(); ?>
    <?php event_trigger('mw.live_edit.footer'); ?>


    <?php print mw_admin_footer_scripts(); ?>

    <script>

        mw.settings.adminUrl = '<?php print admin_url(); ?>';
        mw.settings.liveEditModuleSettingsUrls =  <?php print json_encode(\MicroweberPackages\Module\Facades\ModuleAdmin::getLiveEditSettingsUrls()); ?>;

    </script>


</div>
