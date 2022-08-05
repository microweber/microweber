(function () {

    var IconLoader = function (store) {
        var scope = this;

        var defaultVersion = '-1';

        var common = {
            'fontAwesome': {
                cssSelector: '.fa',
                detect: function (target) {
                    return target.classList.contains('fa');
                },
                render: function (icon, target) {
                    target.classList.add('fa');
                    target.classList.add(icon);
                },
                remove: function (target) {
                    target.classList.remove('fa');
                    var exception= ['fa-lg', 'fa-2x', 'fa-3x', 'fa-4x', 'fa-5x', 'fa-fw', 'fa-spin', 'fa-pule', 'fa-rotate-90',
                        'fa-rotate-180', 'fa-rotate-270', 'fa-flip-horizontal', 'fa-flip-vertical'];
                    mw.tools.classNamespaceDelete(target, 'fa-', undefined, undefined, exception);
                },
                icons: function () {
                     return new Promise(function (resolve) {
                         $.get(mw.settings.modules_url + 'microweber/api/fa.icons.js',function (data) {
                             resolve(JSON.parse(data));
                        });
                    });
                },
                name: 'Font Awesome',
                load:  mw.settings.libs_url + 'fontawesome-4.7.0' + '/css/font-awesome.min.css',
                unload: function () {
                    document.querySelector('link[href*="fontawesome-4.7.0"]').remove();
                },
                version: '4.7.0'
            },
            'materialIcons': {
                cssSelector: '.material-icons',
                detect: function (target) {
                    return target.classList.contains('material-icons');
                },
                render: function (icon, target) {
                    target.classList.add('material-icons');
                    target.innerHTML = (icon);
                },
                remove: function (target) {
                    mw.tools.removeClass(target, 'material-icons');
                    target.innerHTML = '';
                 },
                icons: function () {
                    return new Promise(function (resolve) {
                        $.get(mw.settings.modules_url + 'microweber/api/material.icons.js',function (data) {
                            resolve(JSON.parse(data));
                        });
                    });
                },
                name: 'Material Icons',
                load: mw.settings.libs_url + 'material_icons' + '/material_icons.css',
                unload: function () {
                    top.document.querySelector('link[href*="material_icons.css"]').remove();
                },
                version: 'mw'
            },
            'iconsMindLine': {
                cssSelector: '[class*="mw-micon-"]:not([class*="mw-micon-solid-"])',
                detect: function (target) {
                    return target.className.includes('mw-micon-') && !target.className.includes('mw-micon-solid-');
                },
                render: function (icon, target) {
                    target.classList.add(icon);
                },
                remove: function (target) {
                    mw.tools.classNamespaceDelete(target, 'mw-micon-', undefined, undefined, []);
                },
                icons: function () {
                    var scope = this;
                    var parse = function (cssLink) {
                        if(!cssLink.sheet){
                            return;
                        }
                        var icons = cssLink.sheet.cssRules;
                         var l = icons.length, i = 0, mindIcons = [];
                         for (; i < l; i++) {
                            var sel = icons[i].selectorText;
                            if (!!sel && sel.indexOf('.mw-micon-') === 0) {
                                var cls = sel.replace(".", '').split(':')[0];
                                mindIcons.push(cls);
                            }
                        }
                        return mindIcons
                    };
                    var load = function (cb) {
                        var cssLink = mw.top().win.document.querySelector('link[href*="mw-icons-mind/line"]');
                        if(cssLink) {
                            cb.call(undefined, cssLink);
                        }  else {
                            $.get(scope.load, function (data) {
                                cssLink = document.createElement('link');
                                cssLink.type = 'text/css';
                                cssLink.rel = 'stylesheet';
                                cssLink.href = scope.load;
                                $(document.head).append(cssLink);
                                cb.call(undefined, cssLink);
                            });
                        }
                    };
                    return new Promise(function (resolve) {
                        load(function (link) {
                            resolve(parse(link));
                        });
                    });
                },
                name: 'Icons Mind Line',
                load:  mw.settings.modules_url + 'microweber/api/libs/mw-icons-mind/line/style.css',
                unload: function () {
                    document.querySelector('link[href*="mw-icons-mind/line/style"]').remove();
                },
                version: 'mw_local'
            },
            'iconsMindSolid': {
                cssSelector: '[class*="mw-micon-solid-"]',
                detect: function (target) {
                    return target.className.includes('mw-micon-solid-');
                },
                render: function (icon, target) {
                    target.classList.add(icon);
                },
                remove: function (target) {
                    mw.tools.classNamespaceDelete(target, 'mw-micon-solid-', undefined, undefined, []);
                },
                icons: function () {
                    var scope = this;
                    var parse = function (cssLink) {
                        if(!cssLink.sheet){
                            return;
                        }
                        var icons = cssLink.sheet.cssRules;
                        var l = icons.length, i = 0, mindIcons = [];
                        for (; i < l; i++) {
                            var sel = icons[i].selectorText;
                            if (!!sel && sel.indexOf('.mw-micon-solid-') === 0) {
                                var cls = sel.replace(".", '').split(':')[0];
                                mindIcons.push(cls);
                            }
                        }
                        return mindIcons
                    };
                    var load = function (cb) {
                        var cssLink = mw.top().win.document.querySelector('link[href*="mw-icons-mind/solid"]');
                         if(cssLink) {
                            cb.call(undefined, cssLink);
                        }  else {
                            $.get(scope.load, function (data) {
                                cssLink = document.createElement('link');
                                cssLink.type = 'text/css';
                                cssLink.rel = 'stylesheet';
                                cssLink.href = scope.load;
                                $(document.head).append(cssLink);
                                cb.call(undefined, cssLink);
                            });
                        }
                    };
                    return new Promise(function (resolve) {
                        load(function (link) {
                            resolve(parse(link));
                        });
                    });
                },
                name: 'Icons Mind Solid',
                load:  mw.settings.modules_url + 'microweber/api/libs/mw-icons-mind/solid/style.css',
                unload: function () {
                    document.querySelector('link[href*="mw-icons-mind/solid/style"]').remove();
                },
                version: 'mw_local'
            },

            'materialDesignIcons': {
                cssSelector: '.mdi',
                detect: function (target) {
                    return target.classList.contains('mdi');
                },
                render: function (icon, target) {
                    target.classList.add('mdi');
                    target.classList.add(icon);
                },
                remove: function (target) {
                    mw.tools.classNamespaceDelete(target, 'mdi-', undefined, undefined, []);
                    target.classList.remove('mdi');
                },
                icons: function () {
                    var scope = this;
                    var load = function (cb) {
                        var cssLink = mw.top().win.document.querySelector('link[href*="materialdesignicons"]');
                        if(cssLink) {
                            cb.call(undefined, cssLink);
                        }  else {
                            $.get(scope.load, function (data) {
                                cssLink = document.createElement('link');
                                cssLink.type = 'text/css';
                                cssLink.rel = 'stylesheet';
                                cssLink.href = scope.load;
                                $(document.head).append(cssLink);
                                cb.call(undefined, cssLink);
                            });
                        }
                    };
                    return new Promise(function (resolve) {
                        load(function (link){
                            if(!link || !link.sheet) {
                                resolve([]);
                                return;
                            }
                            var icons = link.sheet.cssRules;
                            var l = icons.length, i = 0, mdiIcons = [];
                            for (; i < l; i++) {
                                var sel = icons[i].selectorText;
                                if (!!sel && sel.indexOf('.mdi-') === 0) {
                                    var cls = sel.replace(".", '').split(':')[0];
                                    mdiIcons.push(cls);
                                }
                            }
                            resolve(mdiIcons);
                        });
                    });
                },
                name: 'Material Design Icons',
                load:  mw.settings.modules_url + 'microweber/css/fonts/materialdesignicons/css/materialdesignicons.min.css',
                unload: function () {
                    document.querySelector('link[href*="materialdesignicons"]').remove();
                },
                version: 'mw_local'
            },
            'mwIcons': {
                cssSelector: '[class*="mw-icon-"]',
                detect: function (target) {
                    return target.className.includes('mw-icon-');
                },
                render: function (icon, target) {
                    target.classList.add(icon);
                },
                remove: function (target) {
                    mw.tools.classNamespaceDelete(target, 'mw-icon-', undefined, undefined, []);
                },
                icons: function () {
                    return new Promise(function (resolve) {
                        $.get(mw.settings.modules_url + 'microweber/api/microweber.icons.js',function (data) {
                            resolve(JSON.parse(data));
                        });
                    });
                },
                name: 'Microweber Icons',
                load:  mw.settings.modules_url + 'microweber/css/fonts/materialdesignicons/css/materialdesignicons.min.css',
                unload: function () {
                    document.querySelector('link[href*="materialdesignicons"]').remove();
                },
                version: 'mw_local'
            },
            SVGIcons: {
                cssSelector: 'svg[viewBox]',
                detect: function (target) {
                    return target.nodeName === 'SVG'
                },
                render: function (icon, target) {
                     target.innerHTML = icon.source;
                     var svg = target.querySelector('svg');
                     if (svg) {
                         svg.setAttribute('width', '1em');
                         svg.setAttribute('fill', 'currentColor');
                         svg.setAttribute('height', '1em');
                         svg.style.width = '1em';
                         svg.style.height = '1em';
                         svg.style.fill = 'currentColor';
                     }
                },
                remove: function (target) {
                    target.innerHTML = ''
                },
                icons: function () {
                    return new Promise(function (resolve) {
                        if(window.TemplateVectorIcons) {
                            resolve(TemplateVectorIcons)
                        } else {
                            $.getScript(mw.settings.template_url + 'template_icons.js', function (){
                                resolve(TemplateVectorIcons)
                            })
                        }

                    });
                },
                name: 'Vector Icons',
                load:  null,
                unload: function () {

                },
                version: 'mw_local'
            },
        };

        var storage = function () {
            if(!mw.top().__IconStorage) {
                mw.top().__IconStorage = [];
            }
            return mw.top().__IconStorage;
        };

        this.storage = store || storage;


        var iconSetKey = function (options) {
            return options.name + options.version;
        };

        var iconSetPush = function (options) {
            if(!storage().find(function (a) {return iconSetKey(options) === iconSetKey(a); })) {
                return storage().push(options);
            }
            return false;
        };

        var addFontIconSet = function (options) {
            options.version = options.version || defaultVersion;
            iconSetPush(options);

            if (typeof options.load === 'string') {
                mw.require(options.load);
            } else if (typeof options.load === 'function') {
                options.load();
            }
        };
        var addIconSet = function (conf) {

            if(typeof conf === 'string') {
                if (common[conf]) {
                    conf = common[conf];
                } else {
                    console.warn(conf + ' is not defined.');
                    return;
                }
            }
             if(!conf) return;
            conf.type = conf.type || 'font';
            if (conf.type === 'font') {
                return addFontIconSet(conf);
            }
        };

        this.addIconSet = function (conf) {
            addIconSet(conf);
            return this;
        };

        this.removeIconSet = function (name, version) {
            var str = storage();
            var item = str.find(function (a) { return a.name === name && (!version || a.version === version); });
            if (item) {
                if (item.unload) {
                    item.unload();
                }
                str.splice(str.indexOf(item), 1);
            }
        };


        this.init = function () {
            storage().forEach(function (iconSet){
                scope.addIconSet(iconSet);
            });
        };

    };

    mw.iconLoader = function (options) {
        return new IconLoader(options);
    };


})();


