
$(document).ready(function(){
  mw.iconSelector.addCSS('link[href*="/iconsmind.css"]', '.icon-')
})

mw.iconSelector = mw.iconSelector || {
    _string: '',
    _activeElement: null,

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
       mw.iconSelector.popup(true);
    },

    init: function () {
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

                var l = icons.length, i = 0, html = '';
                for (; i < l; i++) {
                    var sel = icons[i].selectorText;
                    if (!!sel && sel.indexOf('.mw-icon-') === 0) {
                        var cls = sel.replace(".", '').split(':')[0];
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

    popup: function (refresh) {
      

        if (mw.iconSelector.iconFontClasses.length == 0) {
            mw.iconSelector.init();
        }

        if (mw.iconSelector.iconFontClasses.length == 0) {
            // if no icon sets, disable the icon editor
            return;
        }


        if (mw.iconSelector._string == '' || refresh) {


            var uicss = mw.iconSelector.iconFontClasses;
            var l = uicss.length, i = 0, html = '';
            for (; i < l; i++) {
                var sel = uicss[i];
                html += '<li onclick="mw.iconSelector.select(\'' + sel + '\')" title="' + sel + '"><i class="' + sel + '"></i></li>';

            }



            var theOptions = ''
                + '<span class="mw-ui-btn" id="icon-color-pick">Color</span>'
                + '<br><br>'
                + '<label class="mw-ui-label" style="padding-bottom:20px;font-size:11px">Size</label>'
                + '<input class="mw-icon-selector-set-icon-size" type="range" name="mw-icon-selector-set-icon-size"  min="10" max="120" onchange="mw.iconSelector.set_icon_size(this.value)"  />';




            mw.iconSelector.getMaterialIconsPopup(function(){
              mw.iconSelector._string = html + this;
             mw.iconSelector._string = '<ul class="mw-icon-selector">' + mw.iconSelector._string + '</ul>';

               mw.iconSelector._string =
                '<div class="mw-ui-btn-nav mw-ui-btn-nav-tabs live-edit-icon-pick-menu"><span class="mw-ui-btn">Icons</span><span class="mw-ui-btn">Options</span></div>'
                + '<div class="mw-ui-box mw-ui-box-content live-edit-icon-pick-tab" style="width:300px">' + mw.iconSelector._string + '</div>'
                + '<div class="mw-ui-box mw-ui-box-content live-edit-icon-pick-tab" style="width:300px">' + theOptions + '</div>'
                ;

              mw.iconSelectorToolTip = mw.tooltip({
                  content: mw.iconSelector._string,
                  element: mw.iconSelector._activeElement,
                  position: 'bottom-center'
              });

              $(mw.iconSelectorToolTip).addClass('tooltip-icon-picker')

              $('.mw-icon-selector', mw.iconSelectorToolTip).show();
              var tabs = mw.tabs({
                  nav:'.live-edit-icon-pick-menu .mw-ui-btn',
                  tabs:'.live-edit-icon-pick-tab'
              })

              tabs.set(0)

              mw.colorPicker({
                  element:'#icon-color-pick',
                  position:'bottom-center',
                  onchange:function(color){
                      $(mw.iconSelector._activeElement).css("color", color);
                  }
              });
              mw.iconSelector.searchInit();

            })
        }
        else {
            $(mw.iconSelectorToolTip).show();
            $('.mw-icon-selector', mw.iconSelectorToolTip).show();


            mw.tools.tooltip.setPosition(mw.iconSelectorToolTip, mw.iconSelector._activeElement, 'bottom-center');
        }
        var icons_size_val = $(mw.iconSelector._activeElement).css("fontSize");
        var a = parseInt(icons_size_val);

        if (a > 0) {
            $('.mw-icon-selector-set-icon-size').val(a);
        }



    },
    _exceptions:['fa-lg', 'fa-2x', 'fa-3x', 'fa-4x', 'fa-5x', 'fa-fw', 'fa-spin', 'fa-pule', 'fa-rotate-90', 'fa-rotate-180', 'fa-rotate-270', 'fa-flip-horizontal', 'fa-flip-vertical'],
    select: function (icon, is) {
        if (mw.iconSelector._activeElement !== null && typeof mw.iconSelector._activeElement !== 'undefined') {
            mw.tools.removeClass(mw.iconSelector._activeElement, mw.iconSelector.iconFontClasses);
            mw.wysiwyg.elementRemoveFontIconClasses(mw.iconSelector._activeElement);
            mw.tools.classNamespaceDelete(mw.iconSelector._activeElement, 'mw-icon-');
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
              mw.$(mw.iconSelector._activeElement).empty()
            }

            if(typeof(mw.iconSelector._activeElement) != 'undefined' && typeof(mw.iconSelector._activeElement.nodeName) != 'undefined'){
                if(mw.iconSelector._activeElement.nodeName == "INPUT"){
                    $(mw.iconSelector._activeElement).val(icon).trigger( "change");
                }
            }

        }
        $(mw.tools.firstParentWithClass(mw.iconSelector._activeElement, 'edit')).addClass('changed');
        mw.iconSelector._activeElement = null;


        $(mw.iconSelectorToolTip).hide();
    },
    hide: function () {
        if (mw.iconSelector._string != '') {
            $(mw.iconSelectorToolTip).hide();
        }
    },
    search:function(val){
      val = val.toLowerCase();
      var final = [];
      $.each(mw.iconSelector.iconFontClasses, function(){
        if(this.indexOf(val) !== -1){
          final.push(final)
        }
      })
      $.each(mw.materialIcons, function(){
        if(this.indexOf(val) !== -1){
          final.push(final)
        }
      });
      return final;
    },
    searchInit:function(el){
      if(!!mw.iconSelector.searchelement) return;
      mw.iconSelector.searchelement = document.createElement('input');
      mw.iconSelector.searchelement.className = 'mw-ui-searchfield icon-picker-search';
      mw.iconSelector.searchelement.__time = null;
      mw.$('.' + mw.iconSelector._exceptions.join(', .'), mw.iconSelectorToolTip).remove()
      $(mw.iconSelector.searchelement).on('input keyup paste', function(){
        clearTimeout(mw.iconSelector.searchelement.__time);
        mw.iconSelector.searchelement.__time = setTimeout(function(){
          var val = mw.iconSelector.searchelement.value.toLowerCase();
          if(!val){
            $(".live-edit-icon-pick-tab li").show();
            return;
          }
          var results = mw.iconSelector.search(mw.iconSelector.searchelement.value);
          $(".live-edit-icon-pick-tab li").hide().each(function(){
            if(this.title.indexOf(val) !== -1){
              $(this).show();
            }
          })
        }, 100)
      })
      $(mw.iconSelector.searchelement).appendTo('.tooltip-icon-picker')
    },
    iconDropdown:function(selector, options){
        var el = $(selector)[0];
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
          html = '<ul class="mw-icon-selector mw-icon-selector-dropdown" style="position:'+options.mode+';width:100%; left:0;top:100%;">' + html + this + '</ul>';
          $(selector).addClass('mw-icon-selector-dropdown-wrapper').append(holder).append(html)
          $('li', el).on('mousedown touchstart', function(){
            var val =  $(this).html()
            $('.mw-ui-field-icon', holder).html(val);
             if(typeof options.onchange == 'function'){
                 options.onchange.call(undefined, val, el)
             }
          });
        })


        $(input).on('focus', function(){
            $(this).parent().parent().addClass('focused')
        });
         $(input).on('input change', function(){
             var val = $.trim(this.value);
             clearTimeout(input.__time);
             (function(val, el){
                 input.__time = setTimeout(function(){
                        if(!val){
                            $('.mw-icon-selector li', el).show()
                        }
                        else{
                            $('.mw-icon-selector li', el).hide().filter('[data-value*="'+val+'"]').show()
                        }
                        if(typeof options.onchange == 'function'){
                             //options.onchange.call(undefined, input.value, el)
                        }
                 }, 300);
             })(val, el);

        });
        el.__time = null;




        $(input).on('blur', function(){
            (function(el){
                clearTimeout(el.__time)
                el.__time = setTimeout(function(){
                    $(el).parent().parent().removeClass('focused')
                }, 200)
            })(this)
        });



    },
    getMaterialIconsPopup:function(callback){

      mw.require('https://fonts.googleapis.com/icon?family=Material+Icons&.css')
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
      mw.require('https://fonts.googleapis.com/icon?family=Material+Icons&.css')
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
            $(mw.iconSelector._activeElement).css("fontSize", a + "px");
        } else {
            $(mw.iconSelector._activeElement).css("fontSize", "inherit");
        }


    }
}