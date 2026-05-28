<template>
    <div v-show="isTextElement">
        <div v-if="isTextElement" class="current-node-text-edit">
            <div
                :class="{ 'active': isEditing }"
                class="btn-icon live-edit-toolbar-buttons text-edit-button"
                title="Edit Text"
                @click="editCurrentNode"
                @mouseenter="onNodeHover"
            >
                <v-tooltip activator="parent" location="start">
                    Edit Text
                </v-tooltip>
                <span v-html="getTextEditIcon()"></span>
            </div>
        </div>
    </div>
</template>

<style scoped>
.current-node-text-edit {
    display: flex;
    align-items: center;
    padding: 5px 0;
}

.text-edit-button {
    height: 32px;
    width: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 4px;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    cursor: pointer;
    transition: all 0.2s ease;
    user-select: none;
}

.text-edit-button:hover {
    background: rgba(255, 255, 255, 0.2);
    border-color: rgba(255, 255, 255, 0.3);
    transform: scale(1.05);
}

.text-edit-button.active {

    background: rgba(33, 37, 41, 0.16);
    border-color: rgb(56, 54, 54);
    box-shadow: 0 0 4px rgb(70, 73, 84);

}

.text-edit-button:active {
    transform: scale(0.95);
}

.text-edit-button :deep(svg) {
    width: 24px;
    height: 24px;
    fill: currentColor;
}

.text-edit-button :deep(img) {
    width: 16px;
    height: 16px;
    object-fit: contain;
}
</style>

<script>
export default {
    name: 'CurrentNodeTextEditButton', data() {
        return {
            currentElement: null,
            isTextElement: false,
            isEditing: false,
            updateInterval: null,
            // Store event handler references for cleanup
            eventHandlers: {
                liveEditCanvasLoaded: null,
                canvasDocumentClick: null,
                editNodeRequest: null,
                editNodeEnd: null
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

            // Listen for editor events
            if (window.mw?.app?.editor) {
                this.eventHandlers.editNodeRequest = () => {
                    this.isEditing = true;
                };

                this.eventHandlers.editNodeEnd = () => {
                    this.isEditing = false;
                    this.updateCurrentNode(); // Refresh current node after editing ends
                };

                window.mw.app.editor.on('editNodeRequest', this.eventHandlers.editNodeRequest);
                window.mw.app.editor.on('editNodeEnd', this.eventHandlers.editNodeEnd);
            }
        }, cleanupEventListeners() {
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
                if (this.eventHandlers.editNodeRequest) {
                    window.mw.app.editor.off('editNodeRequest', this.eventHandlers.editNodeRequest);
                }
                if (this.eventHandlers.editNodeEnd) {
                    window.mw.app.editor.off('editNodeEnd', this.eventHandlers.editNodeEnd);
                }
            }

            // Clear event handler references
            this.eventHandlers = {
                liveEditCanvasLoaded: null,
                canvasDocumentClick: null,
                editNodeRequest: null,
                editNodeEnd: null
            };
        },

        updateCurrentNode() {
            try {


                const activeElement = mw.top().app.liveEdit.elementHandle.getTarget()
                      || window.mw.top().app.liveEdit.getSelectedNode()
                      || window.mw.top().app.liveEdit.getSelectedElementNode();

                if (activeElement !== this.currentElement) {
                    this.currentElement = activeElement;
                    this.checkIfTextElement();
                }
            } catch (error) {
                console.warn('Error updating current node:', error);
                this.currentElement = null;
                this.isTextElement = false;
            }
        }, checkIfTextElement() {
            if (!this.currentElement) {
                this.isTextElement = false;
                return;
            }

            // Use Microweber's built-in isEditable check first
            if (window.mw?.tools?.isEditable) {


                this.isTextElement = window.mw.tools.isEditable(this.currentElement);

                //check is editing
                if (this.isTextElement && this.currentElement.contentEditable === 'true') {
                    this.isEditing = true;
                } else {
                    this.isEditing = false;
                }


                return;
            }

        },


        editCurrentNode() {
            try {
                if (!this.currentElement) {
                    console.warn('No current element to edit');
                    return;
                }


                // Set the editing state
                this.isEditing = true;
                window.mw.app.editor.dispatch('editNodeRequest', this.currentElement);
            } catch (error) {
                console.error('Error editing current node:', error);
                this.isEditing = false;
            }
        },


        onNodeHover() {

        },

        getTextEditIcon() {
            // Try to get text edit icon from Microweber's icon service
            if (window.mw?.top()?.app?.iconService?.icon) {
                const icon =   window.mw.top().app.iconService.icon('edit');

                if (icon) {
                    return icon;
                }
            }

            // Fallback to a basic text edit icon
            return '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>';
        }
    }
};
</script>
