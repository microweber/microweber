
mw.top()._icons = mw.top()._icons || [];

(function () {
    var icons = {
        findByIcon: function (name) {
            return  mw.top()._icons.some(function (font) {
                return font.icons.some(function (icon) {
                    return icon === name;
                });
            });
        },
        findByName: function (name) {
            return  mw.top()._icons.some(function (font) {
                return font.name === name;
            });
        },
        availableIcons: function () {
            for (var i = 0; i < mw.top()._icons.length; i++) {
                var set = mw.top()._icons[i];
                if (set.url) {
                    mw.moduleCSS(url);
                }
            }
        },
        cleanIcon: function (node) {
            for (var i = 0; i < mw.top()._icons.length; i++) {
                var set = mw.top()._icons[i];
                if (set.remove) {
                    set.remove(node);
                }
            }
        },
        addFontIcons: function (options) {
            if(!options) return;
            if(!options.icons) return;
            if(!options.name) return;
            if(!options.render) return;
            if(!options.remove) return;
            if(options.url) {
                mw.moduleCSS(options.url);
            }
            options.exists = options.exists || function() {
                return  mw.top()._icons.some(function (font) {
                    return font.name === options.name;
                });
            };
            if(options.exists()) return;
            var toAdd = {
                render: function (icon, target) {
                    mw.icons.cleanIcon(target);
                    return options.render(icon, target);
                },
                remove: options.remove,
                icons: options.icons,
                name: options.name
            };
            mw.top()._icons = mw.top()._icons.concat(toAdd);
            mw._iconsCache = false;
        },
        searchGUI: function (list) {
            var scope = this;
            var nav = document.createElement('div');
            nav.className = 'mw-ui-btn-nav';
            nav.innerHTML = ''
                /*+ '<div class="mw-dropdown mw-dropdown-default">'
                    + '<span class="mw-dropdown-value mw-ui-btn mw-dropdown-val">All</span>'
                    + '<div class="mw-dropdown-content">'
                        + '<ul></ul>'
                    + '</div>'
                + '</div>'*/;
            var ul = $('ul', list);
            $.each(mw._icons, function(){
               var li = document.createElement('li');
               li.value = this.name;
               li.innerHTML = this.name;
                ul.append(li);
            });
            var input =  document.createElement('input');
            input.type = 'mw-text';
            input.className = 'mw-ui-field';
            input.placeholder = 'Filter icons...';

            nav.append(input);
            input.oninput = function(){
                scope.search(list, this.value);
            };
            return nav;
        },
        search: function (list, term) {
            term = (term || '').trim().toLowerCase();
            var all = list.querySelectorAll('.mw-icon-list-icon'), i = 0;
            if(!term) {
                for (  ; i<all.length; i++) {
                    mw.tools.removeClass(all[i], 'mw-icon-list-icon-hidden');
                }
                return;
            }
            for (  ; i<all.length; i++) {
                var has = all[i]._searchvalue.indexOf(term) !== -1;
                mw.tools[has ? 'removeClass' : 'addClass'](all[i], 'mw-icon-list-icon-hidden');
            }
        },
        list: function () {
            var list = document.createElement('div');
            list.className = 'mw-icon-list';
            list.appendChild(this.searchGUI(list));
            var fragment = document.createDocumentFragment();
            var i, ic, ticons = mw.top()._icons;
            for( i = 0; i < ticons.length; i++){
                var set = ticons[i];
                var title = document.createElement('h5');
                var section = document.createElement('div');
                section.className = 'mw-icon-list-section';
                title.innerHTML = set.name;
                fragment.appendChild(title);
                for( ic = 0; ic < set.icons.length; ic++){
                    var icon = document.createElement('span');
                    var iconc = document.createElement('span');
                    icon.className = 'mw-icon-list-icon mw-icon-list-' + set.name;
                    icon._searchvalue = set.icons[ic].toLowerCase();
                    icon._value = [set.icons[ic], set.render, icon];
                    icon.appendChild(iconc)
                    icon.onclick = function (ev) {
                        $(list).trigger('_$select', this._value);
                    };
                    set.render(set.icons[ic], iconc);
                    section.appendChild(icon);
                }
                fragment.appendChild(section);
            }
            list.appendChild(fragment);
            return list;
        },


        listOptions: function ( options ) {
            var def = {
                color: true,
                size: true
            };

            var holder = function () {
                var holder = document.createElement('div');
                holder.className = 'mw-icon-list-settings-section-block-item';
                return holder;
            };

            options = typeof options === 'undefined' ? def : options;
            if(!options) {
                return;
            }
            var final = {
              $e: $({})
            };
            if(options === true) {
                options = def;
            }
            if(mw.tools.isEmptyObject(options)){ return; }
            var accordion = document.createElement('div');
            accordion.className = 'mw-tab-accordion';

            var content = '' +
                    '<div class="mw-accordion-item">' +
                        '<div class="mw-ui-box-header mw-accordion-title">Icons</div>' +
                        '<div class="mw-accordion-content mw-ui-box mw-ui-box-content mw-icon-list-settings-icons"></div>'+
                    '</div>' +
                    '<div class="mw-accordion-item">' +
                        ' <div class="mw-ui-box-header mw-accordion-title">' +
                        ' Settings' +
                        ' </div>' +
                        ' <div class="mw-accordion-content mw-ui-box mw-ui-box-content mw-icon-list-settings-section"></div>' +
                    '</div>';

            accordion.innerHTML = content;
            var root = mw.$('.mw-icon-list-settings-section', accordion);
            if(options.size) {
                var size = document.createElement('div');
                size.className = 'mw-field';
                size.dataset.before = 'Icon size';
                var cpinput = document.createElement('input');
                cpinput.type = 'number';
                cpinput.min = '8';
                cpinput.placeholder = 'e.g.: 16';
                size.appendChild(cpinput);
                cpinput.oninput = function (ev) {
                    final.$e.trigger('sizeChange', [parseFloat(this.value), this.value]);
                };
                var hsize = holder();
                hsize.appendChild(size);
                root.append(hsize);
            }
            if(options.color) {
                var cp = document.createElement('div');
                cp.className = 'mw-icon-list-settings-section-color-picker';
                final.colorPicker = mw.colorPicker({
                    element:cp,
                    position:'bottom-center',
                    method:'inline',
                    onchange:function(color){
                        final.$e.trigger('colorChange', [color]);
                    }
                });

                var hcolor = holder();
                hcolor.appendChild(cp);
                root.append(hcolor);
            }

            final.element = accordion;
            return final;

        },

        dialog: function (iconsSettings) {
            var list = this.list();
            var content = list;
            if(iconsSettings) {
                var listOptions = this.listOptions(iconsSettings);
                mw.$('.mw-icon-list-settings-icons', listOptions.element).append(list);
                content = listOptions.element;
            }
            var dialog = mw.top().dialog({
                content: content,
                height: 'auto',
                autoHeight: true
            });
            $(list).on('_$select', function (e, icon, render) {
                dialog.result({
                    icon: icon,
                    render: render
                });
            });
            if(iconsSettings) {
                listOptions.$e.on('sizeChange', function (val, originalValue) {
                    dialog.result({
                        fontSize: val,
                        fontSizeOriginal: originalValue
                    });
                });
                listOptions.$e.on('colorChange', function (color) {
                    dialog.result({
                        color: color
                    });
                });
            }

            mw.dropdown(list);
            return dialog;
        },

        tooltip: function(config, iconsSettings) {
            if(!config || !config.element) return;
            var list = this.list();
            var content = list, listOptions;
            if(iconsSettings) {
                listOptions = this.listOptions(iconsSettings);
                mw.$('.mw-icon-list-settings-icons', listOptions.element).append(list);
                content = listOptions.element;
            }
            var settings = $.extend({}, config, {content: content});
            var tooltip = mw.tooltip(settings);

            var res = {
                $e: $({}),
                tooltip: tooltip,
                list: list,
                content: content,
                iconSettings: listOptions
            };
            $(list).on('_$select', function (e, icon, render) {
                var result = {
                    icon: icon,
                    render: render
                };
                res.$e.trigger('Result', [result]);
            });
            if(iconsSettings) {
                listOptions.$e.on('sizeChange', function (e, val, originalValue) {
                    res.$e.trigger('sizeChange', [val, originalValue]);
                });
                listOptions.$e.on('colorChange', function (e, color) {
                    res.$e.trigger('colorChange', [color]);
                });
            }

            setTimeout(function () {
                mw.components._init();
            }, 78);

            return res;
        },
        faIconsInit: function(){
            var faIconsArray = [];
            var exc = ['fa-lg', 'fa-2x', 'fa-3x', 'fa-4x', 'fa-5x', 'fa-fw', 'fa-spin', 'fa-pule', 'fa-rotate-90', 'fa-rotate-180', 'fa-rotate-270', 'fa-flip-horizontal', 'fa-flip-vertical'];
            var faicons = mwd.querySelector('link[href*="/font-awesome.min.css"]');
            if (!faicons) {
                faicons = mwd.querySelector('link[href*="/font-awesome.css"]');
            }
            if (faicons  && faicons.sheet) {
                try {
                    var icons = faicons.sheet.cssRules;
                    var l = icons.length, i = 0, cls;
                    for (; i < l; i++) {
                        sel = icons[i].selectorText;
                        if (!!sel && sel.indexOf('.fa-') === 0) {
                            cls = sel.replace(".", '').split(':')[0];
                            if(exc.indexOf(cls) === -1) {
                                faIconsArray.push(cls);
                            }

                        }
                    }
                } catch (e) {
                }
            }
            if(faIconsArray.length) {
                this.addFontIcons({
                    icons: faIconsArray,
                    name: 'Font Awesome',
                    remove: function(target) {
                        mw.tools.classNamespaceDelete(target, 'fa-');
                        mw.tools.removeClass(target, 'fa');
                    },
                    render: function(icon, target) {
                        mw.$(target).addClass( 'fa ' + icon );
                    }
                });
            }
        },
        mindIconsInit: function(){
            var mindIcons = [];
            var faicons = mwd.querySelector('link[href*="/mw-icons-mind/"]');
            if (faicons != null && faicons.sheet) {
                try {
                    var icons = faicons.sheet.cssRules;
                    var l = icons.length, i = 0;
                    for (; i < l; i++) {
                        var sel = icons[i].selectorText;
                        if (!!sel && sel.indexOf('.mw-micon-') === 0) {
                            var cls = sel.replace(".", '').split(':')[0];
                            mindIcons.push('mw-micon- ' + cls);
                        }
                    }
                } catch (e) {
                }
            }
            if(mindIcons.length) {
                this.addFontIcons({
                    icons: mindIcons,
                    name: 'Icons Mind',
                    remove: function(target) {
                        mw.tools.classNamespaceDelete(target, 'mw-micon-');
                        mw.tools.classNamespaceDelete(target, 'icon-');
                        mw.tools.removeClass(target, 'icon');
                    },

                    render: function(icon, target) {
                        mw.$(target).addClass('mw-icon ' + icon);
                    }
                });
            }
        },
        _defaultsPrepare: function(c) {
            if(!mw.materialIcons){
                $.getScript(mw.settings.modules_url + 'microweber/api/microweber.icons.js', function(data){
                    $.getScript(mw.settings.modules_url + 'microweber/api/material.icons.js', function(data){
                        c.call();
                    });
                });
            } else {
                c.call();
            }
        },
        _defaults: function() {
            var scope = this;
            this._defaultsPrepare(function () {
                icons.mindIconsInit();
                icons.faIconsInit();
                icons.addFontIcons({
                    icons: mw.materialIcons,
                    name: 'Material Icons',
                    remove: function(target) {
                        mw.tools.removeClass(target, 'material-icons');
                        target.innerHTML = '';
                    },
                    render: function(icon, target) {
                        mw.$(target).addClass('mw-icon material-icons').html(icon);

                    }
                });
                icons.addFontIcons({
                    icons: mw.microweberIcons,
                    name: 'Microweber Icons',
                    remove: function(target) {
                        mw.tools.classNamespaceDelete(target, 'mw-icon-');
                    },
                    render: function(icon, target) {
                        mw.$(target).addClass('mw-icon ' + icon);
                    }
                });

            });

        }
    };
   $(window).on('load', function () {
       icons._defaults();
       icons.availableIcons();

   })
    mw.icons = icons;
})();


