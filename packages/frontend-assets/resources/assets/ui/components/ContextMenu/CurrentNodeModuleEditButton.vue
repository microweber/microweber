<template>
    <div v-show="isModuleElement || isLayoutElement">
        <div v-if="isModuleElement || isLayoutElement" class="current-node-module-edit">
            <div
                :class="{ 'active': isEditingModule }"
                :title="isLayoutElement ? 'Layout Settings' : 'Module Settings'"
                class="btn-icon live-edit-toolbar-buttons module-edit-button"
                @click="editCurrentModule"
                @mouseenter="onModuleHover"
            >
                <v-tooltip activator="parent" location="start">
                    {{ isLayoutElement ? 'Layout Settings' : 'Module Settings' }}
                </v-tooltip>
                <span v-html="getModuleEditIcon()"></span>
            </div>
        </div>
    </div>
</template>

<style scoped>
.current-node-module-edit {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 5px 0;
}

.module-edit-button {
    height: 32px;
    width: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.2s ease;
    user-select: none;
}

.module-edit-button:active {
    transform: scale(0.95);
}

.module-edit-button :deep(svg) {
    width: 24px;
    height: 24px;
    fill: currentColor;
}

.module-edit-button :deep(img) {
    width: 16px;
    height: 16px;
    object-fit: contain;
}
</style>

