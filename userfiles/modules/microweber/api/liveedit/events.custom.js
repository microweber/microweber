mw.liveedit.handleCustomEvents = function() {
    mw.on('moduleOver ElementOver', function(e, etarget, oevent){
        var target = mw.tools.firstParentOrCurrentWithAnyOfClasses(oevent.target, ['element', 'module']);
        if(target.id){
            mw.liveEditSelector.active(true);
            mw.liveEditSelector.setItem(target, mw.liveEditSelector.interactors);
        }
    });

    /*mw.on("ImageClick ElementClick ModuleClick", function(e, el, originalEvent){
        if(originalEvent) {
            el = mw.tools.firstParentOrCurrentWithAnyOfClasses(originalEvent.target, ['element', 'module'])
        }
        mw.liveEditSelector.select(el);
        if(mw.tools.hasClass(el, 'module')){
            mw.liveEditSelector.activeModule = el;
        }
    });*/

    mw.$(document.body).on('click', function (e) {
        var target = e.target;
        var can = mw.tools.firstParentOrCurrentWithAnyOfClasses(target, [
           'edit', 'module', 'element'
        ]);
        if(can) {
            var toSelect = mw.tools.firstNotInlineLevel(target);

            mw.liveEditSelector.select(target);

            if(mw.liveEditDomTree) {
                mw.liveEditDomTree.select(mw.wysiwyg.validateCommonAncestorContainer(target));

            }
        }


    });


    mw.on("DragHoverOnEmpty", function(e, el) {
        if ($.browser.webkit) {
            var _el = mw.$(el);
            _el.addClass("hover");
            if (!_el.hasClass("mw-webkit-drag-hover-binded")) {
                _el.addClass("mw-webkit-drag-hover-binded");
                _el.mouseleave(function() {
                    _el.removeClass("hover");
                });
            }
        }
    });
    mw.on("IconElementClick", function(e, el) {
        mw.liveedit.widgets.iconEditor(el);
        setTimeout(function () {
            mw.wysiwyg.contentEditable(el, false);
        })
    });

    mw.on("ComponentClick", function(e, node, type){

        if (type === 'icon'){
            mw.liveedit.widgets.iconEditor(node);
            return;

        }
        if(mw.settings.live_edit_open_module_settings_in_sidebar) {
            mw.log('ComponentClick' + type);
            if (!mw.liveEditSettings) {
                return; // admin mode
            }
            var uitype = type;
            if (type === 'element') {
                uitype = 'none';
            }
            if (type === 'safe-element') {
                //uitype = 'element' ;
                uitype = 'none';
            }
            if (node.nodeName === 'IMG') {
                uitype = 'image';
            }

            if (mw.liveEditSettings.active) {
                if (mw.sidebarSettingsTabs) {
                    if (uitype !== 'module') {
                        mw.sidebarSettingsTabs.setLastClicked();
                    } else {
                        mw.sidebarSettingsTabs.set(2);
                    }
                }
                mw.liveNodeSettings.set(uitype, node);
            }

        }
    });

    mw.on("ElementClick", function(e, el, c) {
        mw.$(".element-current").not(el).removeClass('element-current');
        if (mw.liveEditSelectMode === 'element') {
            mw.$(el).addClass('element-current');
        }

        mw.$('.module').each(function(){
            mw.wysiwyg.contentEditable(this, false)
        });
    });
    mw.on("PlainTextClick", function(e, el) {
        mw.wysiwyg.contentEditable(el, true);
        mw.$('.module').each(function(){
            mw.wysiwyg.contentEditable(this, false);
        });
    });


    mw.on("editUserIsTypingForLong", function(node){
        if(typeof(mw.liveEditSettings) != 'undefined'){
            if(mw.liveEditSettings.active){
                mw.liveEditSettings.hide();
            }
        }
    });
    mw.on("TableTdClick", function(e, el) {
        if (mw.liveedit && mw.liveedit.inline) {
            mw.liveedit.inline.setActiveCell(el, e);
            var td_parent_table = mw.tools.firstParentWithTag(el, 'table');
            if (td_parent_table) {
                mw.liveedit.inline.tableController(td_parent_table);
            }
        }
    });

    mw.on('UserInteraction', function(){
        mw.dropables.userInteractionClasses();
        mw.liveEditSelector.positionSelected();

    });

    mw.on('ElementOver moduleOver', function(e, target){
        var over_target_el = null;
        if(e.type === 'onElementOver'){
            over_target_el = mw.tools.firstParentOrCurrentWithAnyOfClasses(target, ['element'])
            if(over_target_el && !mw.tools.hasClass('element-over',over_target_el)){
                mw.tools.addClass(over_target_el, 'element-over')
            }
        } else if(e.type === 'moduleOver'){
            over_target_el = mw.tools.firstParentOrCurrentWithAnyOfClasses(target, ['module'])
            if(over_target_el && !mw.tools.hasClass('module-over',over_target_el)){
                mw.tools.addClass(over_target_el, 'module-over')
            }
        }
        if(over_target_el){
            mw.$(".element-over,.module-over").not(over_target_el).removeClass('element-over module-over');
        }
    });



    mw.on('CloneableOver', function(e, target, isOverControl){
        mw.drag.onCloneableControl(target, isOverControl)
    });

    var onModuleBetweenModulesTime = null;

    mw.on('ModuleBetweenModules', function(e, el, pos){
        clearTimeout(onModuleBetweenModulesTime);
        onModuleBetweenModulesTime = setTimeout(function(){
            if($("#moduleinbetween").length === 0){
                var tip = mw.tooltip({
                    content:'To drop this element here, select Clean container first',
                    element:el[0],
                    position:pos+'-center',
                    skin:'dark',
                    id:'moduleinbetween'
                });
                setTimeout(function(){
                    mw.$("#moduleinbetween").fadeOut(function(){
                        mw.$(this).remove();
                    });
                }, 3000);
            }
        }, 1000);
    });
};
