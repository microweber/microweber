//mw.require(mw.settings.libs_url + 'bootstrap-4.3.1' + '/js/popper.min.js');
//mw.require(mw.settings.libs_url + 'bootstrap-4.3.1' + '/js/bootstrap.min.js');
mw.require('tree.js');
mw.require('link-editor.js');
mw.require('tags.js');
mw.require(mw.settings.modules_url + '/categories/categories.js');


var _adm = {
    language: function(language) {
        if (typeof(mw.notification) != 'undefined') {
            mw.notification.success('Changing language...',10000);
        }
        $.post(mw.settings.api_url + "multilanguage/change_language", {locale: language, is_admin: 1})
            .done(function (data) {
                if (typeof(mw.notification) != 'undefined') {
                    mw.notification.success('Language changed! Reloading page...');
                }

                location.reload();
            });
    },
    editor: {
        set: function (frame) {
            mw.$(frame).width('100%');
            /*
              if (!!frame && frame !== null && !!frame.contentWindow) {
                  var width_mbar = mw.$('#main-bar').width(),
                      tree = document.querySelector('.tree-column'),
                      width_tbar = mw.$(tree).width(),
                      ww = mw.$(window).width();
                  if (tree.style.display === 'none') {
                      width_tbar = 0;
                  }
                  if (width_mbar > 200) {
                      width_mbar = 0;
                  }
                  mw.$(frame)
                      .width(ww - width_tbar - width_mbar - 35)
                      .height(frame.contentWindow.document.body.offsetHeight);
              }*/
        },
        init: function (area, params) {
            params = params || {};
            if (typeof params === 'object') {
                if (typeof params.src != 'undefined') {
                    delete(params.src);
                }
            }
            params.live_edit=false;
            params = typeof params === 'object' ? json2url(params) : params;
            area = mw.$(area);
            var frame = document.createElement('iframe');
            frame.src = mw.external_tool('wysiwyg?' + params);
            console.log(mw.external_tool('wysiwyg?' + params))
            frame.className = 'mw-iframe-editor';
            frame.scrolling = 'no';
            var name = 'mweditor' + mw.random();
            frame.id = name;
            frame.name = name;
            frame.style.backgroundColor = "transparent";
            frame.setAttribute('frameborder', 0);
            frame.setAttribute('allowtransparency', 'true');
            area.empty().append(frame);
            mw.$(frame).load(function () {
                frame.contentWindow.thisframe = frame;
                if (typeof frame.contentWindow.PrepareEditor === 'function') {
                    frame.contentWindow.PrepareEditor();
                }
                mw.admin.editor.set(frame);
                mw.$(frame.contentWindow.document.body).bind('keyup paste', function () {
                    mw.admin.editor.set(frame);
                });
            });
            mw.admin.editor.set(frame);
            mw.$(window).bind('resize', function () {
                mw.admin.editor.set(frame);
            });
            return frame;
        }
    },
    manageToolbarQuickNav: null,
    insertModule: function (module) {
        document.querySelector('.mw-iframe-editor').contentWindow.mw.insertModule(module);
    },


    simpleRotator: function (rotator) {
        if (rotator === null) {
            return undefined;
        }
        if (typeof rotator !== 'undefined') {
            if (!$(rotator).hasClass('activated')) {
                mw.$(rotator).addClass('activated')
                var all = rotator.children;
                var l = all.length;
                mw.$(all).addClass('mw-simple-rotator-item');

                rotator.go = function (where, callback, method) {
                    method = method || 'animate';
                    mw.$(rotator).dataset('state', where);
                    mw.$(rotator.children).hide().eq(where).show()
                    if (typeof callback === 'function') {
                        callback.call(rotator);
                    }

                    if (rotator.ongoes.length > 0) {
                        var l = rotator.ongoes.length;
                        i = 0;
                        for (; i < l; i++) {
                            rotator.ongoes[i].call(rotator);
                        }
                    }
                };
                rotator.ongoes = [];
                rotator.ongo = function (c) {
                    if (typeof c === 'function') {
                        rotator.ongoes.push(c);
                    }
                };
            }
        }
        return rotator;
    },

    postImageUploader: function () {
        if (document.querySelector('#images-manager') === null) {
            return false;
        }
        if (document.querySelector('.mw-iframe-editor') === null) {
            return false;
        }
        if (document.querySelector('.mw-iframe-editor').contentWindow.document.querySelector('.edit') === null) {
            return false;
        }
        var uploader = mw.uploader({
            filetypes: "images",
            multiple: true,
            element: "#insert-image-uploader"
        });
        mw.$(uploader).bind("FileUploaded", function (obj, data) {
            var frameWindow = document.querySelector('.mw-iframe-editor').contentWindow;
            var hasRanges = frameWindow.getSelection().rangeCount > 0;
            var img = '<img class="element" src="' + data.src + '" />';
            if (hasRanges && frameWindow.mw.wysiwyg.isSelectionEditable()) {
                frameWindow.mw.wysiwyg.insert_html(img);
            }
            else {
                frameWindow.mw.$(frameWindow.document.querySelector('.edit')).append(img);
            }
        });

    },
    listPostGalleries: function () {
        if (document.querySelector('#images-manager') === null) {
            return false;
        }
        if (document.querySelector('.mw-iframe-editor') === null) {
            return false;
        }
        if (document.querySelector('.mw-iframe-editor').contentWindow.document.querySelector('.edit') === null) {
            return false;
        }
    },


    beforeLeaveLocker: function () {
        var roots = '#pages_tree_toolbar, #main-bar',
            all = document.querySelectorAll(roots),
            l = all.length,
            i = 0;
        for (; i < l; i++) {
            if (!!all[i].MWbeforeLeaveLocker) continue;
            all[i].MWbeforeLeaveLocker = true;
            var links = all[i].querySelectorAll('a'), ll = links.length, li = 0;
            for (; li < ll; li++) {
                mw.$(links[li]).bind('mouseup', function (e) {
                    if (mw.askusertostay === true) {
                        e.preventDefault();
                        return false;

                    }
                });
            }
        }
    }
};