<script>
export default {
    name: 'CurrentNodeModuleEditButton',
    data() {
        return {
            currentElement: null,
            isModuleElement: false,
            isLayoutElement: false,
            isEditingModule: false,
            updateInterval: null,
            // Store event handler references for cleanup
            eventHandlers: {
                liveEditCanvasLoaded: null,
                canvasDocumentClick: null,
                moduleSettingsRequest: null,
                moduleSettingsEnd: null
            }
        };
    },
    mounted() {
        mw.app.on('ready', event => {
            this.setupEventListeners();
            this.updateCurrentNode();

            // Update periodically to catch dynamic changes
            // this.updateInterval = setInterval(() => {
            //     this.updateCurrentNode();
            // }, 1000);
        });
    },
    beforeUnmount() {
        this.cleanupEventListeners();
        if (this.updateInterval) {
            clearInterval(this.updateInterval);
        }
    },
    methods: {
        setupEventListeners() {
            // Listen for canvas changes
            if (window.mw?.app?.canvas) {
                this.eventHandlers.liveEditCanvasLoaded = () => {
                    this.updateCurrentNode();
                };

                this.eventHandlers.canvasDocumentClick = () => {
                    // Delay update to allow for element selection
                    setTimeout(() => {
                        this.updateCurrentNode();
                    }, 100);
                };

                window.mw.app.canvas.on('liveEditCanvasLoaded', this.eventHandlers.liveEditCanvasLoaded);
                window.mw.app.canvas.on('canvasDocumentClick', this.eventHandlers.canvasDocumentClick);
            }

            // Listen for module editor events
            if (window.mw?.app?.editor) {
                this.eventHandlers.moduleSettingsRequest = () => {
                    this.isEditingModule = true;
                };

                this.eventHandlers.moduleSettingsEnd = () => {
                    this.isEditingModule = false;
                    this.updateCurrentNode(); // Refresh current node after editing ends
                };

                window.mw.app.editor.on('onModuleSettingsRequest', this.eventHandlers.moduleSettingsRequest);
                window.mw.app.editor.on('onModuleSettingsEnd', this.eventHandlers.moduleSettingsEnd);
            }
        },

        cleanupEventListeners() {
            // Clean up canvas event listeners
            if (window.mw?.app?.canvas) {
                if (this.eventHandlers.liveEditCanvasLoaded) {
                    window.mw.app.canvas.off('liveEditCanvasLoaded', this.eventHandlers.liveEditCanvasLoaded);
                }
                if (this.eventHandlers.canvasDocumentClick) {
                    window.mw.app.canvas.off('canvasDocumentClick', this.eventHandlers.canvasDocumentClick);
                }
            }

            // Clean up editor event listeners
            if (window.mw?.app?.editor) {
                if (this.eventHandlers.moduleSettingsRequest) {
                    window.mw.app.editor.off('onModuleSettingsRequest', this.eventHandlers.moduleSettingsRequest);
                }
                if (this.eventHandlers.moduleSettingsEnd) {
                    window.mw.app.editor.off('onModuleSettingsEnd', this.eventHandlers.moduleSettingsEnd);
                }
            }

            // Clear event handler references
            this.eventHandlers = {
                liveEditCanvasLoaded: null,
                canvasDocumentClick: null,
                moduleSettingsRequest: null,
                moduleSettingsEnd: null
            };
        },

        updateCurrentNode() {
            try {
                const activeElement = mw.top().app.liveEdit.elementHandle.getTarget()
                    || window.mw.top().app.liveEdit.getSelectedNode()
                    || window.mw.top().app.liveEdit.getSelectedElementNode();

                if (activeElement !== this.currentElement) {
                    this.currentElement = activeElement;
                    this.checkIfModuleElement();
                }
            } catch (error) {
                console.warn('Error updating current node:', error);
                this.currentElement = null;
                this.isModuleElement = false;
                this.isLayoutElement = false;
            }
        },

        checkIfModuleElement() {
            if (!this.currentElement) {
                this.isModuleElement = false;
                this.isLayoutElement = false;
                return;
            }

            // Check if the current element is a module
            this.isModuleElement = this.isEditableModule(this.currentElement);

            // Check if the current element is a layout
            this.isLayoutElement = this.isEditableLayout(this.currentElement);

            // Check if module settings are currently open
            if (this.isModuleElement || this.isLayoutElement) {
                this.checkModuleEditingState();
            }
        },

        isEditableModule(element) {
            if (!element) return false;

            // Check if element is a module
            const isModule = element.classList.contains('module') ||
                element.hasAttribute('data-type') ||
                element.hasAttribute('data-module');

            if (!isModule) return false;

            // Check if module is inaccessible
            if (this.isModuleInaccessible(element)) {
                return false;
            }

            // Get module type
            const moduleType = element.getAttribute('data-type') ||
                element.getAttribute('type') ||
                element.getAttribute('data-module');

            // Filter out non-editable or system modules
            const excludedTypes = [
                'layouts',
                'layout',
                'text',
                'spacer',
                'divider'
            ];

            return moduleType && !excludedTypes.includes(moduleType.toLowerCase());
        },

        isEditableLayout(element) {
            if (!element) return false;

            // Check if element is a layout
            const isLayout = element.classList.contains('layout') ||
                element.hasAttribute('data-layout') ||
                element.hasAttribute('data-layout-name') ||
                element.classList.contains('module-layouts') ||
                element.classList.contains('edit');

            if (!isLayout) return false;

            // Check if layout is inaccessible
            if (this.isLayoutInaccessible(element)) {
                return false;
            }



            return true;
        },

        isLayoutInaccessible(layoutElement) {
            // Check for inaccessible layout markers
            return layoutElement.classList.contains('no-settings') ||
                layoutElement.classList.contains('inaccessibleLayout');
        },

        isModuleInaccessible(moduleElement) {
            // Use Microweber's built-in inaccessible module check
            if (window.mw?.top()?.app?.liveEdit?.liveEditHelpers?.targetIsInacesibleModule) {
                return window.mw.top().app.liveEdit.liveEditHelpers.targetIsInacesibleModule(moduleElement);
            }

            // Fallback check for inaccessible modules
            return moduleElement.classList.contains('no-settings') ||
                moduleElement.classList.contains('inaccessibleModule') ||
                moduleElement.classList.contains('inaccessibleModuleIfFirstParentIsLayout');
        },

        checkModuleEditingState() {
            // Check if module settings are currently open for this module
            try {
                const moduleId = this.currentElement.getAttribute('id');
                const isSettingsOpen = window.mw?.top()?.app?.liveEdit?.getActiveModuleSettings?.() === moduleId;
                this.isEditingModule = isSettingsOpen || false;
            } catch (error) {
                this.isEditingModule = false;
            }
        },

        editCurrentModule() {
            try {
                if (!this.currentElement) {
                    console.warn('No current element to edit');
                    return;
                }

                if (!this.isEditableModule(this.currentElement) && !this.isEditableLayout(this.currentElement)) {
                    console.warn('Current element is not an editable module or layout');
                    return;
                }

                // Set the editing state
                this.isEditingModule = true;


                mw.top().app.canvas.getWindow().mw.tools.scrollTo(this.currentElement, undefined, 100);




                // Trigger appropriate settings request
                if (this.isLayoutElement) {
                    // Trigger layout settings request
                    if (window.mw?.app?.editor?.dispatch) {
                        window.mw.app.editor.dispatch('onLayoutSettingsRequest', this.currentElement);
                    } else {
                        // Fallback to module settings for layouts
                        window.mw.app.editor.dispatch('onModuleSettingsRequest', this.currentElement);
                    }
                } else {
                    // Trigger module settings request
                    window.mw.app.editor.dispatch('onModuleSettingsRequest', this.currentElement);
                }
            } catch (error) {
                console.error('Error editing current element:', error);
                this.isEditingModule = false;
            }
        },

        onModuleHover() {
            // Optional: Add hover effects or preview functionality
            try {
                if (this.currentElement && (this.isModuleElement || this.isLayoutElement)) {
                    // Could add module or layout highlighting or preview here
                }
            } catch (error) {
                console.warn('Error on element hover:', error);
            }
        },

        getModuleEditIcon() {
            // Try to get appropriate icon from Microweber's icon service
            if (window.mw?.top()?.app?.iconService?.icon) {
                const iconName = this.isLayoutElement ? 'layout-settings' : 'module-settings';
                const icon = window.mw.top().app.iconService.icon(iconName);

                if (icon) {
                    return icon;
                }
            }

            // Fallback icons
            if (this.isLayoutElement) {
                // Layout settings icon - grid/layout icon
                return '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M3 3v8h8V3H3zm10 0v8h8V3h-8zM3 13v8h8v-8H3zm10 0v8h8v-8h-8z"/></svg>';
            } else {
                // Module settings icon - cog icon
                return '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 15.5A3.5 3.5 0 0 1 8.5 12A3.5 3.5 0 0 1 12 8.5a3.5 3.5 0 0 1 3.5 3.5a3.5 3.5 0 0 1-3.5 3.5m7.43-2.53c.04-.32.07-.64.07-.97c0-.33-.03-.66-.07-1l2.11-1.63c.19-.15.24-.42.12-.64l-2-3.46c-.12-.22-.39-.31-.61-.22l-2.49 1c-.52-.39-1.06-.73-1.69-.98l-.37-2.65A.506.506 0 0 0 14 2h-4c-.25 0-.46.18-.5.42l-.37 2.65c-.63.25-1.17.59-1.69.98l-2.49-1c-.22-.09-.49 0-.61.22l-2 3.46c-.13.22-.07.49.12.64L4.57 11c-.04.34-.07.67-.07 1c0 .33.03.65.07.97l-2.11 1.66c-.19.15-.25.42-.12.64l2 3.46c.12.22.39.3.61.22l2.49-1.01c.52.4 1.06.74 1.69.99l.37 2.65c.04.24.25.42.5.42h4c.25 0 .46-.18.5-.42l.37-2.65c.63-.26 1.17-.59 1.69-.99l2.49 1.01c.22.08.49 0 .61-.22l2-3.46c.12-.22.07-.49-.12-.64l-2.11-1.66Z"/></svg>';
            }
        }
    }
};
</script>
