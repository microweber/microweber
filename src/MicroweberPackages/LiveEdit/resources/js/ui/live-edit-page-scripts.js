



if(window.self !== window.top) {


    //mw.require('options.js');



    mw.liveEditSaveService = {
        grammarlyFix: function (data) {

            mw.$("grammarly-btn", data).remove();
            mw.$("grammarly-card", data).remove();
            mw.$("g.gr_", data).each(function () {
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
        coreSave: function (data) {
            if (!data) return false;
            $.each(data, function () {
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
            data = {data_base64: data};
            /************  END base64  ************/

            var xhr = mw.ajax({
                type: 'POST',
                url: mw.settings.api_url + 'save_edit',
                data: data,
                dataType: "json",
                success: function (saved_data) {
                    if (saved_data && saved_data.new_page_url && !mw.liveEditSaveService.DraftSaving) {
                        window.mw.parent().askusertostay = false;
                        window.mw.askusertostay = false;
                        window.location.href = saved_data.new_page_url;

                    }
                }
            });

            xhr.always(function () {
                mw.liveEditSaveService.saving = false;
            });
            return xhr;
        },
        parseContent: function (root) {
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
        htmlAttrValidate: function (edits) {
            var final = [];
            $.each(edits, function () {
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
        animationsClearFix: function (body) {
            mw.$('[class*="animate__"]').each(function () {
                mw.tools.classNamespaceDelete(this, 'animate__');
            });
            return body;
        },
        collectData: function (edits) {
            mw.$(edits).each(function () {
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
                        mw.tools.foreachParents(helper.item, function (loop) {
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
                        var field = !!helper.item.id ? '#' + helper.item.id : '';
                        console.warn('Skipped save: .edit' + field + ' element does not have rel attribute.');
                        continue;
                    }
                    mw.$(helper.item).removeClass('changed orig_changed');
                    mw.$(helper.item).removeClass('module-over');
                    mw.$('.mw-le-ghost-layout', helper.item).remove();
                    mw.$('#mw-non-existing-temp-element-holder', helper.item).remove();

                    mw.$('.module-over', helper.item).each(function () {
                        mw.$(this).removeClass('module-over');
                    });


                    mw.$('.element[data-mwplaceholder]', helper.item).each(function () {
                        var isEmpty = !this.innerHTML.trim();
                        if (!isEmpty) {
                            mw.$(this).removeAttr('data-mwplaceholder');
                        }

                    });
                    mw.$('.element.lipsum', helper.item).each(function () {
                        mw.$(this).removeClass('lipsum');
                    });

                    mw.$('[data-mw-live-edithover]', helper.item).each(function () {
                        mw.$(this).removeAttr('data-mw-live-edithover');
                    });

                    mw.$('[data-mw-temp-option-save]', helper.item).each(function () {
                        mw.$(this).removeAttr('data-mw-temp-option-save');
                    });
                    mw.$('[class]', helper.item).each(function () {
                        var cls = this.getAttribute("class");
                        if (typeof cls === 'string') {
                            cls = cls.trim();
                        }
                        if (!cls) {
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
        getData: function (root) {
            var body = mw.liveEditSaveService.parseContent(root).body,
                edits = body.querySelectorAll('.edit.changed');
            return mw.liveEditSaveService.collectData(edits);
        },

        saveDisabled: false,
        draftDisabled: false,
        save: function (data, success, fail) {
            mw.trigger('beforeSaveStart', data);
            // todo:

             if(mw.top().app) {
                  if (mw.top().app && mw.top().app.cssEditor) {

                      mw.top().app.cssEditor.publishIfChanged();
                 }
             }

             if(mw.top().app) {
                  if (mw.top().app && mw.top().options) {

                      mw.top().options.publishTempOptions(document);
                 }
             }
            if (mw.liveEditSaveService.saveDisabled) {
                return false;
            }
            if (!data) {
                var body = mw.liveEditSaveService.parseContent().body,
                    edits = body.querySelectorAll('.edit.changed');
                data = mw.liveEditSaveService.collectData(edits);
            }

            var animations = (mw.__pageAnimations || []).filter(function (item) {
                return item.animation !== 'none';
            });

            if (animations && animations.length > 0) {
                var options = {
                    group: 'template',
                    key: 'animations-global',
                    value: JSON.stringify(animations)
                };

                mw.top().options.saveOption(options);
                // await new Promise(resolve =>  {
                //     mw.options.saveOption(options, function(){
                //         resolve();
                //     });
                // });
            }


            if (mw.tools.isEmptyObject(data)) {
                if (success) {
                    success.call({})
                }
                return false;
            }


            mw._liveeditData = data;

            mw.trigger('saveStart', mw._liveeditData);

            var xhr = mw.liveEditSaveService.coreSave(mw._liveeditData);
            xhr.error(function (sdata) {

                if (xhr.status == 403) {
                    var modal = mw.dialog({
                        id: 'save_content_error_iframe_modal',
                        html: "<iframe id='save_content_error_iframe' style='overflow-x:hidden;overflow-y:auto;' class='mw-modal-frame' ></iframe>",
                        width: $(window).width() - 90,
                        height: $(window).height() - 90
                    });

                    mw.askusertostay = false;

                    mw.$("#save_content_error_iframe").ready(function () {
                        var doc = document.getElementById('save_content_error_iframe').contentWindow.document;
                        doc.open();
                        doc.write(xhr.responseText);
                        doc.close();
                        var save_content_error_iframe_reloads = 0;
                        doc = document.getElementById('save_content_error_iframe').contentWindow.document;

                        mw.$("#save_content_error_iframe").load(function () {
                            // cloudflare captcha
                            var is_cf = mw.$('.challenge-form', doc).length;
                            save_content_error_iframe_reloads++;

                            if (is_cf && save_content_error_iframe_reloads == 2) {
                                setTimeout(function () {
                                    mw.askusertostay = false;
                                    mw.$('#save_content_error_iframe_modal').remove();
                                }, 150);

                            }
                        });

                    });
                }
                if (fail) {
                    fail.call(sdata)
                }
            });
            xhr.success(function (sdata) {
                mw.$('.edit.changed').removeClass('changed');
                mw.$('.orig_changed').removeClass('orig_changed');
                if (document.querySelector('.edit.changed') !== null) {
                    mw.liveEditSaveService.save();
                } else {
                    mw.askusertostay = false;
                    mw.trigger('saveEnd', sdata);
                }
                if (success) {
                    success.call(sdata)
                }

            });
            xhr.fail(function (jqXHR, textStatus, errorThrown) {
                mw.trigger('saveFailed', textStatus, errorThrown);
                if (fail) {
                    fail.call(sdata)
                }
            });
            return xhr;
        },

    };

    mw.saveLiveEdit = async () => {
        return new Promise((resolve) => {
            mw.liveEditSaveService.save(undefined, () => resolve(true), () => resolve(false));
        })
    };

    mw.top().app.save = async () => {

        return await mw.saveLiveEdit()
    };



    addEventListener('load', () => {

        window.addEventListener('keydown', function (event) {
            mw.top().app.canvas.dispatch('iframeKeyDown', {event})
        });


        const _handleEmptyEditFields = function() {

            function manageNode(node) {
                const isEmptyLike = !node.innerHTML.trim();

                if(isEmptyLike && node.innerHTML.trim() === node.textContent.trim()) {
                    mw.element(node).append(`<p class="element" data-mwplaceholder="${mw.lang(`This is sample text for your page`)}"></p>`);
                } else {
                    node.classList[ isEmptyLike ? 'add' : 'remove']('mw-le-empty-element');
                }
            }

            document.querySelectorAll('.edit').forEach(function(node) {
                if(!node.__$$_handleEmptyEditFields) {
                    node.__$$_handleEmptyEditFields = true;
                    manageNode(node);
                    node.addEventListener('input', function(){
                        manageNode(this);
                    });
                }
            });

            mw.top().app.on('editChanged', edit => {
                setTimeout(() => manageNode(edit));
            })
        };

        _handleEmptyEditFields()



    });


    let _beforeUnload = null;

    mw.top().app.isNavigating = () => {
        return !!_beforeUnload && _beforeUnload.returnValue  && _beforeUnload.defaultPrevented === true
    };



    self.onbeforeunload = function (event) {
        _beforeUnload = event;

        // prevent user from leaving if there are unsaved changes
    //    var liveEditIframe = window;

        var liveEditIframe = mw.top().app.canvas.getWindow();

        liveEditIframe.mw.isNavigating = true;

        mw.top().app.canvas.dispatch('liveEditCanvasBeforeUnload');


        setTimeout(function (liveEditIframe) {
                if(liveEditIframe) {
                    if (liveEditIframe && liveEditIframe.mw) {
                        liveEditIframe.mw.isNavigating = false;
                    }
                }
        }, 1500,liveEditIframe);


        if (liveEditIframe
            && liveEditIframe.mw && liveEditIframe.mw.askusertostay) {
            return true;
        } else {
            mw.top().spinner({element: mw.top().app.canvas.getFrame().parentElement, decorate: true, size: 52}).show()
        }
    };


    mw.drag = mw.drag || {};
    mw.drag.save = function () {
        return mw.liveEditSaveService.save();
    };
    mw.drag.fix_placeholders = function (isHard, selector) {
        selector = selector || '.edit .row';

        var more_selectors2 = 'div.col-md';
        var a = mw.top().app.templateSettings.helperClasses.external_grids_col_classes;
        var index;
        for (index = a.length - 1; index >= 0; --index) {
            more_selectors2 += ',div.' + a[index];
        }
        mw.$(selector).each(function () {
            var el = mw.$(this);
            el.children(more_selectors2).each(function () {
                var empty_child = mw.$(this).children('*');
                if (empty_child.size() == 0) {
                    mw.$(this).append('<div class="element" id="mw-element-' + mw.random() + '">' + '</div>');
                    var empty_child = mw.$(this).children("div.element");
                }
            });
        });

    };

    mw.drag.module_settings = function () {
        var target = mw.top().app.liveEdit.moduleHandle.getTarget();
        return mw.top().app.editor.dispatch('onModuleSettingsRequest', target);
    };



    document.documentElement.addEventListener('click', function (event) {

        var target = event.target;
        var link = mw.tools.firstParentOrCurrentWithTag(target, 'a');

        if(link) {
            const tmp = document.createElement('a');
            tmp.href = link.href;
            if(tmp.host !== location.host && (!link.target || link.target === '_self')) {
                event.preventDefault();
                open(link.href);
            }
        }



    })
    document.addEventListener('keydown', function (event) {
        if (event.ctrlKey && event.key === 's') {
            return mw.top().app.editor.dispatch('Ctrl+S', event);
        }
    });
}
if (self === top) {
    window.addEventListener("load", (event) => {
        if (window.mwLiveEditIframeBackUrl) {
            // Create the <a> element (button)
            var stickyButton = document.createElement('a');
            stickyButton.id = 'back-to-live-sticky-button';
            stickyButton.textContent = 'Live Edit';
            stickyButton.href = window.mwLiveEditIframeBackUrl;
            stickyButton.classList.add('sticky');

            // Append the button to the document body
            document.body.appendChild(stickyButton);

            // Apply sticky behavior
            stickyButton.classList.add('sticky');



            // Create and apply the CSS style dynamically
            var style = document.createElement('style');
            style.textContent = `
                #back-to-live-sticky-button {
                    position: fixed;
                    left: 50%;
                    transform: translateX(-50%);
                    z-index: 999;
                    transition: top 0.3s;
                    background: #000;
                    color:#fff !important;
                    padding: 5px 20px;
                    border-radius: 5px;
                    border-top-left-radius: 0;
                    border-top-right-radius: 0;
                    font-family: Arial, sans-serif;
                }

                #back-to-live-sticky-button.sticky {
                    top: 0;
                }


            `;

            document.head.appendChild(style);
        }
    });
}



