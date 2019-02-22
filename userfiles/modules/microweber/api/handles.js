mw.require('selector.js');

mw.Handle = function(options) {

    this.options = options || {};

    var scope = this;

    this.createWrapper = function() {
        this.wrapper = mwd.createElement('div');
        this.wrapper.id = this.options.id || ('mw-handle-' + mw.random());
        this.wrapper.className = 'mw-defaults mw-handle-item ' + (this.options.className || '');
        this.wrapper.contenteditable = false;
        $(this.wrapper).on('mousedown', function () {
            mw.tools.addClass(this, 'mw-handle-item-mouse-down');
        });
        $(document).on('mouseup', function () {
            mw.tools.removeClass(scope.wrapper, 'mw-handle-item-mouse-down');
        });
        mwd.body.appendChild(this.wrapper);
    };

    this.create = function() {
        this.createWrapper();
        this.createHandler();
        this.createMenu();
    };

    this.setTitle = function (icon, title) {
        this.handleIcon.innerHTML = icon;
        this.handleTitle.innerHTML = title;
    };

    this.hide = function () {
        $(this.wrapper).hide().removeClass('active');
        return this;
    };

    this.show = function () {
        $(this.wrapper).show();
        return this;
    };

    this.createHandler = function(){
        this.handle = mwd.createElement('span');
        this.handleIcon = mwd.createElement('span');
        this.handleTitle = mwd.createElement('span');
        this.handle.className = 'mw-handle-handler';
        this.handleIcon.className = 'mw-handle-handler-icon';
        this.handleTitle.className = 'mw-handle-handler-title';

        this.handle.appendChild(this.handleIcon);
        this.handle.appendChild(this.handleTitle);
        this.wrapper.appendChild(this.handle);

        this.handleTitle.onclick = function () {
            $(scope.wrapper).toggleClass('active');
        };
        $(mwd.body).on('click', function (e) {
            if(!mw.tools.hasParentWithId(e.target, scope.wrapper.id)){
                $(scope.wrapper).removeClass('active');
            }
        });
    };

    this.menuButton = function (data) {
        var btn = mwd.createElement('span');
        btn.className = 'mw-handle-menu-item';
        if(data.icon) {
            var icon = mwd.createElement('span');
            icon.className = data.icon + ' mw-handle-menu-item-icon';
            btn.appendChild(icon);
        }
        btn.appendChild(mwd.createTextNode(data.title));
        if(data.className){
            btn.className += (' ' + data.className);
        }
        if(data.id){
            btn.id = data.id;
        }
        if(data.action){
            btn.onclick = function () {
                data.action();
            };
        }
        return btn;
    };

    this._defaultButtons = [
        {
            title:'Delete',
            icon: 'mw-icon-bin',
            action: function () {
                mw.drag.delete_element(mw.handle_module);
            }
        }
    ];

    this.createMenuDynamicHolder = function(){
        var dn = mwd.createElement('div');
        dn.className = 'mw-handle-menu-dynamic';
        return dn;
    };
    this.createMenu = function(){
        this.menu = mwd.createElement('div');
        this.menu.className = 'mw-handle-menu';
        if (this.options.menu) {
            for (var i=0; i<this.options.menu.length; i++) {
                if(this.options.menu[i].title !== '{dynamic}') {
                    this.menu.appendChild(this.menuButton(this.options.menu[i])) ;
                }
                else {
                    this.menu.appendChild(this.createMenuDynamicHolder()) ;
                }

            }
        }
        this.wrapper.appendChild(this.menu);
    };

    this.create();


};


mw._activeModuleOver = {
    module: null,
    element: null
};

