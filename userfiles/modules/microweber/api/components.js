mw.components = {

    postSearch:function(el){
        var defaults = { keyword:el.value, limit:4 };

        el._setValue = function(id){
            mw.tools.ajaxSearch(this._settings, function(){
                
            })
        }

        var el = $(el);


        var options =  JSON.parse(el.attr("data-options") || '{}');

        settings = $.extend({}, defaults, options);

        el[0]._settings = settings;

        el.wrap("<div class='mw-component-post-search'></div>");
        el.after("<ul></ul>");

        el.on("input focus blur", function(event){

            if(!el[0].is_searching){
                var val = el.val();
                if(event.type=='blur'){
                    $(this).next('ul').hide();
                    return false;
                }
                if(event.type=='focus'){
                    if($(this).next('ul').html()){
                        $(this).next('ul').show()
                    }
                    return false;
                }
                el[0].is_searching = true;

                this._settings.keyword = this.value;
                $('ul', el).empty("")
                el.parent().addClass("loading");
                mw.tools.ajaxSearch(this._settings, function(){
                    var lis = [];
                    var json = this;
                    for(var item in json){
                        var obj = json[item];
                        if(typeof obj === 'object'){
                            var li = document.createElement("li");
                            li._value = obj;
                            li.innerHTML = obj.title;
                            $(li).on("mousedown touchstart", function(){
                                el.val(this._value.title);

                                el[0]._value = li._value;

                                el.trigger('postSelected', [li._value]);
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
    init:function(){
        mw.$('[data-mwcomponent]').each(function(){
            var component = $(this).attr("data-mwcomponent");
            if(mw.components[component]){
                mw.components[component](this);
                $(this).removeAttr('data-mwcomponent')
            }
        });
    }
}

$(document).ready(function(){
    mw.components.init();
});

$(window).on('load', function(){
    mw.components.init();
});

$(window).on('ajaxStop', function(){
    setTimeout(function(){
        mw.components.init();
    }, 100);
});