export default function layoutSettings(activeTab, optionGroup) {
    return {
        activeTab: 'image',
        backgroundSize: 'auto',
        supports: [],
        optionGroup: '',
        modulesList: [],
        modalId: null,

        destroy() {

        },
        init() {

            this.modalId = this.$refs.modalContainer.getAttribute('wire:key')
            this.modalId = this.modalId.substring(0, this.modalId.indexOf('.')) + '-action'


            let targets = this.getTargets();

            if (targets.bg) {
                // add to support
                this.supports.push('image');
                this.supports.push('video');
                this.supports.push('other');
                this.supports.push('color');

            }
            if (targets.bgOverlay) {
                // remove from supprot

            }

            this.$watch('backgroundSize', (size) => {
                this.changeBackgroundSize(size);
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
            let modulesList = [];

            if (target) {
                var mod_in_mods_html_btn = '';
                var _win = mw.top().app.canvas.getWindow() || window;
                var mods_in_mod = _win.$(target).find('.module');

                if (mods_in_mod) {
                    $(mods_in_mod).each(function () {
                        var isInaccessible = mw.top().app.liveEdit.liveEditHelpers.targetIsInacesibleModule(this);
                        if (!isInaccessible) {
                            var moduleType = $(this).attr("type") || $(this).attr("data-type");
                            var moduleId = $(this).attr("id");
                            var moduleTitle = $(this).attr("data-mw-title") || moduleType;
                            modulesList.push({
                                moduleId,
                                moduleType,
                                moduleTitle
                            });
                        }
                    });
                }

                this.modulesList = modulesList;
            }


            return {bg, bgOverlay, bgNode, target, modulesList};
        },

        openModuleSettings(moduleId) {
            if (this.modalId) {
                Livewire.dispatch('close-modal', {id: this.modalId})
            }

           // $("form[wire\\:submit\\.prevent=\"callMountedAction\"]").promise().done((self) => { console.log(self); });


            setTimeout(() => {
                mw.top().openModuleSettings(moduleId)
            }, 2000);



        },

        changeBackgroundSize(size){
            const {bg, bgOverlay, bgNode, target} = this.getTargets();
            mw.top().app.layoutBackground.setBackgroundImageSize(bgNode, size);

        },

        handleReadyLayoutSettingLoaded() {
            let {bg, bgOverlay, bgNode, target, modulesList} = this.getTargets();
            let bgImage = mw.top().app.layoutBackground.getBackgroundImage(bgNode);
            let bgVideo = mw.top().app.layoutBackground.getBackgroundVideo(bgNode);
            let bgCursor = mw.top().app.layoutBackground.getBackgroundCursor(bgNode);
            let bgSize = mw.top().app.layoutBackground.getBackgroundImageSize(bgNode);
            if (!bgSize) {
                bgSize = 'auto';
            }


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
                const {bg, bgOverlay, bgNode, target} = this.getTargets();
                mw.top().app.layoutBackground.setBackgroundCursor(target, cursorPicker.file);
            });
            picker.on('change', () => {
                const {bg, bgOverlay, bgNode, target} = this.getTargets();
                videoPicker.setFile(null);
                mw.top().app.layoutBackground.setBackgroundImage(bgNode, picker.file);
                mw.top().app.registerChange(mw.top().app.liveEdit.handles.get('layout').getTarget());
            });
            videoPicker.on('change', () => {
                const {bg, bgOverlay, bgNode, target} = this.getTargets();
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
                    let {bg, bgOverlay, bgNode, target} = this.getTargets();
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

        },
        handleLayoutTargetChange() {
            this.handleReadyLayoutSettingLoaded();
        },
        showHideRemoveBackgroundsButtons() {
            let {bg, bgOverlay, bgNode, target} = this.getTargets();
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