mw._initHandles = {
    elements: function(){
        mw.handleElement = new mw.Handle({
            id: 'mw-handle-item-element',
            menu:[
                {
                    title: 'Remove',
                    icon: 'mw-icon-bin',
                    action: function () {
                        mw.drag.delete_element(mw._activeElementOver);
                    }
                }
            ]
        });

        $(mw.handleElement.wrapper).draggable({
            handle: mw.handleElement.handleIcon,
            cursorAt: {
                top: -30
            },
            start: function() {
                mw.isDrag = true;
                mw.dragCurrent = mw.ea.data.currentGrabbed = mw._activeElementOver;

                if(!mw.dragCurrent.id){
                    mw.dragCurrent.id = 'element_' + mw.random()
                }
                $(mw.dragCurrent).invisible().addClass("mw_drag_current");
                mw.trigger("AllLeave");
                mw.drag.fix_placeholders();
                $(mwd.body).addClass("dragStart");
                mw.image_resizer._hide();
                mw.wysiwyg.change(mw.dragCurrent);
                mw.smallEditor.css("visibility", "hidden");
                mw.smallEditorCanceled = true;
            },
            stop: function() {
                $(mwd.body).removeClass("dragStart");
            }
        });

        $(mw.handleElement.wrapper).mouseenter(function() {
            /*var curr = mw._activeElementOver;
            $(this).draggable("option", "helper", function() {
                var clone = $(curr).clone(true);
                clone.css({
                    width: $(curr).width(),
                    height: $(curr).height()
                });
                return clone;
            });*/
        }).click(function() {
            if (!$(mw._activeElementOver).hasClass("element-current")) {
                $(".element-current").removeClass("element-current");

                if (mw._activeElementOver.nodeName === 'IMG') {

                    mw.trigger("ImageClick", mw._activeElementOver);
                } else {
                    mw.trigger("ElementClick", mw._activeElementOver);
                }
            }
            /*if (!$(mw._activeElementOver).hasClass('module')) {
                mw.wysiwyg.select_element($(mw._activeElementOver)[0]);
            }*/
        });

        mw.on("ElementOver", function(a, element) {
            mw._activeElementOver = element;
            mw.$(".mw_edit_delete, .mw_edit_delete_element, .mw-sorthandle-moveit, .column_separator_title").show();
            if (!mw.ea.canDrop(element)) {
                mw.$(".mw_edit_delete, .mw_edit_delete_element, .mw-sorthandle-moveit, .column_separator_title").hide();
                return false;
            }
            var el = $(element);

            var o = el.offset();

            var pleft = parseFloat(el.css("paddingLeft"));
            var left_spacing = o.left;
            if (mw.tools.hasClass(element, 'jumbotron')) {
                left_spacing = left_spacing + pleft;
            }
            if(left_spacing<0){
                left_spacing = 0;
            }

            var icon = '<span class="mw-handle-element-title-icon">'+element.nodeName+'</span>';

            var title = 'Text';
            switch(element.nodeName) {
                case 'P':
                    title = 'Paragraph';
                    break;
                case 'H1':
                    title = 'Heading 1';
                    break;
                case 'H2':
                    title = 'Heading 2';
                    break;
                case 'H3':
                    title = 'Heading 3';
                    break;
                case 'H4':
                    title = 'Heading 4';
                    break;
                case 'H5':
                    title = 'Heading 5';
                    break;
                case 'H6':
                    title = 'Heading 6';
                    break;
            }

            mw.handleElement.setTitle(icon, title);



            $(mw.handleElement.wrapper).css({
                top: o.top,
                left: left_spacing
            });

            if(!element.id) {
                element.id = "element_" + mw.random();
            }

            mw.dropable.removeClass("mw_dropable_onleaveedit");

        });
        
        
    },
    modules: function () {
        mw.handleModule = new mw.Handle({
            id: 'mw-handle-item-module',
            menu:[
                {
                    title: 'Settings',
                    icon: 'mw-icon-gear',
                    action: function () {
                        mw.drag.module_settings(mw._activeModuleOver,"admin");
                    }
                },
                {
                    title: 'Move Up',
                    icon: 'mw-icon-arrow-up-b',
                    className: '',
                    id:'mw_handle_module_up',
                    action: function () {
                        mw.drag.replace($(mw._activeModuleOver), 'prev');
                    }
                },
                {
                    title: 'Move Down',
                    icon: 'mw-icon-arrow-down-b',
                    id:'mw_handle_module_down',
                    action: function () {
                        mw.drag.replace($(mw._activeModuleOver), 'next');
                    }
                },
                {
                    title: '{dynamic}',
                    icon: 'mw-icon-arrow-down-b'
                },
                {
                    title: 'Remove',
                    icon: 'mw-icon-bin',
                    action: function () {
                        mw.drag.delete_element(mw._activeModuleOver);
                    }
                }
            ]
        });
        $(mw.handleModule.wrapper).draggable({
            handle: mw.handleModule.handleIcon,
            distance:20,
            cursorAt: {
                top: -30
            },
            start: function() {
                mw.isDrag = true;
                mw.dragCurrent = mw._activeModuleOver;
                if(!mw.dragCurrent.id){
                    mw.dragCurrent.id = 'module_' + mw.random();
                }
                $(mw.dragCurrent).invisible().addClass("mw_drag_current");
                mw.trigger("AllLeave");
                mw.drag.fix_placeholders();
                $(mwd.body).addClass("dragStart");
                mw.image_resizer._hide();
                mw.wysiwyg.change(mw.dragCurrent);
                mw.smallEditor.css("visibility", "hidden");
                mw.smallEditorCanceled = true;
            },
            stop: function() {
                $(mwd.body).removeClass("dragStart");
            }
        })
            .on("mousedown", function(e){
                mw.liveEditSelectMode = 'none';
            });

        $(mw.handleModule.handle).on('mouseenter touchstart', function() {
            /*var curr = 'module_' + mw.random();
            $(mw.handleModule.wrapper).draggable("option", "helper", function() {
                var clone = $(mw._activeModuleOver).clone(true);
                clone.css({
                    width: $(curr).width(),
                    height: $(curr).height()
                });
                return clone;
            });*/
        })
        /*.on('click', function() {
            var curr = $(this).data("curr");
            if (!$(curr).hasClass("element-current")) {
                mw.trigger("ElementClick", curr);
            }
        });*/

        mw.on('moduleOver', function(e, element){

            mw._activeModuleOver = element;
            mw.$(".mw-handle-menu-dynamic", mw.handleModule.wrapper).empty();
            mw.$('#mw_handle_module_up,#mw_handle_module_down').hide();
            if(mw._activeModuleOver && mw._activeModuleOver.getAttribute('data-type') === 'layouts'){
                var $el = $(mw._activeModuleOver);
                var hasedit =  mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst($el[0],['edit', 'module']);
                if(hasedit){
                    if($el.prev('[data-type="layouts"]').length !== 0){
                        mw.$('#mw_handle_module_up').show();
                    }
                    if($el.next('[data-type="layouts"]').length !== 0){
                        mw.$('#mw_handle_module_down').show();
                    }
                }
            }

            var el = $(element);
            var o = el.offset();
            var width = el.width();
            var pleft = parseFloat(el.css("paddingLeft"));

            var lebar =  mwd.querySelector("#live_edit_toolbar");
            var minTop = lebar ? lebar.offsetHeight : 0;
            if(mw.templateTopFixed) {
                var ex = document.querySelector(mw.templateTopFixed);
                if(ex && !ex.contains(el[0])){
                    minTop += ex.offsetHeight;
                }
            }

            var marginTop =  -30;
            var topPos = o.top;

            if(topPos<minTop){
                topPos = minTop;
            }
            var ws = $(window).scrollTop();
            if(topPos<(ws+minTop)){
                topPos=(ws+minTop);
                marginTop =  -15;
                if(el[0].offsetHeight <100){
                    topPos = o.top+el[0].offsetHeight;
                    marginTop =  0;
                }
            }

            var handleLeft = o.left + pleft;
            if (handleLeft < 0) {
                handleLeft = 0;
            }
            mw.handleModule.show()
            $(mw.handleModule.wrapper)
                .removeClass('active')
                .css({
                    top: topPos,
                    left: handleLeft,
                    //width: width,
                    marginTop: marginTop
                }).addClass('mw-active-item');


            mw.$('#mw_handle_module_up, #mw_handle_module_down').hide();

            if(element && element.getAttribute('data-type') === 'layouts'){
                var $el = $(element);
                var hasedit =  mw.tools.hasParentsWithClass($el[0],'edit');
                if(hasedit){
                    if($el.prev('[data-type="layouts"]').length !== 0){
                        mw.$('#mw_handle_module_up').show();
                    }
                    if($el.next('[data-type="layouts"]').length !== 0){
                        mw.$('#mw_handle_module_down').show();
                    }
                }
            }
            var order;
            if(mw.tools.hasClass(element, 'module')){
                order = mw.tools.parentsOrder(element, ['edit', 'module']);
            }
            else {
                order = mw.tools.parentsAndCurrentOrder(element, ['edit', 'module']);
            }
            if (
                !mw.tools.parentsOrCurrentOrderMatchOrOnlyFirstOrNone(element, ['allow-drop', 'nodrop'])
                && (order.edit === -1 || (order.edit > order.module && order.module>-1))) {

            } else {
                mw.$(".mw_edit_delete, #mw_handle_module .mw-sorthandle-moveit, .column_separator_title").show();

                if (order.edit === -1 || (order.module > -1 && order.edit > order.module)) {
                    mw.$("#mw_handle_module .mw-sorthandle-moveit").hide();
                    mw.$("#mw_handle_module .mw_edit_delete").hide();
                } else {
                    mw.$("#mw_handle_module .mw-sorthandle-moveit").show();
                    mw.$("#mw_handle_module .mw_edit_delete").show();
                }
            }


            /*************************************/


            // el = $(element);
            var title = el.dataset("mw-title");
            var id = el.attr("id");
            var module_type = (el.dataset("type") || el.attr("type")).trim();
            var cln = el[0].querySelector('.cloneable');
            if(cln || mw.tools.hasClass(el[0], 'cloneable')){
                if(($(cln).offset().top - el.offset().top) < 20){
                    mw.tools.addClass(mw.drag._onCloneableControl, 'mw-module-near-cloneable');
                } else {
                    mw.tools.removeClass(mw.drag._onCloneableControl, 'mw-module-near-cloneable');
                }
            }

            var mod_icon = mw.live_edit.getModuleIcon(module_type);


            mw.handleModule.setTitle(mod_icon, (title ? title : mw.msg.settings))


            if(!mw.handleModule){
                return;
            }

            mw.tools.classNamespaceDelete(mw.handleModule, 'module-active-');
            mw.tools.addClass(mw.handleModule, 'module-active-' + module_type.replace(/\//g, '-'));


            var mw_edit_settings_multiple_holder_id = 'mw_edit_settings_multiple_holder-' + id;



            if (mw.live_edit_module_settings_array && mw.live_edit_module_settings_array[module_type]) {

                mw.$(".mw_edit_settings", mw.handle_module).hide();


                if (mw.$('#' + mw_edit_settings_multiple_holder_id).length === 0) {

                    var new_el = mwd.createElement('div');
                    new_el.className = 'mw_edit_settings_multiple_holder';
                    new_el.id = mw_edit_settings_multiple_holder_id;
                    $('.mw_edit_settings', mw.handle_module).after(new_el);

                    // mw.$('#' + mw_edit_settings_multiple_holder_id).html(make_module_settings_handle_html);
                    var settings = mw.live_edit_module_settings_array[module_type];


                    mw.$(settings).each(function () {

                        if (this.view) {
                            var new_el = mwd.createElement('a');
                            new_el.className = 'mw_edit_settings_multiple';
                            new_el.title = this.title;
                            new_el.draggable = 'false';
                            var btn_id = 'mw_edit_settings_multiple_btn_' + mw.random();
                            new_el.id = btn_id;

                            if (this.type && this.type === 'tooltip') {
                                new_el.href = 'javascript:mw.drag.current_module_settings_tooltip_show_on_element("' + btn_id + '","' + this.view + '", "tooltip"); void(0);';

                            } else {
                                new_el.href = 'javascript:mw.drag.module_settings(undefined,"' + this.view + '"); void(0);';

                            }


                            var icon = '';

                            if (this.icon) {
                                icon = '<i class="mw-edit-module-settings-tooltip-icon ' + this.icon + '"></i>';
                            }

                            new_el.innerHTML = '' +
                                icon +
                                '<span class="mw-edit-module-settings-tooltip-btn-title">' +
                                this.title +
                                '</span>' +
                                '';

                            console.log(new_el)

                            mw.$(".mw-handle-menu-dynamic", mw.handleModule.wrapper).append(new_el);


                        }

                    });
                }
                $('#' + mw_edit_settings_multiple_holder_id + ':hidden').show();
            } else {
                mw.$(".mw_edit_settings", mw.handle_module).show();

            }

            if (mod_icon) {
                var sorthandle_main = mw.$(".mw-element-name-handle", mw.handle_module).parent().parent();
                if (sorthandle_main) {
                    mw.$(sorthandle_main).addClass('mw-element-name-handle-no-fallback-icon');
                }
            }



            /*************************************/


            if(!element.id) {
                element.id = "module_" + mw.random();
            }
        });
    }
};




$(document).ready(function () {

    mw._initHandles.modules();
    mw._initHandles.elements();
});