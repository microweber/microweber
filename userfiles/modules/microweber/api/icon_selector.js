
mw.top()._icons = mw.top()._icons || [];


mw.lib.require('colorpicker');

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
                scope.search(this.value, list);
            };
            return nav;
        },
        _search: function (list, term) {
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
        search: function (term, list) {
            mw.$('.mw-icon-list-section', list).each(function () {
                icons.setRender(this._iconSet, this, 1, term) ;
            });
        },
        createPaging:function(perPage, length, page){
            page = page || 1;
            var max = 999;
            var pages = Math.min(Math.ceil(length/perPage), max);
            var paging = document.createElement('div');
            paging.className = 'mw-paging mw-paging-small';
            if(perPage >= length ) {
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
                $(paging).append(next)
            }
            if(active > 1) {
                var prev = document.createElement('a');
                prev.className = 'mw-paging-visible-range mw-paging-prev';
                prev.innerHTML = '&laquo;';
                prev._value = active-1;
                $(paging).prepend(prev);
            }

            return paging;
        },
        setRender: function(set, section, page, search){
            var perPage = 70;
            page = page || 1;
            search = (search || '').trim().toLowerCase();
            $(section).empty( );
            var off = perPage * (page - 1);
            var icons = set.icons;
            if(search) {
                icons = set.icons.filter(function (icon) {
                    return icon.toLowerCase().indexOf(search) !== -1;
                });
            }
            var to = off + Math.min(icons.length - off, perPage);
            for ( var i = off; i < to; i++ ) {
                var icon = document.createElement('i');
                var iconc = document.createElement('i');
                icon.className = 'mw-icon-list-icon mw-icon-list-' + set.name;
                icon._searchvalue = icons[i].toLowerCase();
                icon._value = [icons[i], set.render, icon];
                icon.appendChild(iconc);
                icon.onclick = function () {
                    $(section.parentNode).trigger('_$select', this._value);
                };
                set.render(icons[i], iconc);
                section.appendChild(icon);
            }
            var pg = this.createPaging(perPage, icons.length, page);
            section.appendChild(pg);
            var scope = this;
            mw.$('a', pg).on('click', function () {
                scope.setRender(set, section, this._value, search);
            });
        },
        list: function (options) {
            options = options || {};
            var defaults = {
                showAll: false
            };
            var settings = $.extend({}, defaults, options);
            var list = document.createElement('div');
            list.className = 'mw-icon-list';
            list.appendChild(this.searchGUI(list));
            var fragment = document.createDocumentFragment();

            var select;
            var containers = [];
            if (!settings.showAll) {
                var selectHolder = document.createElement('div');
                selectHolder.className = 'mw-ui-btn-nav';

                var prev = $('<span class="mw-ui-btn"><span class="mw-icon-arrowleft"></span></span>');
                var next = $('<span class="mw-ui-btn"><span class="mw-icon-arrowright"></span></span>');

                select = document.createElement('select');
                select.className = 'mw-ui-field';
                (function (containers) {
                    $(select).on('change', function () {
                        var curr = this.options[this.options.selectedIndex]._for;
                        $(containers).hide().filter(curr).show();
                    });
                })(containers);

                prev.on('click', function () {
                    select.selectedIndex = select.options.selectedIndex >= 1 ? select.options.selectedIndex - 1 : select.options.length - 1;
                    $(select).trigger('change')
                });
                next.on('click', function () {
                    select.selectedIndex = select.options.selectedIndex <  select.options.length - 1 ? select.options.selectedIndex + 1 : 0;
                    $(select).trigger('change')
                });

                $(selectHolder)
                    .append(prev)
                    .append(select)
                    .append(next);
            }



            var i, ic, ticons = mw.top()._icons;
            for( i = 0; i < ticons.length; i++ ){
                var set = ticons[i];
                var title = document.createElement('h5');
                var section = document.createElement('div');
                var wrapper = document.createElement('div');
                containers.push(wrapper);
                wrapper.className = 'mw-icon-list-section-wrapper';
                wrapper.style.display = i === 0 ? 'block' : 'none';
                section.className = 'mw-icon-list-section';
                section._iconSet = set;
                title.innerHTML = set.name;
                wrapper.appendChild(title);
                this.setRender(set, section);
                wrapper.appendChild(section);
                if (!settings.showAll) {
                    var option = document.createElement('option');
                    option.innerHTML = set.name;
                    option._for = wrapper;
                    select.appendChild(option);
                }

                fragment.appendChild(wrapper);
            }
            list.appendChild(selectHolder);
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
        faIcons5Init: function(){
            var fa5IconsArray = [];
            var exception = [
                "fa-xs", "fa-sm", "fa-lg", "fa-1x", "fa-2x", "fa-3x", "fa-4x", "fa-5x", "fa-6x", "fa-7x", "fa-8x", "fa-9x", "fa-10x",
                "fa-fw",
                "fa-ul > li", "fa-ul", "fa-li",
                "fa-rotate-90", "fa-rotate-180", "fa-rotate-270", "fa-flip-horizontal", "fa-flip-vertical", "fa-flip-both", "fa-flip-horizontal.fa-flip-vertical, .fa-flip-vertical", "fas fa-flip-horizontal.fa-flip-vertical", "fa-flip-horizontal.fa-flip-vertical",
                "fa-spin", "fa-pulse",
                "fa-pull-left", "fa-pull-right", "fa-border",
                "fa-stack", "fa-stack-1x", "fa-stack-2x", "fa-inverse", "fa-stack-1x, .fa-stack-2x",
                "fa-seedling",
                "fa-flip-both, .fa-flip-horizontal.fa-flip-vertical, .fa-flip-vertical",
                "fa-flip-both, .fa-flip-horizontal.fa-flip-vertical"
            ];
            //lists updated 01/27/20
            var regularList = [
                "fa-address-book",
                "fa-address-card",
                "fa-angry",
                "fa-arrow-alt-circle-down",
                "fa-arrow-alt-circle-left",
                "fa-arrow-alt-circle-right",
                "fa-arrow-alt-circle-up",
                "fa-bell",
                "fa-bell-slash",
                "fa-bookmark",
                "fa-building",
                "fa-calendar",
                "fa-calendar-alt",
                "fa-calendar-check",
                "fa-calendar-minus",
                "fa-calendar-plus",
                "fa-calendar-times",
                "fa-caret-square-down",
                "fa-caret-square-left",
                "fa-caret-square-right",
                "fa-caret-square-up",
                "fa-chart-bar",
                "fa-check-circle",
                "fa-check-square",
                "fa-circle",
                "fa-clipboard",
                "fa-clock",
                "fa-clone",
                "fa-closed-captioning",
                "fa-comment",
                "fa-comment-alt",
                "fa-comment-dots",
                "fa-comments",
                "fa-compass",
                "fa-copy",
                "fa-copyright",
                "fa-credit-card",
                "fa-dizzy",
                "fa-dot-circle",
                "fa-edit",
                "fa-envelope",
                "fa-envelope-open",
                "fa-eye",
                "fa-eye-slash",
                "fa-file",
                "fa-file-alt",
                "fa-file-archive",
                "fa-file-audio",
                "fa-file-code",
                "fa-file-excel",
                "fa-file-image",
                "fa-file-pdf",
                "fa-file-powerpoint",
                "fa-file-video",
                "fa-file-word",
                "fa-flag",
                "fa-flushed",
                "fa-folder",
                "fa-folder-open",
                "fa-frown",
                "fa-frown-open",
                "fa-futbol",
                "fa-gem",
                "fa-grimace",
                "fa-grin",
                "fa-grin-alt",
                "fa-grin-beam",
                "fa-grin-beam-sweat",
                "fa-grin-hearts",
                "fa-grin-squint",
                "fa-grin-squint-tears",
                "fa-grin-stars",
                "fa-grin-tears",
                "fa-grin-tongue",
                "fa-grin-tongue-squint",
                "fa-grin-tongue-wink",
                "fa-grin-wink",
                "fa-hand-lizard",
                "fa-hand-paper",
                "fa-hand-peace",
                "fa-hand-point-down",
                "fa-hand-point-left",
                "fa-hand-point-right",
                "fa-hand-point-up",
                "fa-hand-pointer",
                "fa-hand-rock",
                "fa-hand-scissors",
                "fa-hand-spock",
                "fa-handshake",
                "fa-hdd",
                "fa-heart",
                "fa-hospital",
                "fa-hourglass",
                "fa-id-badge",
                "fa-id-card",
                "fa-image",
                "fa-images",
                "fa-keyboard",
                "fa-kiss",
                "fa-kiss-beam",
                "fa-kiss-wink-heart",
                "fa-laugh",
                "fa-laugh-beam",
                "fa-laugh-squint",
                "fa-laugh-wink",
                "fa-lemon",
                "fa-life-ring",
                "fa-lightbulb",
                "fa-list-alt",
                "fa-map",
                "fa-meh",
                "fa-meh-blank",
                "fa-meh-rolling-eyes",
                "fa-minus-square",
                "fa-money-bill-alt",
                "fa-moon",
                "fa-newspaper",
                "fa-object-group",
                "fa-object-ungroup",
                "fa-paper-plane",
                "fa-pause-circle",
                "fa-play-circle",
                "fa-plus-square",
                "fa-question-circle",
                "fa-registered",
                "fa-sad-cry",
                "fa-sad-tear",
                "fa-save",
                "fa-share-square",
                "fa-smile",
                "fa-smile-beam",
                "fa-smile-wink",
                "fa-snowflake",
                "fa-square",
                "fa-star",
                "fa-star-half",
                "fa-sticky-note",
                "fa-stop-circle",
                "fa-sun",
                "fa-surprise",
                "fa-thumbs-down",
                "fa-thumbs-up",
                "fa-times-circle",
                "fa-tired",
                "fa-trash-alt",
                "fa-user",
                "fa-user-circle",
                "fa-window-close",
                "fa-window-maximize",
                "fa-window-minimize",
                "fa-window-restore"
            ]; //151 items
            var brandList = [
                "fa-500px",
                "fa-accessible-icon",
                "fa-accusoft",
                "fa-acquisitions-incorporated",
                "fa-adn",
                "fa-adobe",
                "fa-adversal",
                "fa-affiliatetheme",
                "fa-airbnb",
                "fa-algolia",
                "fa-alipay",
                "fa-amazon",
                "fa-amazon-pay",
                "fa-amilia",
                "fa-android",
                "fa-angellist",
                "fa-angrycreative",
                "fa-angular",
                "fa-app-store",
                "fa-app-store-ios",
                "fa-apper",
                "fa-apple",
                "fa-apple-pay",
                "fa-artstation",
                "fa-asymmetrik",
                "fa-atlassian",
                "fa-audible",
                "fa-autoprefixer",
                "fa-avianex",
                "fa-aviato",
                "fa-aws",
                "fa-bandcamp",
                "fa-battle-net",
                "fa-behance",
                "fa-behance-square",
                "fa-bimobject",
                "fa-bitbucket",
                "fa-bitcoin",
                "fa-bity",
                "fa-black-tie",
                "fa-blackberry",
                "fa-blogger",
                "fa-blogger-b",
                "fa-bluetooth",
                "fa-bluetooth-b",
                "fa-bootstrap",
                "fa-btc",
                "fa-buffer",
                "fa-buromobelexperte",
                "fa-buy-n-large",
                "fa-buysellads",
                "fa-canadian-maple-leaf",
                "fa-cc-amazon-pay",
                "fa-cc-amex",
                "fa-cc-apple-pay",
                "fa-cc-diners-club",
                "fa-cc-discover",
                "fa-cc-jcb",
                "fa-cc-mastercard",
                "fa-cc-paypal",
                "fa-cc-stripe",
                "fa-cc-visa",
                "fa-centercode",
                "fa-centos",
                "fa-chrome",
                "fa-chromecast",
                "fa-cloudscale",
                "fa-cloudsmith",
                "fa-cloudversify",
                "fa-codepen",
                "fa-codiepie",
                "fa-confluence",
                "fa-connectdevelop",
                "fa-contao",
                "fa-cotton-bureau",
                "fa-cpanel",
                "fa-creative-commons",
                "fa-creative-commons-by",
                "fa-creative-commons-nc",
                "fa-creative-commons-nc-eu",
                "fa-creative-commons-nc-jp",
                "fa-creative-commons-nd",
                "fa-creative-commons-pd",
                "fa-creative-commons-pd-alt",
                "fa-creative-commons-remix",
                "fa-creative-commons-sa",
                "fa-creative-commons-sampling",
                "fa-creative-commons-sampling-plus",
                "fa-creative-commons-share",
                "fa-creative-commons-zero",
                "fa-critical-role",
                "fa-css3",
                "fa-css3-alt",
                "fa-cuttlefish",
                "fa-d-and-d",
                "fa-d-and-d-beyond",
                "fa-dashcube",
                "fa-delicious",
                "fa-deploydog",
                "fa-deskpro",
                "fa-dev",
                "fa-deviantart",
                "fa-dhl",
                "fa-diaspora",
                "fa-digg",
                "fa-digital-ocean",
                "fa-discord",
                "fa-discourse",
                "fa-dochub",
                "fa-docker",
                "fa-draft2digital",
                "fa-dribbble",
                "fa-dribbble-square",
                "fa-dropbox",
                "fa-drupal",
                "fa-dyalog",
                "fa-earlybirds",
                "fa-ebay",
                "fa-edge",
                "fa-elementor",
                "fa-ello",
                "fa-ember",
                "fa-empire",
                "fa-envira",
                "fa-erlang",
                "fa-ethereum",
                "fa-etsy",
                "fa-evernote",
                "fa-expeditedssl",
                "fa-facebook",
                "fa-facebook-f",
                "fa-facebook-messenger",
                "fa-facebook-square",
                "fa-fantasy-flight-games",
                "fa-fedex",
                "fa-fedora",
                "fa-figma",
                "fa-firefox",
                "fa-firefox-browser",
                "fa-first-order",
                "fa-first-order-alt",
                "fa-firstdraft",
                "fa-flickr",
                "fa-flipboard",
                "fa-fly",
                "fa-font-awesome",
                "fa-font-awesome-alt",
                "fa-font-awesome-flag",
                "fa-fonticons",
                "fa-fonticons-fi",
                "fa-fort-awesome",
                "fa-fort-awesome-alt",
                "fa-forumbee",
                "fa-foursquare",
                "fa-free-code-camp",
                "fa-freebsd",
                "fa-fulcrum",
                "fa-galactic-republic",
                "fa-galactic-senate",
                "fa-get-pocket",
                "fa-gg",
                "fa-gg-circle",
                "fa-git",
                "fa-git-alt",
                "fa-git-square",
                "fa-github",
                "fa-github-alt",
                "fa-github-square",
                "fa-gitkraken",
                "fa-gitlab",
                "fa-gitter",
                "fa-glide",
                "fa-glide-g",
                "fa-gofore",
                "fa-goodreads",
                "fa-goodreads-g",
                "fa-google",
                "fa-google-drive",
                "fa-google-play",
                "fa-google-plus",
                "fa-google-plus-g",
                "fa-google-plus-square",
                "fa-google-wallet",
                "fa-gratipay",
                "fa-grav",
                "fa-gripfire",
                "fa-grunt",
                "fa-gulp",
                "fa-hacker-news",
                "fa-hacker-news-square",
                "fa-hackerrank",
                "fa-hips",
                "fa-hire-a-helper",
                "fa-hooli",
                "fa-hornbill",
                "fa-hotjar",
                "fa-houzz",
                "fa-html5",
                "fa-hubspot",
                "fa-ideal",
                "fa-imdb",
                "fa-instagram",
                "fa-intercom",
                "fa-internet-explorer",
                "fa-invision",
                "fa-ioxhost",
                "fa-itch-io",
                "fa-itunes",
                "fa-itunes-note",
                "fa-java",
                "fa-jedi-order",
                "fa-jenkins",
                "fa-jira",
                "fa-joget",
                "fa-joomla",
                "fa-js",
                "fa-js-square",
                "fa-jsfiddle",
                "fa-kaggle",
                "fa-keybase",
                "fa-keycdn",
                "fa-kickstarter",
                "fa-kickstarter-k",
                "fa-korvue",
                "fa-laravel",
                "fa-lastfm",
                "fa-lastfm-square",
                "fa-leanpub",
                "fa-less",
                "fa-line",
                "fa-linkedin",
                "fa-linkedin-in",
                "fa-linode",
                "fa-linux",
                "fa-lyft",
                "fa-magento",
                "fa-mailchimp",
                "fa-mandalorian",
                "fa-markdown",
                "fa-mastodon",
                "fa-maxcdn",
                "fa-mdb",
                "fa-medapps",
                "fa-medium",
                "fa-medium-m",
                "fa-medrt",
                "fa-meetup",
                "fa-megaport",
                "fa-mendeley",
                "fa-microblog",
                "fa-microsoft",
                "fa-mix",
                "fa-mixcloud",
                "fa-mizuni",
                "fa-modx",
                "fa-monero",
                "fa-napster",
                "fa-neos",
                "fa-nimblr",
                "fa-node",
                "fa-node-js",
                "fa-npm",
                "fa-ns8",
                "fa-nutritionix",
                "fa-odnoklassniki",
                "fa-odnoklassniki-square",
                "fa-old-republic",
                "fa-opencart",
                "fa-openid",
                "fa-opera",
                "fa-optin-monster",
                "fa-orcid",
                "fa-osi",
                "fa-page4",
                "fa-pagelines",
                "fa-palfed",
                "fa-patreon",
                "fa-paypal",
                "fa-penny-arcade",
                "fa-periscope",
                "fa-phabricator",
                "fa-phoenix-framework",
                "fa-phoenix-squadron",
                "fa-php",
                "fa-pied-piper",
                "fa-pied-piper-alt",
                "fa-pied-piper-hat",
                "fa-pied-piper-pp",
                "fa-pied-piper-square",
                "fa-pinterest",
                "fa-pinterest-p",
                "fa-pinterest-square",
                "fa-playstation",
                "fa-product-hunt",
                "fa-pushed",
                "fa-python",
                "fa-qq",
                "fa-quinscape",
                "fa-quora",
                "fa-r-project",
                "fa-raspberry-pi",
                "fa-ravelry",
                "fa-react",
                "fa-reacteurope",
                "fa-readme",
                "fa-rebel",
                "fa-red-river",
                "fa-reddit",
                "fa-reddit-alien",
                "fa-reddit-square",
                "fa-redhat",
                "fa-renren",
                "fa-replyd",
                "fa-researchgate",
                "fa-resolving",
                "fa-rev",
                "fa-rocketchat",
                "fa-rockrms",
                "fa-safari",
                "fa-salesforce",
                "fa-sass",
                "fa-schlix",
                "fa-scribd",
                "fa-searchengin",
                "fa-sellcast",
                "fa-sellsy",
                "fa-servicestack",
                "fa-shirtsinbulk",
                "fa-shopware",
                "fa-simplybuilt",
                "fa-sistrix",
                "fa-sith",
                "fa-sketch",
                "fa-skyatlas",
                "fa-skype",
                "fa-slack",
                "fa-slack-hash",
                "fa-slideshare",
                "fa-snapchat",
                "fa-snapchat-ghost",
                "fa-snapchat-square",
                "fa-soundcloud",
                "fa-sourcetree",
                "fa-speakap",
                "fa-speaker-deck",
                "fa-spotify",
                "fa-squarespace",
                "fa-stack-exchange",
                "fa-stack-overflow",
                "fa-stackpath",
                "fa-staylinked",
                "fa-steam",
                "fa-steam-square",
                "fa-steam-symbol",
                "fa-sticker-mule",
                "fa-strava",
                "fa-stripe",
                "fa-stripe-s",
                "fa-studiovinari",
                "fa-stumbleupon",
                "fa-stumbleupon-circle",
                "fa-superpowers",
                "fa-supple",
                "fa-suse",
                "fa-swift",
                "fa-symfony",
                "fa-teamspeak",
                "fa-telegram",
                "fa-telegram-plane",
                "fa-tencent-weibo",
                "fa-the-red-yeti",
                "fa-themeco",
                "fa-themeisle",
                "fa-think-peaks",
                "fa-trade-federation",
                "fa-trello",
                "fa-tripadvisor",
                "fa-tumblr",
                "fa-tumblr-square",
                "fa-twitch",
                "fa-twitter",
                "fa-twitter-square",
                "fa-typo3",
                "fa-uber",
                "fa-ubuntu",
                "fa-uikit",
                "fa-umbraco",
                "fa-uniregistry",
                "fa-unity",
                "fa-untappd",
                "fa-ups",
                "fa-usb",
                "fa-usps",
                "fa-ussunnah",
                "fa-vaadin",
                "fa-viacoin",
                "fa-viadeo",
                "fa-viadeo-square",
                "fa-viber",
                "fa-vimeo",
                "fa-vimeo-square",
                "fa-vimeo-v",
                "fa-vine",
                "fa-vk",
                "fa-vnv",
                "fa-vuejs",
                "fa-waze",
                "fa-weebly",
                "fa-weibo",
                "fa-weixin",
                "fa-whatsapp",
                "fa-whatsapp-square",
                "fa-whmcs",
                "fa-wikipedia-w",
                "fa-windows",
                "fa-wix",
                "fa-wizards-of-the-coast",
                "fa-wolf-pack-battalion",
                "fa-wordpress",
                "fa-wordpress-simple",
                "fa-wpbeginner",
                "fa-wpexplorer",
                "fa-wpforms",
                "fa-wpressr",
                "fa-xbox",
                "fa-xing",
                "fa-xing-square",
                "fa-y-combinator",
                "fa-yahoo",
                "fa-yammer",
                "fa-yandex",
                "fa-yandex-international",
                "fa-yarn",
                "fa-yelp",
                "fa-yoast",
                "fa-youtube",
                "fa-youtube-square",
                "fa-zhihu"
            ]; //438 items
            var fa5icons = mwd.querySelector('link[href*="/all.min.css"]');
            if (!fa5icons) {
                fa5icons = mwd.querySelector('link[href*="/all.css"]');
            }
            if (fa5icons && fa5icons.sheet) {
                try {
                    var icons = fa5icons.sheet.cssRules;
                    var l = icons.length, i = 0, cls;
                    for (; i < l; i++) {
                        var sel = icons[i].selectorText;
                        if (!!sel && sel.indexOf('.fa-') === 0) {
                            cls = sel.replace(".", '').split(':')[0];
                            if(exception.indexOf(cls) === -1) {
                                if(brandList.indexOf(cls) !== -1){
                                    fa5IconsArray.push("fab " + cls);
                                }else{
                                    fa5IconsArray.push("fas " + cls);
                                    if(regularList.indexOf(cls) !== -1){
                                        fa5IconsArray.push("far " + cls);
                                    }
                                }
                            }

                        }
                    }
                } catch (e) {
                }
            }
            if(fa5IconsArray.length) {
                this.addFontIcons({
                    icons: fa5IconsArray,
                    name: 'Font Awesome 5 Free',
                    remove: function(target) {
                        mw.tools.classNamespaceDelete(target, 'fa-');
                        mw.tools.removeClass(target, 'fas');
                        mw.tools.removeClass(target, 'fab');
                        mw.tools.removeClass(target, 'far');
                    },
                    render: function(icon, target) {
                        mw.$(target).addClass( icon );
                    }
                });
            }
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
            var mindIconsSolid = [];
            var mindLink = mwd.querySelector('link[href*="/mw-icons-mind/line/"]');
            var mindLinkSolid = mwd.querySelector('link[href*="/mw-icons-mind/solid/"]');
            if (mindLink != null && mindLink.sheet) {
                try {
                    var icons = mindLink.sheet.cssRules;
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
            if (mindLinkSolid != null && mindLinkSolid.sheet) {
                try {
                    var icons = mindLinkSolid.sheet.cssRules;
                    var l = icons.length, i = 0;
                    for (; i < l; i++) {
                        var sel = icons[i].selectorText;
                        if (!!sel && sel.indexOf('.mw-micon-solid-') === 0) {
                            var cls = sel.replace(".", '').split(':')[0];
                            mindIconsSolid.push('mw-micon- ' + cls);
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
            if(mindIconsSolid.length) {
                this.addFontIcons({
                    icons: mindIconsSolid,
                    name: 'Icons Mind Solid',
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
                icons.faIcons5Init();
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

   });
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

            tabs.set(0);

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
            var $el = $(mw.iconSelectorGUI);

            if(($el.offset().top + $el.height()) > Math.max(document.body.scrollHeight, document.documentElement.scrollHeight)){
                mw.iconSelectorGUI.scrollIntoView()
            }
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
                    res.render(res.icon, btn.firstChild);
                    options.onchange.call(undefined, elVal.outerHTML, el)
                }
                dialog.remove();
            });
        };
        $el.empty().append(btn);
        return {
            value: function (val) {
                mw.$(btn.firstChild).replaceWith(val);
            }
        }
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
$(window).on('load', function () {
    mw.iconSelector.init();
});
