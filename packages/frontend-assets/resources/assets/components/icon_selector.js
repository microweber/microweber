(function () {

    var IconLoader = function (store) {
        var scope = this;

        var defaultVersion = '-1';

        var common = {
            'fontAwesome': {
                cssSelector: '.fa',
                detect: function (target) {
                    return target.classList.includes('fa');
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
                        const icons = ['glass','music','search','envelope-o','heart','star','star-o','user','film','th-large','th','th-list','check','times','search-plus','search-minus','power-off','signal','cog','trash-o','home','file-o','clock-o','road','download','arrow-circle-o-down','arrow-circle-o-up','inbox','play-circle-o','repeat','refresh','list-alt','lock','flag','headphones','volume-off','volume-down','volume-up','qrcode','barcode','tag','tags','book','bookmark','print','camera','font','bold','italic','text-height','text-width','align-left','align-center','align-right','align-justify','list','outdent','indent','video-camera','picture-o','pencil','map-marker','adjust','tint','pencil-square-o','share-square-o','check-square-o','arrows','step-backward','fast-backward','backward','play','pause','stop','forward','fast-forward','step-forward','eject','chevron-left','chevron-right','plus-circle','minus-circle','times-circle','check-circle','question-circle','info-circle','crosshairs','times-circle-o','check-circle-o','ban','arrow-left','arrow-right','arrow-up','arrow-down','share','expand','compress','plus','minus','asterisk','exclamation-circle','gift','leaf','fire','eye','eye-slash','exclamation-triangle','plane','calendar','random','comment','magnet','chevron-up','chevron-down','retweet','shopping-cart','folder','folder-open','arrows-v','arrows-h','bar-chart','twitter-square','facebook-square','camera-retro','key','cogs','comments','thumbs-o-up','thumbs-o-down','star-half','heart-o','sign-out','linkedin-square','thumb-tack','external-link','sign-in','trophy','github-square','upload','lemon-o','phone','square-o','bookmark-o','phone-square','twitter','facebook','github','unlock','credit-card','rss','hdd-o','bullhorn','bell','certificate','hand-o-right','hand-o-left','hand-o-up','hand-o-down','arrow-circle-left','arrow-circle-right','arrow-circle-up','arrow-circle-down','globe','wrench','tasks','filter','briefcase','arrows-alt','users','link','cloud','flask','scissors','files-o','paperclip','floppy-o','square','bars','list-ul','list-ol','strikethrough','underline','table','magic','truck','pinterest','pinterest-square','google-plus-square','google-plus','money','caret-down','caret-up','caret-left','caret-right','columns','sort','sort-desc','sort-asc','envelope','linkedin','undo','gavel','tachometer','comment-o','comments-o','bolt','sitemap','umbrella','clipboard','lightbulb-o','exchange','cloud-download','cloud-upload','user-md','stethoscope','suitcase','bell-o','coffee','cutlery','file-text-o','building-o','hospital-o','ambulance','medkit','fighter-jet','beer','h-square','plus-square','angle-double-left','angle-double-right','angle-double-up','angle-double-down','angle-left','angle-right','angle-up','angle-down','desktop','laptop','tablet','mobile','circle-o','quote-left','quote-right','spinner','circle','reply','github-alt','folder-o','folder-open-o','smile-o','frown-o','meh-o','gamepad','keyboard-o','flag-o','flag-checkered','terminal','code','reply-all','star-half-o','location-arrow','crop','code-fork','chain-broken','question','info','exclamation','superscript','subscript','eraser','puzzle-piece','microphone','microphone-slash','shield','calendar-o','fire-extinguisher','rocket','maxcdn','chevron-circle-left','chevron-circle-right','chevron-circle-up','chevron-circle-down','html5','css3','anchor','unlock-alt','bullseye','ellipsis-h','ellipsis-v','rss-square','play-circle','ticket','minus-square','minus-square-o','level-up','level-down','check-square','pencil-square','external-link-square','share-square','compass','caret-square-o-down','caret-square-o-up','caret-square-o-right','eur','gbp','usd','inr','jpy','rub','krw','btc','file','file-text','sort-alpha-asc','sort-alpha-desc','sort-amount-asc','sort-amount-desc','sort-numeric-asc','sort-numeric-desc','thumbs-up','thumbs-down','youtube-square','youtube','xing','xing-square','youtube-play','dropbox','stack-overflow','instagram','flickr','adn','bitbucket','bitbucket-square','tumblr','tumblr-square','long-arrow-down','long-arrow-up','long-arrow-left','long-arrow-right','apple','windows','android','linux','dribbble','skype','foursquare','trello','female','male','gratipay','sun-o','moon-o','archive','bug','vk','weibo','renren','pagelines','stack-exchange','arrow-circle-o-right','arrow-circle-o-left','caret-square-o-left','dot-circle-o','wheelchair','vimeo-square','try','plus-square-o','space-shuttle','slack','envelope-square','wordpress','openid','university','graduation-cap','yahoo','google','reddit','reddit-square','stumbleupon-circle','stumbleupon','delicious','digg','pied-piper','pied-piper-alt','drupal','joomla','language','fax','building','child','paw','spoon','cube','cubes','behance','behance-square','steam','steam-square','recycle','car','taxi','tree','spotify','deviantart','soundcloud','database','file-pdf-o','file-word-o','file-excel-o','file-powerpoint-o','file-image-o','file-archive-o','file-audio-o','file-video-o','file-code-o','vine','codepen','jsfiddle','life-ring','circle-o-notch','rebel','empire','git-square','git','hacker-news','tencent-weibo','qq','weixin','paper-plane','paper-plane-o','history','circle-thin','header','paragraph','sliders','share-alt','share-alt-square','bomb','futbol-o','tty','binoculars','plug','slideshare','twitch','yelp','newspaper-o','wifi','calculator','paypal','google-wallet','cc-visa','cc-mastercard','cc-discover','cc-amex','cc-paypal','cc-stripe','bell-slash','bell-slash-o','trash','copyright','at','eyedropper','paint-brush','birthday-cake','area-chart','pie-chart','line-chart','lastfm','lastfm-square','toggle-off','toggle-on','bicycle','bus','ioxhost','angellist','cc','ils','meanpath','buysellads','connectdevelop','dashcube','forumbee','leanpub','sellsy','shirtsinbulk','simplybuilt','skyatlas','cart-plus','cart-arrow-down','diamond','ship','user-secret','motorcycle','street-view','heartbeat','venus','mars','mercury','transgender','transgender-alt','venus-double','mars-double','venus-mars','mars-stroke','mars-stroke-v','mars-stroke-h','neuter','genderless','facebook-official','pinterest-p','whatsapp','server','user-plus','user-times','bed','viacoin','train','subway','medium','y-combinator','optin-monster','opencart','expeditedssl','battery-full','battery-three-quarters','battery-half','battery-quarter','battery-empty','mouse-pointer','i-cursor','object-group','object-ungroup','sticky-note','sticky-note-o','cc-jcb','cc-diners-club','clone','balance-scale','hourglass-o','hourglass-start','hourglass-half','hourglass-end','hourglass','hand-rock-o','hand-paper-o','hand-scissors-o','hand-lizard-o','hand-spock-o','hand-pointer-o','hand-peace-o','trademark','registered','creative-commons','gg','gg-circle','tripadvisor','odnoklassniki','odnoklassniki-square','get-pocket','wikipedia-w','safari','chrome','firefox','opera','internet-explorer','television','contao','500px','amazon','calendar-plus-o','calendar-minus-o','calendar-times-o','calendar-check-o','industry','map-pin','map-signs','map-o','map','commenting','commenting-o','houzz','vimeo','black-tie','fonticons'];
                        resolve(icons.map(i => `fa-${i}`));
                    });
                },
                name: 'Font Awesome',
                load:  mw.settings.libs_url + 'font-awesome/font-awesome.css',
                unload: function () {
                    document.querySelector('link[href*="font-awesome"]').remove();
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

                load:  mw.settings.site_url + 'vendor/microweber-packages/frontend-assets-libs/mw-icons-mind/line/style.css',
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

                load:  mw.settings.site_url + 'vendor/microweber-packages/frontend-assets-libs/mw-icons-mind/solid/style.css',
                unload: function () {
                    document.querySelector('link[href*="mw-icons-mind/solid/style"]').remove();
                },
                version: 'mw_local'
            },

            'materialDesignIcons': {
                cssSelector: '.mdi',
                detect: function (target) {
                    return target.classList.includes('mdi');
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
                // load:  mw.settings.modules_url + 'microweber/css/fonts/materialdesignicons/css/materialdesignicons.min.css',
                load:  mw.settings.site_url + 'vendor/microweber-packages/frontend-assets-libs/mdi/css/materialdesignicons.css',
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
                if(mw !== mw.top()) {
                    mw.top().require(options.load);
                }
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
                var el = mw.element('<div class="mw-accordion-item px-3" />');
                var content = mw.element('<div class="mw-accordion-content px-1">' +(item.content || '') +'</div>');
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
                props: { className: 'mw-icon-selector-root mx-auto w-100 my-3' }
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
                iconsBlockHolder = mw.element().addClass('px-3');
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
                    var label = mw.element(`
                        <div class="mw-icon-selector-flex my-2">
                            <label class="mw-icon-selector-control-label live-edit-label px-0">${mw.lang('Size')}</label>
                        </div>
                    `);

                    var currentSize = 20;

                    if(scope.settings.target) {
                        currentSize = parseFloat(getComputedStyle(scope.settings.target).fontSize)
                    }

                    var sizeel = mw.element('<div class="mwiconlist-settings-section-block-item input-group input-group-flat" style="width: 230px;"><span class="input-group-text"><kbd>px</kbd></span></div>');
                    var sizeinput = mw.element(`<input class="form-control" value="${currentSize}" type="number" min="8" max="200">`);
                    var sizeinput2 = mw.element(`<input class="mw-icon-selector-form-control-range" value="${currentSize}" type="range" min="8" max="200">`);

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

                    sizeel.prepend(sizeinput2);
                    sizeel.prepend(sizeinput);
                    holder.append(sizeel);
                }
                if(scope.settings.iconOptions.color) {
                    var cel = mw.element('<div class="mwiconlist-settings-section-block-item"><label class="mw-icon-selector-control-label live-edit-label px-0 mb-2 ps-2">Choose color</label></div>');
                    var cinput = mw.element('<input class="mw-icon-selector-form-control mw-icon-selector-2-column" type="color">');
                    actionNodes.color = cinput;
                    cinput.on('input', function () {
                        scope.dispatch('colorChange', cinput.get(0).value);


                    });
                    var cpHolder = mw.element()
                    // cel.append(cinput);
                    setTimeout(function (){
                        var cpOptions = {
                            element: cpHolder.get(0),
                            position: 'bottom-center',
                            onchange: function (color) {
                                scope.dispatch('colorChange', color);
                            },

                        };
                        if(scope.settings.target) {
                            cpOptions.value = getComputedStyle(scope.settings.target).color
                        }
                        mw.colorPicker(cpOptions);
                    }, 100)
                    cel.append(cpHolder);
                    holder.append(cel);
                }
                if(scope.settings.iconOptions.imageReplace) {
                    var rel = mw.element(`
                    <div class="my-3">
                        <label class="mw-icon-selector-control-label live-edit-label px-0 mb-2 ps-2">${mw.lang('Replace with image')}</label>
                    </div>`);
                    var rinput = mw.element(`
                        <button type="button" style="min-width: 150px" class="btn btn-light border-0 go-live-edit-href-set admin-toolbar-buttons">
                        ${mw.lang('Choose')}
                        </button>
                    `);
                    rinput.on('click', function () {
                        mw.filePickerDialog( (url) => {
                            scope.dispatch('iconReplaced', {
                                type: 'image',
                                url: url,
                            });
                        });

                    });
                    rel.append(rinput);
                    holder.append(rel);
                }
                if(scope.settings.iconOptions.reset) {
                    var rel = mw.element(`
                    <div class="my-3">
                        <label class="mw-icon-selector-control-label live-edit-label px-0 mb-2 ps-2">${mw.lang('Reset icon options')}</label>
                    </div>`);
                    var rinput = mw.element(`
                        <button type="button" style="min-width: 150px" class="btn btn-outline-secondary ">
                        ${mw.lang('Reset')}
                        </button>
                    `);
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
            var paging = document.createElement('ul');
            paging.className = 'pagination mw-live-edit-pagination-tabler d-flex flex-wrap mx-auto mt-3';
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
                el.classList.add('page-link');
                el.onclick = function () {
                    comRender({page: this._value });
                };
                var elLi = document.createElement('li');
                elLi.className = 'page-item';

                elLi.appendChild(el);


                paging.appendChild(elLi);
            }
            var all = paging.querySelectorAll('li');
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
                next.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M9 6l6 6l-6 6"></path></svg>';
                $(paging).append(next);
                next.onclick = function () {
                     comRender({page: this._value });
                }
            }
            if(active > 1) {
                var prev = document.createElement('a');
                prev.className = 'mw-paging-visible-range mw-paging-prev';
                prev.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M15 6l-6 6l6 6"></path></svg>';
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
                    className: 'form-control-live-edit-input',
                    placeholder: 'Search',
                    oninput: function () {
                        clearTimeout(time);
                        time = setTimeout(function (){
                            comRender();
                        }, 123);
                    }
                }
            });

            var searchFieldWrapper = mw.element('<div class="form-control-live-edit-label-wrapper m-0 mt-2"></div>');
            var searchFieldSpanEffect = mw.element('<span class="form-control-live-edit-bottom-effect"></span>');
            searchFieldWrapper.append(scope.searchField);
            searchFieldWrapper.append(searchFieldSpanEffect);

            return searchFieldWrapper;
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
            var sel = mw.element('<select class="form-select form-control-live-edit-input" />');
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

            var searchSelectorWrapper = mw.element('<div class="form-control-live-edit-label-wrapper m-0 mt-2"></div>');
            searchSelectorWrapper.append(sel);

            return searchSelectorWrapper;
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
            if(!res.data.length) {
                root.append( mw.element(`<div class="alert" role="alert"><h5 class="text-secondary fw-normal">No results for <strong>${conf.term}</strong></h5></div>`));
            }
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
                this._dialog = mw.top().dialog({content: this.get(), title: 'Select icon', closeButtonAction: 'hide', width: 450,overlayClose: true});

                this._dialog.dialogContainer.style.padding = '0px';
                this._dialog.overlay.style.backgroundColor = 'transparent';

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