mw.iconSelector = mw.iconSelector || {
    _string: '',
    _activeElement: null,
    defaultIcons: false,

    iconFontClasses: [],

    addCSS:function(selector, propertyStartsWith, method){
       var css = mwd.querySelector(selector), icons;
       if(css === null ) return;
       for(var i=0; i < css.sheet.cssRules.length; i++){
          var item = css.sheet.cssRules[i];
          var sel = item.selectorText;
          if (!!sel && sel.indexOf(propertyStartsWith) === 0) {
              var cls = sel.split(':')[0];
              mw.iconSelector.iconFontClasses.push(cls.split('.')[1])
          }
       }
       mw.iconSelector.settingsUI(true);
    },
    _parentSynced: false,
    parentSync: function () {
        if(!this._parentSynced && mw.top() !== mw && mw.top().iconSelector) {
            this._parentSynced = true;
            mw.iconSelector.iconFontClasses = $.merge( mw.iconSelector.iconFontClasses, mw.top().iconSelector.iconFontClasses );
        }
    },
    init: function () {
        this.parentSync();
        if (mw.iconSelector.iconFontClasses.length == 0) {
            try {
                var uicss = mwd.querySelector('link[href*="/ui.css"]'), icons;
                if(uicss === null){
                    var def = mwd.querySelector('link[href*="/default.css"]');
                    if(def !== null){
                        for(var i=0; i<def.sheet.cssRules.length; i++){
                            var item = def.sheet.cssRules[i];
                            if( item.cssText.indexOf('ui.css') != -1){
                                icons = item.styleSheet.rules;
                            }
                        }
                    }
                }
                else{
                    icons = uicss.sheet.cssRules;
                }

                var i = 0, html = '', sel, cls;
                for (; i < icons.length; i++) {
                    sel = icons[i].selectorText;
                    if (!!sel && sel.indexOf('.mw-icon-') === 0) {
                        cls = sel.replace(".", '').split(':')[0];
                        if (mw.iconSelector.iconFontClasses.indexOf(cls) === -1) {
                            mw.iconSelector.iconFontClasses.push(cls);
                        }
                    }
                }
            } catch (e) {
            }


            //check font awesome
            var faicons = mwd.querySelector('link[href*="/font-awesome.min.css"]');
            if (faicons != null && faicons.length == 0) {
                var faicons = mwd.querySelector('link[href*="/font-awesome.css"]');
            }

            if (faicons != null && faicons.length != 0 && typeof(faicons.sheet) != 'undefined' && typeof(faicons.sheet) != 'null') {
                try {
                    var icons = faicons.sheet.cssRules;
                    var l = icons.length, i = 0, html = '';
                    for (; i < l; i++) {
                        var sel = icons[i].selectorText;
                        if (!!sel && sel.indexOf('.fa-') === 0) {
                            var cls = sel.replace(".", '').split(':')[0];
                            if (mw.iconSelector.iconFontClasses.indexOf('fa ' + cls) === -1) {
                                mw.iconSelector.iconFontClasses.push('fa ' + cls);
                            }
                        }
                    }
                } catch (e) {
                }


            }

            //check semantic ui
            var faicons = mwd.querySelector('link[href*="/semantic.min.css"]');
            if (faicons !== null) {
                var faicons = mwd.querySelector('link[href*="/semantic.css"]');
            }



            if (faicons != null && faicons.length != 0 && typeof(faicons.sheet) != 'undefined' && typeof(faicons.sheet) != 'null') {
                try {
                    var icons = faicons.sheet.cssRules;

                    var l = icons.length, i = 0, html = '';
                    for (; i < l; i++) {
                        var sel = icons[i].selectorText;
                        if (!!sel && sel.indexOf('i.icon') === 0) {
                            var cls = sel.replace("i.", '').split(':')[0];
                            cls = cls.split('.').join(' ');
                            if (mw.iconSelector.iconFontClasses.indexOf(cls) === -1) {
                                mw.iconSelector.iconFontClasses.push(cls);
                            }
                        }
                    }
                } catch (e) {
                }
            }

        }



        try {
                var icons = mwd.querySelector('link[data-iconset]').sheet.cssRules;

                var l = icons.length, i = 0, html = '';
                for (; i < l; i++) {
                    var sel = icons[i].selectorText;
                    if (!!sel) {
                        var cls = sel.replace(".", '').split(':')[0];
                        if (mw.iconSelector.iconFontClasses.indexOf(cls) === -1) {
                            mw.iconSelector.iconFontClasses.push(cls);
                        }
                    }
                }
            } catch (e) {
            }

    },


    mindIconsTab:function(){
        if(this.mindIcons){

        }
    },
    mindIconsInit:function(){
        this.mindIcons = true;
        var faicons = mwd.querySelector('link[href*="/mw-icons-mind/"]');
        if (faicons != null && faicons.length == 0) {
            var faicons = mwd.querySelector('link[href*="/mw-icons-mind/"]');
        }
        if (faicons != null && faicons.length != 0 && typeof(faicons.sheet) != 'undefined' && typeof(faicons.sheet) != 'null') {
            try {
                var icons = faicons.sheet.cssRules;
                var l = icons.length, i = 0, html = '';
                for (; i < l; i++) {
                    var sel = icons[i].selectorText;
                    if (!!sel && sel.indexOf('.mw-micon-') === 0) {
                        var cls = sel.replace(".", '').split(':')[0];
                        if (mw.iconSelector.iconFontClasses.indexOf('mw-micon- ' + cls) === -1) {
                            mw.iconSelector.iconFontClasses.push('mw-micon- ' + cls);
                        }
                    }
                }
            } catch (e) {
            }
        }
    },
    uiHTML: function (refresh) {
        var uicss = mw.iconSelector.iconFontClasses;
        var l = uicss.length, i = 0, html = '';
        for (; i < l; i++) {
            var sel = uicss[i];
            html += '<li onclick="mw.iconSelector.select(\'' + sel + '\')" title="' + sel + '"><i class="' + sel + '"></i></li>';

        }



        var theOptions = ''
            + '<div id="icon-color-pick"></div>'
            + '<br><br>'
            + '<span class="mw-ui-label">Icon Size</span>'
            + '<input class="mw-ui-field mw-icons-selector-set-icon-size" type="number" name="mw-icons-selector-set-icon-size"  min="10" max="120" oninput="mw.iconSelector.set_icon_size(this.value)"  />';




        mw.iconSelector.getMaterialIconsPopup(function(){
            mw.iconSelector._string = html + this;
            mw.iconSelector._string = '<ul class="mw-icons-selector">' + mw.iconSelector._string + '</ul>';

            mw.iconSelector._string =
                '<div class="mw-ui-btn-nav mw-ui-btn-nav-tabs live-edit-icon-pick-menu"><span class="mw-ui-btn">Icons</span><span class="mw-ui-btn">Options</span></div>'
                + '<div class="mw-ui-box mw-ui-box-content live-edit-icon-pick-tab">' + mw.iconSelector._string + '</div>'
                + '<div class="mw-ui-box mw-ui-box-content live-edit-icon-pick-tab">' + theOptions + '</div>'
            ;
            if(!mw.iconSelectorGUI){

                mw.iconSelectorGUI = mw.tooltip({
                    content: mw.iconSelector._string,
                    element: refresh ? mwd.createElement('div') : mw.iconSelector._activeElement,
                    position: 'bottom-center',
                });

                /*mw.$("#js-live-edit-icon-settings-holder").empty().append(mw.iconSelector._string)

                mw.iconSelectorGUI = mw.$("#js-live-edit-icon-settings-holder");
*/

                if(refresh){
                    mw.$(".tooltip-icon-picker .mw-tooltip-content").html(mw.iconSelector._string)
                    mw.$(mw.iconSelectorGUI).hide()
                }
            }
            else if(refresh){
                mw.$(".tooltip-icon-picker .mw-tooltip-content").html(mw.iconSelector._string)
                mw.$(mw.iconSelectorGUI).hide()
            }

            mw.$(mw.iconSelectorGUI).addClass('tooltip-icon-picker')


            mw.$('.mw-icons-selector', mw.iconSelectorGUI).show();
            var tabs = mw.tabs({
                nav:'.live-edit-icon-pick-menu .mw-ui-btn',
                tabs:'.live-edit-icon-pick-tab'
            });

            tabs.set(0)

            mw.colorPicker({
                element:'#icon-color-pick',
                position:'bottom-center',
                method:'inline',
                onchange:function(color){
                    mw.$(mw.iconSelector._activeElement).css("color", color);
                }
            });
            mw.iconSelector.searchInit();

        })
    },
    settingsUI: function (refresh) {



        if (mw.iconSelector.iconFontClasses.length == 0 && this.defaultIcons) {
            mw.iconSelector.init();
        }

        if (mw.iconSelector.iconFontClasses.length == 0 && !this.defaultIcons) {
            mw.iconSelector.mindIconsInit();
        }



        if (!mw.iconSelector._string || refresh) {

            this.uiHTML()

        }
        else {

            if(mw.iconSelector._activeElement !== null){


            mw.$(mw.iconSelectorGUI).show();
            mw.$('.mw-icons-selector', mw.iconSelectorGUI).show();


            mw.tools.tooltip.setPosition(mw.iconSelectorGUI, mw.iconSelector._activeElement, 'bottom-center');
          }
          else{

                mw.$(mw.iconSelectorGUI).hide();
          }

        }
        var icons_size_val = mw.$(mw.iconSelector._activeElement).css("fontSize");
        var a = parseInt(icons_size_val);

        if (a > 0) {
            mw.$('.mw-icons-selector-set-icon-size').val(a);
        }

       if(mw.iconSelector._activeElement === null || refresh){
         mw.$(mw.iconSelectorGUI).hide();

       }

    },
    _exceptions:['fa-lg', 'fa-2x', 'fa-3x', 'fa-4x', 'fa-5x', 'fa-fw', 'fa-spin', 'fa-pule', 'fa-rotate-90', 'fa-rotate-180', 'fa-rotate-270', 'fa-flip-horizontal', 'fa-flip-vertical'],
    select: function (icon, is) {
        if (mw.iconSelector._activeElement !== null && typeof mw.iconSelector._activeElement !== 'undefined') {
            mw.tools.removeClass(mw.iconSelector._activeElement, mw.iconSelector.iconFontClasses);
            mw.wysiwyg.elementRemoveFontIconClasses(mw.iconSelector._activeElement);
            mw.tools.classNamespaceDelete(mw.iconSelector._activeElement, 'mw-icon-');
            mw.tools.classNamespaceDelete(mw.iconSelector._activeElement, 'material-');
            mw.tools.classNamespaceDelete({
              element:mw.iconSelector._activeElement,
              namespace:'fa-',
              exceptions:mw.iconSelector._exceptions
            });
            mw.$(mw.iconSelector._activeElement).addClass(icon + ' mw-wysiwyg-custom-icon ');

            if(!!is){
              mw.$(mw.iconSelector._activeElement).html(is)
            }
            else{
              mw.$(mw.iconSelector._activeElement).empty().removeClass('material-icons')
            }

            if(typeof(mw.iconSelector._activeElement) != 'undefined' && typeof(mw.iconSelector._activeElement.nodeName) != 'undefined'){
                if(mw.iconSelector._activeElement.nodeName == "INPUT"){
                    mw.$(mw.iconSelector._activeElement).val(icon).trigger( "change");
                }
            }

        }
        mw.$(mw.tools.firstParentWithClass(mw.iconSelector._activeElement, 'edit')).addClass('changed');
        //mw.iconSelector._activeElement = null;


        //$(mw.iconSelectorGUI).hide();
    },
    hide: function () {
        if (mw.iconSelector._string != '') {
            mw.$(mw.iconSelectorGUI).hide();
        }
    },
    search:function(val){
      val = val.toLowerCase();
      var final = [];
      $.each(mw.iconSelector.iconFontClasses, function(){
        if(this.toLowerCase().indexOf(val) !== -1){
          final.push(this)
        }
      })
      $.each(mw.materialIcons, function(){
        if(this.indexOf(val) !== -1){
          final.push(this)
        }
      });
      return final;
    },
    searchInit:function(el){
      if(!!mw.iconSelector.searchelement) return;
      mw.iconSelector.searchelement = document.createElement('input');
      mw.iconSelector.searchelement.className = 'mw-ui-searchfield icon-picker-search';
      mw.iconSelector.searchelement.__time = null;
      mw.$('.' + mw.iconSelector._exceptions.join(', .'), mw.iconSelectorGUI).remove()
      mw.$(mw.iconSelector.searchelement).on('input keyup paste', function(){
        clearTimeout(mw.iconSelector.searchelement.__time);
        mw.iconSelector.searchelement.__time = setTimeout(function(){
          var val = mw.iconSelector.searchelement.value.toLowerCase();
          if(!val){
            mw.$(".live-edit-icon-pick-tab li").show();
            return;
          }
          var results = mw.iconSelector.search(mw.iconSelector.searchelement.value);
          mw.$(".live-edit-icon-pick-tab li").hide().each(function(){
            if(this.title.toLowerCase().indexOf(val) !== -1){
              mw.$(this).show();
            }
          })
        }, 100)
      })
      mw.$(mw.iconSelector.searchelement).appendTo('.tooltip-icon-picker')
    },
    iconDropdown:function(selector, options){
        var $el = mw.$(selector);
        var el = $el[0];
        if(!el) return;
        options = options || {};
        options.mode = options.mode || 'absolute';
        var btn = mwd.createElement('span');
        btn.className = 'mw-ui-btn';
        var btnIcon;
        $(btn).append(options.value || '<span></span>');
        btnIcon = btn.firstChild;
        btn.appendChild(mwd.createTextNode('Choose icon'));

        btn.onclick = function(){
            var dialog = mw.top().icons.dialog(false);
            mw.top().$(dialog).on('Result', function(e, res){
                if(options.onchange) {
                    var elVal = document.createElement('span');
                    res.render(res.icon, elVal);
                    res.render(res.icon, btnIcon);
                    options.onchange.call(undefined, elVal.outerHTML, el)
                }
                dialog.remove();
            });
        };
        $el.empty().append(btn);
    },
    _iconDropdown:function(selector, options){
        var el = mw.$(selector)[0];
        if(!el) return;
        options = options || {}
        options.mode = options.mode || 'absolute';
        if (mw.iconSelector.iconFontClasses.length == 0) {
            mw.iconSelector.init();
        }

        if (mw.iconSelector.iconFontClasses.length == 0) {
            // if no icon sets, disable the icon editor
            return;
        }
        var uicss = mw.iconSelector.iconFontClasses;
        var l = uicss.length, i = 0, html = '';
        for (; i < l; i++) {
            var sel = uicss[i];
            html += '<li data-value="'+sel+'" title="'+sel+'"><i class="' + sel + '"></i></li>';

        }





        var input = document.createElement('input');
        input.__time = null;
        input.className = options.className || 'mw-ui-field';

        var holder = document.createElement('div');
        holder.__time = null;
        holder.className = 'mw-ui-field-holder';
        holder.innerHTML = '<span class="mw-ui-field-icon">'+(options.value ? options.value : '')+'</span>';
        holder.appendChild(input)


        mw.iconSelector.getMaterialIconsDropdown(function(){
          html = '<ul class="mw-icons-selector mw-icons-selector-dropdown" style="position:'+options.mode+';width:100%; left:0;top:100%;">' + html + this + '</ul>';
          mw.$(selector).addClass('mw-icons-selector-dropdown-wrapper').empty().append(holder).append(html)
          mw.$('li', el).on('mousedown touchstart', function(){
            var val =  mw.$(this).html()
            mw.$('.mw-ui-field-icon', holder).html(val);
             if(typeof options.onchange === 'function'){
                 options.onchange.call(undefined, val, el)
             }
              mw.$('.mw-icons-selector-dropdown', el).on()
          });
        })


        mw.$(input).on('focus', function(){
            mw.$(this).parent().parent().addClass('focused')
        });
         mw.$(input).on('input change', function(){
             var val = $.trim(this.value);
             clearTimeout(input.__time);
             (function(val, el){
                 input.__time = setTimeout(function(){
                        if(!val){
                            mw.$('.mw-icons-selector li', el).show()
                        }
                        else{
                            mw.$('.mw-icons-selector li', el).hide().filter('[data-value*="'+val+'"]').show()
                        }
                        if(typeof options.onchange == 'function'){
                             //options.onchange.call(undefined, input.value, el)
                        }
                 }, 300);
             })(val, el);

        });
        el.__time = null;




        mw.$(input).on('blur', function(){
            (function(el){
                clearTimeout(el.__time)
                el.__time = setTimeout(function(){
                    mw.$(el).parent().parent().removeClass('focused')
                }, 200)
            })(this)
        });

        return {
            value: function (val) {
                if(!val) return mw.$('.mw-ui-field-icon', holder).html();
                mw.$('.mw-ui-field-icon', holder).html(val);
            }
        }

    },
    getMaterialIconsPopup:function(callback){

      mw.lib.require('material_icons')
      var html = '';
      if(!mw.materialIcons){
          $.getScript(mw.settings.modules_url + 'microweber/api/material.icons.js', function(data){
            $.each(mw.materialIcons, function(){
              html += '<li onclick="mw.iconSelector.select(\'material-icons\', \''+this+'\')" title="'+this+'"><i class="material-icons">'+this+'</i></li>';
            })
            callback.call(html)
          })
        }
        else{
          $.each(mw.materialIcons, function(){
            html += '<li  onclick="mw.iconSelector.select(\'material-icons\', \''+this+'\')" title="'+this+'"><i class="material-icons">'+this+'</i></li>';
          })
          callback.call(html)
        }
    },
    getMaterialIconsDropdown:function(callback){
      mw.lib.require('material_icons')
      var html = '';
      if(!mw.materialIcons){
          $.getScript(mw.settings.modules_url + 'microweber/api/material.icons.js', function(data){
            $.each(mw.materialIcons, function(){
              html += '<li data-value="material-icons" title="'+this+'"><i class="material-icons">'+this+'</i></li>';
            })
            callback.call(html)
          })
        }
        else{
          $.each(mw.materialIcons, function(){
            html += '<li data-value="material-icons" title="'+this+'"><i class="material-icons">'+this+'</i></li>';
          })
          callback.call(html)
        }
    },
    set_icon_size: function (val) {

        var a = parseInt(val);

        if (a > 5) {
            mw.$(mw.iconSelector._activeElement).css("fontSize", a + "px");
        } else {
            mw.$(mw.iconSelector._activeElement).css("fontSize", "inherit");
        }


    }
};
$(document).ready(function () {
    mw.iconSelector.init();
});
