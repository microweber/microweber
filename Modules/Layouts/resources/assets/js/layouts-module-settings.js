export default function layoutSettings() {
    return {
        activeTab: 'image',
        optionGroup: '',
        destroy() {
            mw.top().app.liveEdit.handles.get('layout').off('targetChange', this.handleLayoutTargetChange.bind(this));
            mw.top().$(mw.top().dialog.get(this.frameElement)).on('Remove', () => {
                mw.top().app.liveEdit.handles.get('layout').off('targetChange', this.handleLayoutTargetChange.bind(this));
            });
        },
        init(activeTab, optionGroup) {


            mw.top().app.liveEdit.handles.get('layout').on('targetChange', this.handleLayoutTargetChange.bind(this));
            mw.top().$(mw.top().dialog.get(this.frameElement)).on('Remove', () => {
                mw.top().app.liveEdit.handles.get('layout').off('targetChange', this.handleLayoutTargetChange.bind(this));
            });


            this.handleReadyLayoutSettingLoaded();
        },
        getTargets() {
            const target = mw.top().app.liveEdit.handles.get('layout').getTarget();
            let bg, bgOverlay, bgNode;
            if (target) {
                bg = target.querySelector('.mw-layout-background-block');
                if (bg) {
                    bgNode = bg.querySelector('.mw-layout-background-node');
                    bgOverlay = bg.querySelector('.mw-layout-background-overlay');
                }
                var tabLink = document.querySelector('#change-background-tab-link');
                if (target && bg) {
                    if (tabLink) {
                        tabLink.style.display = '';
                    }
                } else {
                    if (tabLink) {
                        tabLink.style.display = 'none';
                    }
                }
            }
            return { bg, bgOverlay, bgNode, target };
        },
        handleReadyLayoutSettingLoaded() {
            let { bg, bgOverlay, bgNode, target } = this.getTargets();
            let bgImage = mw.top().app.layoutBackground.getBackgroundImage(bgNode);
            let bgVideo = mw.top().app.layoutBackground.getBackgroundVideo(bgNode);
            let bgCursor = mw.top().app.layoutBackground.getBackgroundCursor(bgNode);
            let bgSize = mw.top().app.layoutBackground.getBackgroundImageSize(bgNode);
            if (!bgSize) {
                bgSize = 'auto';
            }
            document.querySelectorAll('[name="backgroundSize"]').forEach(el => {
                el.checked = el.value === bgSize;
                el.addEventListener('change', () => {
                    const { bg, bgOverlay, bgNode, target } = this.getTargets();
                    mw.top().app.layoutBackground.setBackgroundImageSize(bgNode, el.value);
                });
            });

            let picker = mw.app.singleFilePickerComponent({
                element: '#bg--image-picker',
                accept: 'images',
                file: bgImage ? bgImage : null
            });
            let videoPicker = mw.app.singleFilePickerComponent({
                element: '#bg--video-picker',
                accept: 'videos',
                file: bgVideo ? bgVideo : null,
                canEdit: false
            });
            let cursorPicker = mw.app.singleFilePickerComponent({
                element: '#bg--cursor-picker',
                accept: 'images',
                file: bgCursor ? bgCursor : null,
                canEdit: false
            });
            cursorPicker.on('change', () => {
                const { bg, bgOverlay, bgNode, target } = this.getTargets();
                mw.top().app.layoutBackground.setBackgroundCursor(target, cursorPicker.file);
            });
            picker.on('change', () => {
                const { bg, bgOverlay, bgNode, target } = this.getTargets();
                videoPicker.setFile(null);
                mw.top().app.layoutBackground.setBackgroundImage(bgNode, picker.file);
                mw.top().app.registerChange(mw.top().app.liveEdit.handles.get('layout').getTarget());
            });
            videoPicker.on('change', () => {
                const { bg, bgOverlay, bgNode, target } = this.getTargets();
                mw.top().app.layoutBackground.setBackgroundVideo(bgNode, videoPicker.file);
                picker.setFile(null);
                mw.top().app.registerChange(mw.top().app.liveEdit.handles.get('layout').getTarget());
            });

            var cpo = document.querySelector('#overlay-color-picker');
            var cpoPickerPause = false;
            var cpoPicker = mw.colorPicker({
                element: cpo,
                mode: 'inline',
                onchange: (color) => {
                    let { bg, bgOverlay, bgNode, target } = this.getTargets();
                    if (!cpoPickerPause) {
                        mw.top().app.layoutBackground.setBackgroundColor(bgOverlay, color);
                        this.showHideRemoveBackgroundsButtons();
                        mw.top().app.registerChange(mw.top().app.liveEdit.handles.get('layout').getTarget());
                    }
                }
            });

            if (target && bgOverlay) {
                var color = (getComputedStyle(bgOverlay).backgroundColor);
                if (color == 'rgba(0, 0, 0, 0)') {
                    color = 'rgba(0, 0, 0, 0.5)';
                }
                cpoPickerPause = true;
                cpoPicker.setColor(color);
                cpoPickerPause = false;
            }

            cpo.querySelectorAll('input').forEach(node => node.addEventListener('keyup', function (e) {
                if (e.key === 'Escape') {
                    const dialog = mw.dialog.get(this);
                    if (dialog) {
                        dialog.remove();
                    } else if (this.ownerDocument.defaultView.frameElement) {
                        const dialog = mw.dialog.get(this.ownerDocument.defaultView.frameElement);
                        if (dialog) {
                            dialog.remove();
                        }
                    }
                }
            }));

            if(!this.optionGroup || this.optionGroup == ''){
                return;
            }


            var mod_in_mods_html_btn = '';
            var _win = mw.top().app.canvas.getWindow() || window;
            var mods_in_mod = _win.$('#' + this.optionGroup).find('.module', '#' + this.optionGroup);

            if (mods_in_mod) {
                $(mods_in_mod).each(function () {
                    var isInaccessible = mw.top().app.liveEdit.liveEditHelpers.targetIsInacesibleModule(this);
                    if (!isInaccessible) {
                        var inner_mod_type = $(this).attr("type") || $(this).attr("data-type");
                        var inner_mod_id = $(this).attr("id");
                        var inner_mod_title = $(this).attr("data-mw-title") || inner_mod_type;

                        if (inner_mod_type) {
                            var inner_mod_type_admin = inner_mod_type + '/admin';
                            mod_in_mods_html_btn += '<a href="javascript:;" class="btn btn-outline-dark btn-sm" onclick=\'window.mw.parent().tools.open_global_module_settings_modal("' + inner_mod_type_admin + '","' + inner_mod_id + '")\'>' + inner_mod_title + '</a>';
                        }
                    }
                });
            }

            if (mod_in_mods_html_btn) {
                $('.current-template-modules-list-wrap').show();
                $('.current-template-modules-list-label').show();
                $('.current-template-modules-list').html(mod_in_mods_html_btn);
            } else {
                $('.current-template-modules-list-wrap').hide();
                $('.current-template-modules-list-label').hide();
            }
        },
        handleLayoutTargetChange() {
            this.handleReadyLayoutSettingLoaded();
        },
        showHideRemoveBackgroundsButtons() {
            let { bg, bgOverlay, bgNode, target } = this.getTargets();
            var hasBgColor = mw.top().app.layoutBackground.getBackgroundColor(bgOverlay);
            if (hasBgColor) {
                $('#overlay-color-picker-remove-color').show();
            } else {
                $('#overlay-color-picker-remove-color').hide();
            }
            mw.top().app.registerChange(mw.top().app.liveEdit.handles.get('layout').getTarget());
        }
    }
}
