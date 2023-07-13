
mw.require('options.js')
mw.require('liveedit.css')


mw.liveEditSaveService = {
     grammarlyFix:function(data){

        mw.$("grammarly-btn", data).remove();
        mw.$("grammarly-card", data).remove();
        mw.$("g.gr_", data).each(function(){
            mw.$(this).replaceWith(this.innerHTML);
        });
        mw.$("[data-gramm_id]", data).removeAttr('data-gramm_id');
        mw.$("[data-gramm]", data).removeAttr('data-gramm');
        mw.$("[data-gramm_id]", data).removeAttr('data-gramm_id');
        mw.$("grammarly-card", data).remove();
        mw.$("grammarly-inline-cards", data).remove();
        mw.$("grammarly-popups", data).remove();
        mw.$("grammarly-extension", data).remove();
        return data;
    },
    saving: false,
    coreSave: function(data) {
        if (!data) return false;
        $.each(data, function(){
            var body = mw.tools.parseHtml(this.html).body;
            mw.liveEditSaveService.grammarlyFix(body);
            mw.liveEditSaveService.animationsClearFix(body);
            this.html = body.innerHTML;
        });
        mw.liveEditSaveService.saving = true;

        /************  START base64  ************/
        data = JSON.stringify(data);
        data = btoa(encodeURIComponent(data).replace(/%([0-9A-F]{2})/g,
            function toSolidBytes(match, p1) {
                return String.fromCharCode('0x' + p1);
            }));
        data = {data_base64:data};
        /************  END base64  ************/

        var xhr = mw.ajax({
            type: 'POST',
            url: mw.settings.api_url + 'save_edit',
            data: data,
            dataType: "json",
            success: function (saved_data) {
                if(saved_data && saved_data.new_page_url && !mw.liveEditSaveService.DraftSaving){
                    window.mw.parent().askusertostay = false;
                    window.mw.askusertostay = false;
                    window.location.href  = saved_data.new_page_url;

                }
            }
        });

        xhr.always(function() {
            mw.liveEditSaveService.saving = false;
        });
        return xhr;
    },
    parseContent: function(root) {
        root = root || document.body;
        var doc = mw.tools.parseHtml(root.innerHTML);
        mw.$('.element-current', doc).removeClass('element-current');
        mw.$('.element-active', doc).removeClass('element-active');
        mw.$('.disable-resize', doc).removeClass('disable-resize');
        mw.$('.mw-module-drag-clone', doc).removeClass('mw-module-drag-clone');
        mw.$('.ui-draggable', doc).removeClass('ui-draggable');
        mw.$('.ui-draggable-handle', doc).removeClass('ui-draggable-handle');
        mw.$('.mt-ready', doc).removeClass('mt-ready');
        mw.$('.mw-webkit-drag-hover-binded', doc).removeClass('mw-webkit-drag-hover-binded');
        mw.$('.module-cat-toggle-Modules', doc).removeClass('module-cat-toggle-Modules');
        mw.$('.mw-module-drag-clone', doc).removeClass('mw-module-drag-clone');
        mw.$('-module', doc).removeClass('-module');
        mw.$('.empty-element', doc).remove();
        mw.$('.empty-element', doc).remove();
        mw.$('.edit .ui-resizable-handle', doc).remove();
        mw.$('script', doc).remove();

        //var doc = mw.$(doc).find('script').remove();

        mw.tools.classNamespaceDelete('all', 'ui-', doc, 'starts');
        mw.$("[contenteditable]", doc).removeAttr("contenteditable");
        var all = doc.querySelectorAll('[contenteditable]'),
            l = all.length,
            i = 0;
        for (; i < l; i++) {
            all[i].removeAttribute('contenteditable');
        }
        var all = doc.querySelectorAll('.module'),
            l = all.length,
            i = 0;
        for (; i < l; i++) {
            if (all[i].querySelector('.edit') === null) {
                all[i].innerHTML = '';
            }
        }
        return doc;
    },
    htmlAttrValidate:function(edits){
        var final = [];
        $.each(edits, function(){
            var html = this.outerHTML;
            html = html.replace(/url\(&quot;/g, "url('");
            html = html.replace(/jpg&quot;/g, "jpg'");
            html = html.replace(/jpeg&quot;/g, "jpeg'");
            html = html.replace(/png&quot;/g, "png'");
            html = html.replace(/gif&quot;/g, "gif'");
            final.push($(html)[0]);
        })
        return final;
    },
    cleanUnwantedTags: function (body) {


        mw.$('.mw-skip-and-remove,script', body).remove();
        return body;
    },
    animationsClearFix:function(body){
        mw.$('[class*="animate__"]').each(function () {
            mw.tools.classNamespaceDelete(this, 'animate__');
        });
        return body;
    },
    collectData: function(edits) {
        mw.$(edits).each(function(){
            mw.$('meta', this).remove();
            $('.mw-le-spacer', this).empty().removeAttr('data-resizable').removeAttr('style')
        });

        edits = this.htmlAttrValidate(edits);
        var l = edits.length,
            i = 0,
            helper = {},
            master = {};
        if (l > 0) {
            for (; i < l; i++) {
                helper.item = edits[i];
                var rel = mw.tools.mwattr(helper.item, 'rel');
                if (!rel) {
                    mw.$(helper.item).removeClass('changed');
                    mw.tools.foreachParents(helper.item, function(loop) {
                        var cls = this.className;
                        var rel = mw.tools.mwattr(this, 'rel');
                        if (mw.tools.hasClass(cls, 'edit') && mw.tools.hasClass(cls, 'changed') && (!!rel)) {
                            helper.item = this;
                            mw.tools.stopLoop(loop);
                        }
                    });
                }
                var rel = mw.tools.mwattr(helper.item, 'rel');
                if (!rel) {
                    var field = !!helper.item.id ? '#'+helper.item.id : '';
                    console.warn('Skipped save: .edit'+field+' element does not have rel attribute.');
                    continue;
                }
                mw.$(helper.item).removeClass('changed orig_changed');
                mw.$(helper.item).removeClass('module-over');

                mw.$('.module-over', helper.item).each(function(){
                    mw.$(this).removeClass('module-over');
                });
                mw.$('[class]', helper.item).each(function(){
                    var cls = this.getAttribute("class");
                    if(typeof cls === 'string'){
                        cls = cls.trim();
                    }
                    if(!cls){
                        this.removeAttribute("class");
                    }
                });
                var content = mw.liveEditSaveService.cleanUnwantedTags(helper.item).innerHTML;
                var attr_obj = {};
                var attrs = helper.item.attributes;
                if (attrs.length > 0) {
                    var ai = 0,
                        al = attrs.length;
                    for (; ai < al; ai++) {
                        attr_obj[attrs[ai].nodeName] = attrs[ai].nodeValue;
                    }
                }
                var obj = {
                    attributes: attr_obj,
                    html: content
                };
                var objdata = "field_data_" + i;
                master[objdata] = obj;
            }
        }
        return master;
    },
    getData: function(root) {
        var body = mw.liveEditSaveService.parseContent(root).body,
            edits = body.querySelectorAll('.edit.changed');
        return mw.liveEditSaveService.collectData(edits);
    },

    saveDisabled: false,
    draftDisabled: false,
    save: async function(data, success, fail) {
        mw.trigger('beforeSaveStart', data);
        // todo:
        if (mw.liveedit && mw.liveedit.cssEditor) {
            mw.liveedit.cssEditor.publishIfChanged();
        }
        if (mw.liveEditSaveService.saveDisabled) return false;
        if(!data){
            var body = mw.liveEditSaveService.parseContent().body,
                edits = body.querySelectorAll('.edit.changed');
            data = mw.liveEditSaveService.collectData(edits);
        }

        var animations = (mw.__pageAnimations || []).filter(function (item) {
            return item.animation !== 'none'
        })

        var options = {
            group: 'template',
            key: 'animations-global',
            value: JSON.stringify(animations)
        };

        await new Promise(resolve =>  {
            mw.options.saveOption(options, function(){
                resolve()
            });
        })



        if (mw.tools.isEmptyObject(data)) {
            if(success){
                success.call({})
            }
            return false
        };

        mw._liveeditData = data;

        mw.trigger('saveStart', mw._liveeditData);

        var xhr = mw.liveEditSaveService.coreSave(mw._liveeditData);
        xhr.error(function(sdata){

            if(xhr.status == 403){
                var modal = mw.dialog({
                    id : 'save_content_error_iframe_modal',
                    html:"<iframe id='save_content_error_iframe' style='overflow-x:hidden;overflow-y:auto;' class='mw-modal-frame' ></iframe>",
                    width:$(window).width() - 90,
                    height:$(window).height() - 90
                });

                mw.askusertostay = false;

                mw.$("#save_content_error_iframe").ready(function() {
                    var doc = document.getElementById('save_content_error_iframe').contentWindow.document;
                    doc.open();
                    doc.write(xhr.responseText);
                    doc.close();
                    var save_content_error_iframe_reloads = 0;
                    doc = document.getElementById('save_content_error_iframe').contentWindow.document;

                    mw.$("#save_content_error_iframe").load(function(){
                        // cloudflare captcha
                        var is_cf =  mw.$('.challenge-form',doc).length;
                        save_content_error_iframe_reloads++;

                        if(is_cf && save_content_error_iframe_reloads == 2){
                            setTimeout(function(){
                                mw.askusertostay = false;
                                mw.$('#save_content_error_iframe_modal').remove();
                            }, 150);

                        }
                    });

                });
            }
            if(fail){
                fail.call(sdata)
            }
        });
        xhr.success(function(sdata) {
            mw.$('.edit.changed').removeClass('changed');
            mw.$('.orig_changed').removeClass('orig_changed');
            if (document.querySelector('.edit.changed') !== null) {
                mw.liveEditSaveService.save();
            } else {
                mw.askusertostay = false;
                mw.trigger('saveEnd', sdata);
            }
            if(success){
                success.call(sdata)
            }

        });
        xhr.fail(function(jqXHR, textStatus, errorThrown) {
            mw.trigger('saveFailed', textStatus, errorThrown);
            if(fail){
                fail.call(sdata)
            }
        });
        return xhr;
    },

}

addEventListener('load', () => {
    const save = async () => {
        return new Promise((resolve) => {
            mw.liveEditSaveService.save(undefined, () => resolve(true), () => resolve(false));
        })
    };

    mw.top().app.save = async () => {

        return await save()
    };

    window.addEventListener('keydown', function(event){
        mw.top().app.canvas.dispatch('iframeKeyDown', {event})
    })

})

self.onbeforeunload = function(event) {
    mw.top().app.canvas.dispatch('liveEditCanvasBeforeUnload');

    // prevent user from leaving if there are unsaved changes
    var liveEditIframe = (mw.top().app.canvas.getWindow());
    if (liveEditIframe
        && liveEditIframe.mw && liveEditIframe.mw.askusertostay) {
        return true;
    } else {
        mw.top().spinner({element: mw.top().app.canvas.getFrame().parentElement, decorate: true, size: 52}).show()
    }
};