if(mw.admin) {
    Object.assign(_adm, mw.admin);
} else {
    mw.admin = _adm;
}

mw.admin.tree = function (target, opt, mode) {


    if(typeof target === "string") {
        target = document.querySelector(target);
    }
    if(!target) {
        return;
    }
    if(!mode) {
        mode = 'treeTags';
    }
    if(!opt) {
        opt = {};
    }

    var tree;

    var params = opt.params, options = opt.options;

    var url = mw.settings.api_url + 'content/get_admin_js_tree_json';
    var treeEl = document.createElement('div');
    treeEl.className = 'mw--global-admin-tree';


    if(!params) {
        params = {};
    }

    if(!options) {
        options = {};
    }


    var optionsDefaults;

    function _getTree() {
        return tree.tree || tree;
    }

    if(mode === 'tree') {
        optionsDefaults = {
            element: treeEl,
            sortable: false,
            selectable: true,
            singleSelect: true,
            searchInput: true
        };
    } else if(mode === 'treeTags') {
        var tags = mw.element();

        optionsDefaults = {
            selectable: true,
            multiPageSelect: false,
            tagsHolder: tags.get(0),
            treeHolder: treeEl,
            color: 'primary',
            size: 'sm',
            outline: true,
            saveState: false,
            searchInput: true,
            on: {
                selectionChange: function () {
                    mw.askusertostay = true;
                }
            }
        };

        target.appendChild(tags.get(0));
    }

    target.appendChild(treeEl);

    var _serialize = function(obj) {
        var str = [];
        for (var p in obj){
            if (obj.hasOwnProperty(p)) {
                str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
            }
        }
        return str.join("&");
    };

    var treeSettings = Object.assign({}, optionsDefaults, options);

    url = url + '?' + _serialize(params);

    return new Promise(function (resolve){
        $.get(url, function (data) {
            treeSettings.data = data;
            tree = new mw[mode](treeSettings);
            var res =  {
                tree: mode === 'tree' ? tree : tree.tree,
                tags: mode === 'tree' ? null : tree.tags,
                treeTags: mode === 'tree' ? null : tree
            };
            resolve(res);
        });
    });
};


mw.contactForm = function () {
    mw.top().dialogIframe({
        url: 'https://microweber.com/contact-frame/',
        overlay: true,
        height: 600
    })
};


$(mwd).ready(function () {


    mw.$(document.body).on('keydown', function (e) {
        if (mw.event.key(e, 8) && (e.target.nodeName === 'DIV' || e.target === document.body)) {
            if (!e.target.isContentEditable) {
                mw.event.cancel(e);
                return false;
            }
        }
    });

    mw.admin.beforeLeaveLocker();

    mw.$(document.body).on('click', '[data-href]', function(e){
        e.preventDefault();
        e.stopPropagation();
        var loc = $(this).attr('data-href');
        if (mw.askusertostay) {
            mw.confirm(mw.lang("Continue without saving") + '?', function () {
                mw.askusertostay = false;
                location.href = loc;
            });
        } else {
            location.href = loc;
        }
    });
});

