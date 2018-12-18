mw.components = {
    'color-picker': function(el){
        var nav = document.createElement('div');
        nav.className = 'mw-ui-btn-nav mw-color-picker-holder';
        var view = document.createElement('div');
        view.className = 'mw-ui-btn';
        view.innerHTML = '<span class="mw-ui-btn-img"></span>';
        nav.appendChild(view);
        var inputEl;
        if(el.nodeName === 'INPUT'){
            inputEl = el;
            $(el).addClass('mw-ui-field').after(nav);
            nav.appendChild(el);
            $('.mw-ui-btn-img', view).css("background-color", el.value);
        }

        inputEl._time = null;
        var picker = mw.colorPicker({
            element:inputEl,
            position:'bottom-center',
            onchange:function(color){
                $('.mw-ui-btn-img', view).css("background-color", color);
                $(inputEl).trigger('change');
            }
        });
        $(view).on("click", function(){
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
        var ch = $(el).attr("onchange");

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
            $(el).addClass('mw-accordion-window-height')
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
        var options = this._options(el);
        var settings = $.extend(options, {element:el})
        var accordion = new mw.uiAccordion(settings);
        if($(el).hasClass('mw-accordion-window-height')){
            accordion._setHeight = function(){
                var max =  $(window).height() - accordion.root.offset().top;
                accordion.root.css('height', max);
                var content_max = max - (accordion.titles.length * accordion.titles.eq(0).outerHeight());
                accordion.contents.css('height', content_max);
            };
            accordion._setHeight();
            $(window).on('load resize', function(){
                accordion._setHeight();
            });
            if(window !== top){
                $(top).on('load resize', function(){
                    accordion._setHeight();
                });
            }
        }
        if($(el).hasClass('mw-accordion-full-height')){
            accordion._setHeight = function(){
                var max = Math.min($(el).height(), $(window).height());
                accordion.root.css('maxHeight', max);
                var content_max = max - (accordion.titles.length * accordion.titles.eq(0).outerHeight());
                accordion.contents.css('maxHeight', content_max);
            }
            accordion._setHeight();
            $(window).on('load resize', function(){
                accordion._setHeight();
            });
            if(window !== top){
                $(top).on('load resize', function(){
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
        }

        var el = $(el);

        var options = JSON.parse(el.attr("data-options") || '{}');

        settings = $.extend({}, defaults, options);

        el[0]._settings = settings;

        el.wrap("<div class='mw-component-post-search'></div>");
        el.after("<ul></ul>");

        el.on("input focus blur", function (event) {

            if (!el[0].is_searching) {
                var val = el.val();
                if (event.type == 'blur') {
                    $(this).next('ul').hide();
                    return false;
                }
                if (event.type == 'focus') {
                    if ($(this).next('ul').html()) {
                        $(this).next('ul').show()
                    }
                    return false;
                }
                el[0].is_searching = true;

                this._settings.keyword = this.value;
                $('ul', el).empty("")
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
                            $(li).on("mousedown touchstart", function () {
                                el.val(this._value.title);

                                el[0]._value = this._value;

                                el.trigger('postSelected', [this._value]);
                                $(this.parentNode).hide()
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
        el.trigger("postSearchReady")
    },
    _options: function (el) {
        var opt = ( el.dataset.options || '').trim().split(','), final = {};
        if(!opt[0]) return final;
        $.each(opt, function(){
            var arr = this.split(':');
            var val = arr[1].trim();
            if(!val){

            }
            else if(val === 'true' || val === 'false'){
                val = val === 'true';
            }
            else if(!/\D/.test(val)){
                val = parseInt(val, 10);
            }
            final[arr[0].trim()] = val;
        });
        return final;
    },
    _init: function () {
        mw.$('[data-mwcomponent]').each(function () {
            var component = $(this).attr("data-mwcomponent");
            if (mw.components[component]) {
                mw.components[component](this);
                $(this).removeAttr('data-mwcomponent')
            }
        });
        $.each(this, function(key){
            if(key.indexOf('_') === -1){
                mw.$('.mw-'+key+', '+key).not(".mw-component-ready").each(function () {
                    $(this).addClass('mw-component-ready');
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

mw.on('ComponentsLaunch', function () {
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
