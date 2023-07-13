    mw.components = {
    _rangeOnce: false,
    'range': function(el){
        mw.lib.require('jqueryui');
        var options = this._options(el);
        var defaults = {
            range: 'min',
            animate: "fast"
        };
        var ex = {}, render = el;
        if(el.nodeName === 'INPUT'){
            el._pauseChange = false;
            el.type = 'text';
            render = document.createElement('div');
            $(el).removeClass('mw-range');
            $(render).addClass('mw-range');
            $(el).after(render);
            ex = {
                slide: function( event, ui ) {
                    el._pauseChange = true;
                   $(el).val(ui.value).trigger('change').trigger('input');
                    setTimeout(function () {
                        el._pauseChange = false;
                    }, 78);
                }
            };

        }
        var settings = $.extend({}, defaults, options, ex);
        if(el.min){
            settings.min = parseFloat(el.min);
        }
        if(el.max){
            settings.max = parseFloat(el.max);
        }
        if(el.value){
            settings.value = parseFloat(el.value);
        }
        mw.$(render)
            .slider(settings)
            .on('mousedown touchstart', function(){
                mw.$(this).addClass('active');
            });
        $(el).on('input', function(){
            mw.$(render).slider( "value", this.value );
        });
        if(!mw.components._rangeOnce) {
            mw.components._rangeOnce = true;
            mw.$(document.body).on('mouseup touchend', function(){
                mw.$('.mw-range.active').removeClass('active');
            });
        }
    },
    'color-picker': function(el){
        var options = this._options(el);
        var defaults = {
            position: 'bottom-center'
        };
        var settings = $.extend({}, defaults, options);
        var nav = document.createElement('div');
        nav.className = 'mw-ui-btn-nav mw-color-picker-holder';
        var view = document.createElement('div');
        view.className = 'mw-ui-btn';
        view.innerHTML = '<span class="mw-ui-btn-img"></span>';
        nav.appendChild(view);
        var inputEl;
        if(el.nodeName === 'INPUT'){
            inputEl = el;
            mw.$(el).addClass('mw-ui-field').after(nav);
            nav.appendChild(el);
            mw.$('.mw-ui-btn-img', view).css("background-color", el.value);
            inputEl._time = null;
        }

        
        var picker = mw.colorPicker({
            element:inputEl,
            position:settings.position,
            onchange:function(color){
                mw.$('.mw-ui-btn-img', view).css("background-color", color);
                mw.$(inputEl).trigger('change');
            }
        });
        mw.$(view).on("click", function(){
            setTimeout(function(){
                picker.toggle();
            }, 10);
        });
    },
    'file-uploader':function(el){
        var options = this._options(el);
        var defaults = {
            element: el
        };
        var settings = $.extend({}, defaults, options);
        var ch = mw.$(el).attr("onchange");

        mw.fileWindow({
            types:'media',
            change:function(url){
                try{
                    eval(ch);
                }
                catch(err){}
            }
        });
    },
    'modules-tabs':function(el){
        var options = this._options(el);
        options.breakPoint = 100; //makes accordion if less then 100px
        if(window.live_edit_sidebar) {
            mw.$(el).addClass('mw-accordion-window-height')
            options.breakPoint = 800; //makes accordion if less then 800px
        }
        var accordion = this.accordion(el);
        var tb = new mw.tabAccordion(options, accordion);
    },
    'tab-accordion':function(el){
       var options = this._options(el);
       var accordion = this.accordion(el);
       var tb = new mw.tabAccordion(options, accordion);
    },
    accordion:function(el){
        if(!el || el._accordion) return;
        if(!$(el).is(':visible')){
            setTimeout(function(){
                mw.components.accordion(el);
            }, 777, el);
            return;
        }
        el._accordion = true;
        var options = this._options(el);
        var settings = $.extend(options, {element:el})
        var accordion = new mw.uiAccordion(settings);
        if($(el).hasClass('mw-accordion-window-height')){
            accordion._setHeight = function(){
                var max =  mw.$(window).height() - (accordion.root.offset().top - mw.$(window).scrollTop());
                accordion.root.css('height', max);
                var content_max = max - (accordion.titles.length * accordion.titles.eq(0).outerHeight());
                accordion.contents.css('height', content_max);
            };
            accordion._setHeight();
            mw.$(window).on('load resize', function(){
                accordion._setHeight();
            });
            if(window !== top){
                mw.$(top).on('load resize', function(){
                    accordion._setHeight();
                });
            }
        }
        if($(el).hasClass('mw-accordion-full-height')){
            accordion._setHeight = function(){
                var max = Math.min($(el).parent().height(), mw.$(window).height());
                accordion.root.css('maxHeight', max);
                var content_max = max - (accordion.titles.length * accordion.titles.eq(0).outerHeight());
                accordion.contents.css('maxHeight', content_max);
            };
            accordion._setHeight();
            mw.$(window).on('load resize', function(){
                accordion._setHeight();
            });
            if(window !== top){
                mw.$(top).on('load resize', function(){
                    accordion._setHeight();
                });
            }
        }
        return accordion;
    },
    postSearch: function (el) {
        var defaults = {keyword: el.value, limit: 4};
        el._setValue = function (id) {
            mw.tools.ajaxSearch(this._settings, function () {

            });
        };

        el = mw.$(el);
        var options = JSON.parse(el.attr("data-options") || '{}');
        settings = $.extend({}, defaults, options);
        el[0]._settings = settings;

        el.wrap("<div class='mw-component-post-search'></div>");
        el.after("<ul></ul>");

        el.on("input focus blur", function (event) {

            if (!el[0].is_searching) {
                var val = el.val();
                if (event.type == 'blur') {
                    mw.$(this).next('ul').hide();
                    return false;
                }
                if (event.type == 'focus') {
                    if ($(this).next('ul').html()) {
                        mw.$(this).next('ul').show()
                    }
                    return false;
                }
                el[0].is_searching = true;

                this._settings.keyword = this.value;
                mw.$('ul', el).empty("")
                el.parent().addClass("loading");
                mw.tools.ajaxSearch(this._settings, function () {
                    var lis = [];
                    var json = this;
                    for (var item in json) {
                        var obj = json[item];
                        if (typeof obj === 'object') {
                            var li = document.createElement("li");
                            li._value = obj;
                            li.innerHTML = obj.title;
                            mw.$(li).on("mousedown touchstart", function () {
                                el.val(this._value.title);

                                el[0]._value = this._value;

                                el.trigger('postSelected', [this._value]);
                                mw.$(this.parentNode).hide()
                            })

                            lis.push(li);
                        }
                    }
                    el.parent().removeClass("loading");
                    var ul = el.parent().find("ul");
                    ul.empty().append(lis).show();
                    el[0].is_searching = false;
                });
            }
        });
        el.trigger("postSearchReady");
    },
    _options: function (el) {
        return mw.tools.elementOptions(el);
    },
    _init: function () {
        mw.$('.mw-field input[type="range"]').addClass('mw-range');
        mw.$('[data-mwcomponent], [data-mw-component]').each(function () {
            var component = this.dataset.mwComponent || this.dataset.mwcomponent;
            if (mw.components[component]) {
                mw.components[component](this);
                mw.$(this).removeAttr('data-mwcomponent').removeAttr('data-mw-component')
            }
        });
        $.each(this, function(key){
            if(key.indexOf('_') === -1){
                mw.$('.mw-'+key+', mw-'+key).not(".mw-component-ready").each(function () {
                    mw.$(this).addClass('mw-component-ready');
                    mw.components[key](this);
                });
            }
        });
    }
};

$(document).ready(function () {
    mw.components._init();
});

$(window).on('load', function () {
    mw.components._init();
});



$(window).on('ajaxStop', function () {
    setTimeout(function () {
        mw.components._init();
    }, 100);
});

mw.registerComponent = function(name, func){
    if(mw.components[name]){
        console.warn('Component ' + name + ' already exists.');
        return;
    }
    mw.components[name] = func;
};