$(mww).on('load', function () {
    mw.on.moduleReload('pages_tree_toolbar', function () {

    });



    if (document.getElementById('main-bar-user-menu-link') !== null) {

        mw.$(document.body).on('click', function (e) {
            if (e.target !== document.getElementById('main-bar-user-menu-link') && e.target.parentNode !== document.getElementById('main-bar-user-menu-link')) {
                mw.$('#main-bar-user-tip').removeClass('main-bar-user-tip-active');
            }
            else {

                mw.$('#main-bar-user-tip').toggleClass('main-bar-user-tip-active');
            }
        });
    }

    mw.on('adminSaveStart saveStart', function () {
        var btn = document.querySelector('#content-title-field-buttons .btn-save span');
        btn.innerHTML = mw.msg.saving + '...';
    });
    mw.on('adminSaveEnd saveEnd', function () {
         var btn = document.querySelector('#content-title-field-buttons .btn-save span');
        btn.innerHTML = mw.msg.save;
    });

    mw.$(".dr-item-table > table").click(function(){
        mw.$(this).toggleClass('active').next().stop().slideToggle().parents('.dr-item').toggleClass('active')
    });

});


QTABSArrow = function (el) {
    el = mw.$(el);
    if (el == null) {
        return;
    }
    if (!el.length) {
        return;
    }
    var left = el.offset().left - mw.$(document.getElementById('quick-add-post-options')).offset().left + (el[0].offsetWidth / 2) - 5;
    mw.$('#quick-add-post-options-items-holder .mw-tooltip-arrow').css({left: left});
};


;(function (){

    var self;
    var RtlDetect=self={_regexEscape:/([\.\*\+\^\$\[\]\\\(\)\|\{\}\,\-\:\?])/g,_regexParseLocale:/^([a-zA-Z]*)([_\-a-zA-Z]*)$/,_escapeRegExpPattern:function(str){if(typeof str!=='string'){return str}
            return str.replace(self._regexEscape,'\\$1')},_toLowerCase:function(str,reserveReturnValue){if(typeof str!=='string'){return reserveReturnValue&&str}
            return str.toLowerCase()},_toUpperCase:function(str,reserveReturnValue){if(typeof str!=='string'){return reserveReturnValue&&str}
            return str.toUpperCase()},_trim:function(str,delimiter,reserveReturnValue){var patterns=[];var regexp;var addPatterns=function(pattern){patterns.push('^'+pattern+'+|'+pattern+'+$')};if(typeof delimiter==='boolean'){reserveReturnValue=delimiter;delimiter=null}
            if(typeof str!=='string'){return reserveReturnValue&&str}
            if(Array.isArray(delimiter)){delimiter.map(function(item){var pattern=self._escapeRegExpPattern(item);addPatterns(pattern)})}
            if(typeof delimiter==='string'){var patternDelimiter=self._escapeRegExpPattern(delimiter);addPatterns(patternDelimiter)}
            if(!delimiter){addPatterns('\\s')}
            var pattern='('+patterns.join('|')+')';regexp=new RegExp(pattern,'g');while(str.match(regexp)){str=str.replace(regexp,'')}
            return str},_parseLocale:function(strLocale){var matches=self._regexParseLocale.exec(strLocale);var parsedLocale;var lang;var countryCode;if(!strLocale||!matches){return}
            matches[2]=self._trim(matches[2],['-','_']);lang=self._toLowerCase(matches[1]);countryCode=self._toUpperCase(matches[2])||countryCode;parsedLocale={lang:lang,countryCode:countryCode};return parsedLocale},isRtlLang:function(strLocale){var objLocale=self._parseLocale(strLocale);if(!objLocale){return}
            return(self._BIDI_RTL_LANGS.indexOf(objLocale.lang)>=0)},getLangDir:function(strLocale){return self.isRtlLang(strLocale)?'rtl':'ltr'}};Object.defineProperty(self,'_BIDI_RTL_LANGS',{value:['ae','ar','arc','bcc','bqi','ckb','dv','fa','glk','he','ku','mzn','nqo','pnb','ps','sd','ug','ur','yi'],writable:!1,enumerable:!0,configurable:!1})

    mw.admin.rtlDetect = RtlDetect;

})();
