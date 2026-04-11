window.layoutSettings = function layoutSettings(activeTab, optionGroup) {
    return {
        activeTab: 'image',
        backgroundSize: 'auto',
        supports: [],
        optionGroup: '',
        modulesList: [],
        modalId: null,        // Add reactive properties for background states
        hasBackgroundImage: false,
        hasBackgroundVideo: false,
        hasBackgroundColor: false,
        hasBackgroundCursor: false,
        backgroundImagePreview: null,
        backgroundColorPreview: null,
        backgroundCursorPreview: null,

        destroy() {

        },
        init() {

            var modalContainer = this.$el.closest('[wire\\:key]');
            if (modalContainer) {
                this.modalId = modalContainer.getAttribute('wire:key');
                this.modalId = this.modalId.substring(0, this.modalId.indexOf('.')) + '-action';
            }


            let targets = this.getTargets();

            // Reset supports array before re-detecting
            this.supports = [];

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


            let target = window.mw.top().app.liveEdit.getSelectedLayoutNode();
            if (!target) {
                target = mw.top().app.liveEdit.handles.get('layout').getTarget();
            }





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
                var _win = mw.top().app.canvas?.getWindow() || window;
                var mods_in_mod = _win.$(target).find('.module');

                if (mods_in_mod) {
                    var self = this;
                    $(mods_in_mod).each(function () {
                        var isInaccessible = mw.top().app.liveEdit.liveEditHelpers.targetIsInacesibleModule(this);
                        if (!isInaccessible) {
                            var moduleType = $(this).attr("type") || $(this).attr("data-type");
                            var moduleId = $(this).attr("id");
                            var moduleTitle = self.getModuleTitle(this, moduleType);
                            var moduleIcon = self.getModuleIcon(moduleType);
                            modulesList.push({
                                moduleId,
                                moduleType,
                                moduleTitle,
                                moduleIcon
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

        changeBackgroundSize(size) {
            const {bg, bgOverlay, bgNode, target} = this.getTargets();
            mw.top().app.layoutBackground.setBackgroundImageSize(bgNode, size);

        },        // Add method to update background states
        updateBackgroundStates() {
            let {bg, bgOverlay, bgNode, target} = this.getTargets();

            // Check for background image
            let bgImage = mw.top().app.layoutBackground.getBackgroundImage(bgNode);
            this.hasBackgroundImage = !!bgImage;
            this.backgroundImagePreview = bgImage;

            // Check for background video
            let bgVideo = mw.top().app.layoutBackground.getBackgroundVideo(bgNode);
            this.hasBackgroundVideo = !!bgVideo;

            // Check for background color
            let bgColor = mw.top().app.layoutBackground.getBackgroundColor(bgOverlay);
            this.hasBackgroundColor = !!bgColor && bgColor !== 'rgba(0, 0, 0, 0)' && bgColor !== 'transparent';
            this.backgroundColorPreview = bgColor;

            // Check for cursor - only show if there's actually a cursor image set
            let bgCursor = mw.top().app.layoutBackground.getBackgroundCursor(bgNode);
            this.hasBackgroundCursor = !!bgCursor && bgCursor.trim() !== '' && bgCursor.trim() !== 'auto';
            this.backgroundCursorPreview = bgCursor;
        },

        // Add method to remove background color
        removeBackgroundColor() {
            let {bg, bgOverlay, bgNode, target} = this.getTargets();
            mw.top().app.layoutBackground.setBackgroundColor(bgOverlay, '');
            this.showHideRemoveBackgroundsButtons();
            mw.top().app.registerChange(mw.top().app.liveEdit.handles.get('layout').getTarget());
            this.updateBackgroundStates();
        },

        handleReadyLayoutSettingLoaded() {
            let {bg, bgOverlay, bgNode, target, modulesList} = this.getTargets();
            let bgImage = mw.top().app.layoutBackground.getBackgroundImage(bgNode);

            let bgVideo =  mw.top().app.layoutBackground.getBackgroundVideo(bgNode);





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
                this.updateBackgroundStates();
            });
            picker.on('change', () => {
                const {bg, bgOverlay, bgNode, target} = this.getTargets();
                videoPicker.setFile(null);
                mw.top().app.layoutBackground.setBackgroundImage(bgNode, picker.file);
                mw.top().app.registerChange(mw.top().app.liveEdit.handles.get('layout').getTarget());
                this.updateBackgroundStates();
            });
            videoPicker.on('change', () => {
                const {bg, bgOverlay, bgNode, target} = this.getTargets();
                mw.top().app.layoutBackground.setBackgroundVideo(bgNode, videoPicker.file);
                picker.setFile(null);
                mw.top().app.registerChange(mw.top().app.liveEdit.handles.get('layout').getTarget());
                this.updateBackgroundStates();
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
                        this.updateBackgroundStates();
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

            // Update background states after initialization
            this.updateBackgroundStates();
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
            this.updateBackgroundStates();
        },

        getModuleTitle(moduleElement, moduleType) {
            // Try to get module info from Microweber's module system first
            if (window.mw?.top()?.app?.modules) {
                const info = window.mw.top().app.modules.getModuleInfo(moduleType);
                if (info && info.name) {
                    return info.name;
                }
            }

            // moduleElement is already a native DOM element from the canvas document
            if (moduleElement && typeof moduleElement.getAttribute === 'function') {
                // Try to get a meaningful title for the module
                const title = moduleElement.getAttribute('data-title') ||
                    moduleElement.getAttribute('data-module-title') ||
                    moduleElement.getAttribute('data-mw-title') ||
                    moduleElement.querySelector('.module-title')?.textContent ||
                    moduleElement.querySelector('h1, h2, h3, h4')?.textContent;

                if (title) {
                    return title.length > 20 ? title.substring(0, 20) + '...' : title;
                }
            }

            // Fallback to formatted module type
            return this.formatModuleType(moduleType);
        },

        formatModuleType(moduleType) {
            if (!moduleType) return 'Module';

            // Convert module type to readable format
            return moduleType
                .replace(/[_-]/g, ' ')
                .replace(/\b\w/g, l => l.toUpperCase())
                .trim();
        },

        getModuleIcon(moduleType) {
            // Use the new getModuleIcon service function directly
            if (window.mw?.top()?.app?.modules) {
                return window.mw.top().app.modules.getModuleIcon(moduleType);
            }

            // Fallback to default icon
            return '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>';
        }
    }
}