(function (){

    var IconPicker = function (options) {
        options = options || {};
        var loader = mw.iconLoader();
        var defaults = {
            iconsPerPage: 40,
            iconOptions: {
                size: true,
                color: true,
                reset: false
            }
        };


        this.settings = mw.object.extend(true, {}, defaults, options);
        var scope = this;
        var tabAccordionBuilder = function (items) {
            var res = {root: mw.element('<div class="mw-tab-accordion" data-options="tabsSize: medium, tabsColor: tab" />'), items: []};
            items.forEach(function (item){
                var el = mw.element('<div class="mw-accordion-item" />');
                var content = mw.element('<div class="mw-accordion-content mw-ui-box mw-ui-box-content">' +(item.content || '') +'</div>');
                var title = mw.element('<div class="mw-ui-box-header mw-accordion-title">' + item.title +'</div>');
                el.append(title);
                el.append(content);
                content.css({
                    overflowX: 'hidden'
                })

                res.root.append(el);
                res.items.push({
                    title: title,
                    content: content,
                    root: el,
                });
            });
            setTimeout(function (){
                if(mw.components) {
                    mw.components._init();
                }
            }, 10);
            return res;
        };

        var createUI = function () {
            var root = mw.element({
                props: { className: 'mw-icon-selector-root' }
            });
            var iconsBlockHolder, tabs, optionsHolder, iconsHolder;
            if(scope.settings.iconOptions) {
                tabs = tabAccordionBuilder([
                    {title: 'Icons'},
                    {title: 'Options'},
                ]);
                iconsBlockHolder = tabs.items[0].content;
                optionsHolder = tabs.items[1].content;
                root.append(tabs.root)
            } else {
                iconsBlockHolder = mw.element();
                root.append(iconsBlockHolder);
            }
            iconsHolder = mw.element().addClass('mw-icon-picker-icons-holder');
            iconsBlockHolder.append(iconsHolder);
            return {
                root: root,
                tabs: tabs,
                iconsBlockHolder: iconsBlockHolder,
                iconsHolder: iconsHolder,
                optionsHolder: optionsHolder
            };
        };


        var _e = {};

        this.on = function (e, f) { _e[e] ? _e[e].push(f) : (_e[e] = [f]) };
        this.dispatch = function (e, f) { _e[e] ? _e[e].forEach(function (c){ c.call(this, f); }) : ''; };

        var actionNodes = {};

        var createOptions = function (holder) {

            if(holder && scope.settings.iconOptions) {
                if(scope.settings.iconOptions.size) {
                    var label = mw.element('<div class="mw-icon-selector-flex"> <label class="mw-icon-selector-control-label">Icon size in px</label> </div>');
                    var sizeel = mw.element('<div class="mwiconlist-settings-section-block-item mw-icon-selector-flex  mw-icon-selector-12-column"></div>');
                    var sizeinput = mw.element('<input class="mw-icon-selector-form-control" type="number" min="8" max="200">');
                    var sizeinput2 = mw.element('<input class=" mw-icon-selector-form-control-range " type="range" min="8" max="200">');

                    actionNodes.size = sizeinput;
                    sizeinput.on('input', function () {
                        scope.dispatch('sizeChange', sizeinput.get(0).value);
                        sizeinput2.val(sizeinput.get(0).value);
                    });
                    sizeinput2.on('input', function () {
                        sizeinput.val(sizeinput2.get(0).value);
                        scope.dispatch('sizeChange', sizeinput.get(0).value);
                    });

                    holder.append(label);
                    sizeel.append(sizeinput);
                    sizeel.append(sizeinput2);
                    holder.append(sizeel);
                }
                if(scope.settings.iconOptions.color) {
                    cel = mw.element('<div class="mwiconlist-settings-section-block-item"><label class="mw-icon-selector-control-label  ps-2">Choose color</label></div>');
                    cinput = mw.element('<input class="mw-icon-selector-form-control mw-icon-selector-2-column" type="color">');
                    actionNodes.color = cinput;
                    cinput.on('input', function () {
                        scope.dispatch('colorChange', cinput.get(0).value);
                    });
                    var cpHolder = mw.element()
                    // cel.append(cinput);
                    setTimeout(function (){
                        mw.colorPicker({
                            element: cpHolder.get(0),
                            position: 'bottom-center',
                            onchange: function (color) {
                                scope.dispatch('colorChange', color);
                            }
                        });
                    }, 100)
                    cel.append(cpHolder);
                    holder.append(cel);
                }
                if(scope.settings.iconOptions.reset) {
                    var rel = mw.element('<div class="mwiconlist-settings-section-block-item"> </div>');
                    var rinput = mw.element('<input type="button" class="mw-ui-btn" value="' + mw.lang('Reset') + '">');
                    rinput.on('click', function () {
                        scope.dispatch('reset', rinput.get(0).value);

                    });
                    rel.append(rinput);
                    holder.append(rel);
                }
            }
        };

        var _prepareIconsLists = function (c) {
            var sets = loader.storage();
            var all = sets.length;
            var i = 0;
             sets.forEach(function (set){
                 if (!set._iconsLists) {
                     (function (aset){
                         aset.icons().then(function (data){
                             aset._iconsLists = data;
                             i++;
                             if(i === all) c.call(sets, sets);
                         });
                     })(set);
                 } else {
                     i++;
                     if(i === all) c.call(sets, sets);
                 }

            });
        };


        var createPaging = function(length, page){
            page = page || 1;
            var max = 999;
            var pages = Math.min(Math.ceil(length/scope.settings.iconsPerPage), max);
            var paging = document.createElement('div');
            paging.className = 'mw-paging mw-paging-small mw-icon-selector-paging';
            if(scope.settings.iconsPerPage >= length ) {
                return paging;
            }
            var active = false;
            for ( var i = 1; i <= pages; i++) {
                var el = document.createElement('a');
                el.innerHTML = i;
                el._value = i;
                if(page === i) {
                    el.className = 'active';
                    active = i;
                }
                el.onclick = function () {
                    comRender({page: this._value });
                };
                paging.appendChild(el);
            }
            var all = paging.querySelectorAll('a');
            for (var i = active - 3; i < active + 2; i++){
                if(all[i]) {
                    all[i].className += ' mw-paging-visible-range';
                }
            }


            if(active < pages) {
                var next = document.createElement('a');
                next.innerHTML = '&raquo;';
                next._value = active+1;
                next.className = 'mw-paging-visible-range mw-paging-next';
                next.innerHTML = '&raquo;';
                $(paging).append(next);
                next.onclick = function () {
                     comRender({page: this._value });
                }
            }
            if(active > 1) {
                var prev = document.createElement('a');
                prev.className = 'mw-paging-visible-range mw-paging-prev';
                prev.innerHTML = '&laquo;';
                prev._value = active-1;
                $(paging).prepend(prev);
                prev.onclick = function () {
                     comRender({page: this._value });
                };
            }

            return paging;
        };

        var searchField = function () {
            var time = null;
            scope.searchField =  mw.element({
                tag: 'input',
                props: {
                    className: 'mw-ui-searchfield w100',
                    placeholder: 'Search',
                    oninput: function () {
                        clearTimeout(time);
                        time = setTimeout(function (){
                            comRender();
                        }, 123);
                    }
                }
            });

            return scope.searchField;
        };

        var comRender = function (options) {
            options = options || {};
            options = mw.object.extend({}, {
                set: scope.selectField.get(0).options[scope.selectField.get(0).selectedIndex]._value,
                term: scope.searchField.get(0).value
            }, options);
            scope.ui.iconsHolder.empty().append(renderSearchResults(options));
        };

        var searchSelector = function () {
            var sel = mw.element('<select class="mw-ui-field w100" />');
            scope.selectField = sel;
            loader.storage().forEach(function (item) {
                var el = document.createElement('option');
                el._value = item;
                el.innerHTML = item.name;
                sel.append(el);
            });
            sel.on('change', function (){
                comRender()
            });
            return sel;
        };

        var search = function (conf) {
            conf = conf || {};
            conf.set = conf.set ||  loader.storage()[0];
            conf.page = conf.page || 1;
            conf.term = (conf.term || '').trim().toLowerCase();

            if (!conf.set._iconsLists) {
                return;
            }

            var all = conf.set._iconsLists.filter(function (f){ return (f.name || f).toLowerCase().indexOf(conf.term) !== -1; });

            var off = scope.settings.iconsPerPage * (conf.page - 1);
            var to = off + Math.min(all.length - off, scope.settings.iconsPerPage);

            return mw.object.extend({}, conf, {
                data: all.slice(off, to),
                all: all,
                off: off
            });
            /*for ( var i = off; i < to; i++ ) {

            }*/
        };

        var renderSearchResults = function (conf) {
            var res = search(conf);
            if(!res) return;
            var pg = createPaging(res.all.length, res.page);
            var root = mw.element();
            res.data.forEach(function (iconItem){
                var icon = mw.element({
                    tag: 'span',
                    props: {
                        className: 'mwiconlist-icon',
                        onclick: function (e) {
                            scope.dispatch('select', {
                                icon: iconItem,
                                renderer: res.set.render,
                                render: function () {
                                    var sets = loader.storage();
                                    sets.forEach(function (set) {
                                        set.remove(scope.target)
                                    })
                                    return res.set.render(iconItem, scope.target);
                                }
                            });
                            setTimeout(function (){
                                mw.trigger('iconInserted')
                            })
                        }
                    }
                });
                root.append(icon);
                res.set.render(iconItem, icon.get(0));
            });
            root.append(pg)
            return root;
        };

        var createIconsBlock = function () {
            mw.spinner({element: scope.ui.iconsHolder.get(0), size: 30}).show();
            _prepareIconsLists(function (){
                comRender();
                mw.spinner({element: scope.ui.iconsHolder.get(0)}).hide();
            });
        };

        this.create = function () {
            this.ui = createUI();
            createOptions(this.ui.optionsHolder);
            this.ui.iconsBlockHolder.prepend(searchField());

            this.ui.iconsBlockHolder.prepend(searchSelector());
            createIconsBlock();

        };

        this.get = function () {
            return this.ui.root.get(0);
        };

        this.dialog = function (method) {
            if(method === 'hide') {
                this._dialog.hide();
                return;
            }
            if(!this._dialog) {
                this._dialog = mw.top().dialog({content: this.get(), title: 'Select icon', closeButtonAction: 'hide', width: 450});
                mw.components._init();
            }
            this._dialog.show();
            return this._dialog;
        };

        this.destroy = function () {
            this.get().remove()
            if(this._dialog) {
                this._dialog.remove();
            }
            if(this._tooltip) {
                this._tooltip.remove();
            }
        };

        this.target = null;

        this.tooltip = function (target) {
            this.target = target;
            if(target === 'hide' && this._tooltip) {
                this._tooltip.style.display = 'none';
            } else {
                if (!this._tooltip) {
                    this._tooltip = mw.tooltip({
                        content: this.get(),
                        element: target,
                        position: 'bottom-center',
                    });
                } else {
                    mw.tools.tooltip.setPosition(this._tooltip, target, 'bottom-center');
                }

                this._tooltip.style.display = 'block';
                if(target.nodeType === 1) {
                    var css = getComputedStyle(target);
                    $('[type="number"],[type="range"]', this._tooltip).val(parseFloat(css.fontSize));

                    $('[type="color"]', this._tooltip).val(mw.color.rgbOrRgbaToHex(css.color));
                }

            }

            mw.components._init();
            return this._tooltip;
        };

        this.init = function () {
            this.create();
        };

        this.promise = function () {
            return new Promise(function (resolve){
               scope.on('select', function (data) {
                   resolve(data);
               });
            });
        };

        this.init();

    };


    mw.iconPicker = function (options) {
        return new IconPicker(options);
    };

})();






