import {HandleMenu} from "../handle-menu";

export const ElementHandleContent = function () {
    this.root = mw.element();
    this.menuHolder = mw.element();
    this.root.append(this.menuHolder)

    this.menu = new HandleMenu({
        id: 'mw-handle-item-element',
        className: 'mw-handle-type-default',
        buttons: [
            {
                title: mw.lang('Insert'),
                icon: 'mdi-plus-circle',
                className: 'mw-handle-insert-button',
                hover: [
                    function (e){
                        handleInsertTargetDisplay(mw._activeElementOver, mw.handleElement.positionedAt);
                    },
                    function (e){
                        handleInsertTargetDisplay('hide');
                    }
                ],
                action: function (el) {
                    if (!mw.tools.hasClass(el, 'active')) {
                        mw.tools.addClass(el, 'active');
                        mw.drag.plus.locked = true;
                        mw.$('.mw-tooltip-insert-module').remove();
                        mw.drag.plusActive = this === mw.drag.plusTop ? 'top' : 'bottom';

                        var tooltip = new mw.ToolTip({
                            content: document.getElementById('plus-modules-list').innerHTML,
                            element: el,
                            position: mw.drag.plus.tipPosition(this.currentNode),
                            template: 'mw-tooltip-default mw-tooltip-insert-module',
                            id: 'mw-plus-tooltip-selector',
                            overlay: true
                        });
                        tooltip.on('removed', function () {
                            mw.drag.plus.locked = false;
                        });
                        mw._initHandles.hideAll();

                        var tip = tooltip.tooltip.get(0);
                        setTimeout(function (){
                            $('#mw-plus-tooltip-selector').addClass('active').find('.mw-ui-searchfield').focus();
                        }, 10);
                        mw.tabs({
                            nav: tip.querySelectorAll('.mw-ui-btn'),
                            tabs: tip.querySelectorAll('.module-bubble-tab'),
                        });

                        mw.$('.mw-ui-searchfield', tip).on('input', function () {
                            var resultsLength = mw.drag.plus.search(this.value, tip);
                            if (resultsLength === 0) {
                                mw.$('.module-bubble-tab-not-found-message').html(mw.msg.no_results_for + ': <em>' + this.value + '</em>').show();
                            }
                            else {
                                mw.$(".module-bubble-tab-not-found-message").hide();
                            }
                        });
                        mw.$('#mw-plus-tooltip-selector li').each(function () {
                            this.onclick = function () {
                                var name = mw.$(this).attr('data-module-name');
                                var conf = { class: this.className };
                                if(name === 'layout') {
                                    conf.template = mw.$(this).attr('template');
                                }
                                mw.module.insert(mw._activeElementOver, name, conf, mw.handleElement.positionedAt);
                                mw.wysiwyg.change(mw._activeElementOver)
                                tooltip.remove();
                            };
                        });
                    }
                }
            }
        ],
        menu: [
            {
                title: 'Edit HTML',
                icon: 'mw-icon-code',
                action: function () {
                    mw.editSource(mw._activeElementOver);
                }
            },
            {
                title: 'Edit Style',
                icon: 'mdi mdi-layers',
                action: function () {
                    mw.liveEditSettings.show();
                    mw.sidebarSettingsTabs.set(3);
                    if(mw.cssEditorSelector){
                        mw.liveEditSelector.active(true);
                        mw.liveEditSelector.select(mw._activeElementOver);
                    } else {
                        mw.$(mw.liveEditWidgets.cssEditorInSidebarAccordion()).on('load', function () {
                            setTimeout(function(){
                                mw.liveEditSelector.active(true);
                                mw.liveEditSelector.select(mw._activeElementOver);
                            }, 333);
                        });
                    }
                    mw.liveEditWidgets.cssEditorInSidebarAccordion();
                }
            },
            {
                title: 'Remove',
                icon: 'mw-icon-bin',
                className:'mw-handle-remove',
                action: function () {
                    mw.drag.delete_element(mw._activeElementOver);
                    mw.handleElement.hide()
                }
            }
        ]
    });

    this.menuHolder.append(this.menu.wrapper)

}
