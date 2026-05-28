<template>
    <div v-if="currentLayoutModules.length > 0" class="current-layout-modules">

        <hr>

        <!--
            task-2026-05-05-1db9bd (Audit-#4) — drunk-designer external
            audit reported the right-rail `+` button on the inserted
            module had no label. The DOM had `title="Insert Module"`
            already, but the Vuetify `<v-tooltip>` overlay doesn't
            render reliably in this context (Vuetify CSS isn't loaded
            in the parent admin window — see vuetify-slider-in-mw-admin
            skill), so the audit observed an unlabelled icon. Adding
            `aria-label` and `role="button"` + `tabindex` so keyboard
            and AT users always get the affordance. Title remains as a
            native browser hover label.
        -->
        <div class="add-module-section">
            <div
                class="btn-icon add-module-button live-edit-toolbar-buttons"
                title="Insert content"
                aria-label="Insert content into this layout"
                role="button"
                tabindex="0"
                @click="insertModuleIntoLayout"
                @keydown.enter.prevent="insertModuleIntoLayout"
                @keydown.space.prevent="insertModuleIntoLayout"
            >
                <v-tooltip activator="parent" location="start">
                    Insert content
                </v-tooltip>
                <span>
                   <svg fill="currentColor" height="24px" viewBox="0 -960 960 960" width="24px"
                        xmlns="http://www.w3.org/2000/svg"><path d="M440-120v-320H120v-80h320v-320h80v320h320v80H520v320h-80Z"/></svg>
                </span>
            </div>
        </div>

        <!-- task-2026-05-28-0d7e2a / AI-1226 — module-settings-button lifted
             from 32×32px to min 44×44px for WCAG 2.5.5 touch target. -->
        <div class="modules-buttons">
            <div
                v-for="module in currentLayoutModules"
                :key="module.id"
                :class="{ 'active': module.isActive }"
                :title="module.title || module.type"
                :aria-label="`Configure ${module.title || module.type}`"
                role="button"
                tabindex="0"
                class="btn-icon module-settings-button live-edit-toolbar-buttons"
                @click="openModuleSettings(module)"
                @keydown.enter.prevent="openModuleSettings(module)"
                @keydown.space.prevent="openModuleSettings(module)"
                @mouseenter="onModuleHover(module)"
            >
                <v-tooltip activator="parent" location="start">
                    {{ module.title || module.type }}
                </v-tooltip>
                <span v-html="getModuleIcon(module)"></span>

                <!-- Background indicators for background modules -->
                <div v-if="module.type === 'background'" class="background-indicators">
                    <div
                        v-if="module.backgroundImage"
                        class="background-indicator background-image-indicator"
                        :style="{ backgroundImage: `url('${module.backgroundImage}')` }"
                    ></div>
                    <div
                        v-else-if="module.backgroundVideo"
                        class="background-indicator background-video-indicator"
                    >
                        <span class="video-icon">▶</span>
                    </div>
                    <div
                        v-else-if="module.backgroundColor"
                        class="background-indicator background-color-indicator"
                        :style="{ backgroundColor: module.backgroundColor }"
                    ></div>
                </div>
            </div>
        </div>

        <hr>

    </div>
</template>

<style scoped>
.current-layout-modules {
    display: flex;
    flex-direction: column;
    gap: 15px;
    padding: 15px 0;
    /* task-2026-05-28 — was rgba(255,255,255,0.1): invisible in light mode.
       Use a colour that is visible on both white and dark sidebar backgrounds. */
    border-bottom: 1px solid rgba(0, 0, 0, 0.12);
    margin-bottom: 10px;
}

.layout-modules-header {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 5px;
}

.layout-title {
    font-size: 11px;
    /* task-2026-05-28 — was rgba(255,255,255,0.7): invisible in light mode.
       Use currentColor with opacity for light and dark mode compatibility. */
    color: currentColor;
    opacity: 0.6;
    text-align: center;
    text-overflow: ellipsis;
    overflow: hidden;
    white-space: nowrap;
    max-width: 100%;
}

.modules-buttons {
    display: flex;
    flex-direction: column;
    gap: 15px;
    align-items: center;
}

.add-module-section {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 5px;
}

/* task-2026-05-28-0d7e2a / AI-1226 — "Insert content" button was 24px
   (SVG-natural height only). Lifted to 44px min for WCAG 2.5.5. */
.add-module-button {
    min-height: 44px;
    min-width: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* task-2026-05-28-0d7e2a / AI-1226 — Configure-X buttons were 32×32px.
   WCAG 2.5.5 requires 44×44px minimum touch target on mobile. */
.module-settings-button {
    min-height: 44px;
    min-width: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.2s ease;
    user-select: none;
    position: relative;
    overflow: visible;
}

/* task-2026-05-28 — hover visual feedback now comes from the inherited
   .live-edit-toolbar-buttons:hover background-color rule in live-edit-classes.css.
   Scale transform is inconsistent with the rest of the sidebar button styles. */
.module-settings-button:hover {
    /* no transform: scale — background-color change used instead */
}

.module-settings-button:active {
    transform: scale(0.95);
}

.module-settings-button :deep(svg) {
    width: 20px;
    height: 20px;
    fill: currentColor;
}

.module-settings-button :deep(img) {
    width: 20px;
    height: 20px;
    object-fit: contain;
}

/* Background indicators */
.background-indicators {
    position: absolute;
    top: -6px;
    right: -6px;
    pointer-events: none;
}

.background-indicator {
    width: 16px;
    height: 16px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 8px;
    color: white;
    overflow: hidden;
}

.background-image-indicator {
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
}

.background-video-indicator {
    background-color: #000;
}

.background-color-indicator {
    /* Background color will be set inline via style attribute */
    min-width: 16px;
    min-height: 16px;
}

.video-icon {
    font-size: 8px;
    line-height: 1;
}
</style>

<script>
export default {
    name: 'CurrentLayoutSettingsButtons',
    data() {
        return {
            currentLayoutModules: [],
            currentLayoutTitle: '',
            currentLayoutElement: null,
            updateInterval: null,
            activeModuleId: null
        };
    },
    mounted() {


        mw.app.on('ready', event => {


            this.setupEventListeners();
            this.updateCurrentLayout();

            // Update periodically to catch dynamic changes
            this.updateInterval = setInterval(() => {
                this.updateCurrentLayout();
            }, 2000);

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
            // Listen for layout changes
            if (window.mw?.app?.canvas) {
                window.mw.app.canvas.on('liveEditCanvasLoaded', () => {
                    this.updateCurrentLayout();
                });


                mw.top().app.on('moduleInserted', () => {
                    this.updateCurrentLayout();
                })
                mw.top().app.on('layoutCloned', () => {
                    this.updateCurrentLayout();
                })
                window.mw.app.canvas.on('canvasDocumentClick', () => {
                    // Delay update to allow for layout selection
                    setTimeout(() => {
                        this.updateCurrentLayout();
                        this.updateActiveModule();
                    }, 100);
                });
            }

            // Listen for module settings events
            if (window.mw?.app?.editor) {
                window.mw.app.editor.on('onModuleSettingsRequest', (module) => {
                    this.activeModuleId = module?.id || null;
                });

                // Listen for layout background changes
                window.mw.app.editor.on('onLayoutBackgroundChanged', () => {
                    this.updateCurrentLayout();
                });
            }

            // Listen for layout background changes from the layout background API
            if (window.mw?.top()?.app?.layoutBackground) {
                // Listen for background image changes
                const originalSetBackgroundImage = window.mw.top().app.layoutBackground.setBackgroundImage;
                if (originalSetBackgroundImage) {
                    window.mw.top().app.layoutBackground.setBackgroundImage = (...args) => {
                        originalSetBackgroundImage.apply(window.mw.top().app.layoutBackground, args);
                        setTimeout(() => {
                            this.updateCurrentLayout();
                            this.updateBackgroundIndicators();
                        }, 100);
                    };
                }

                // Listen for background color changes
                const originalSetBackgroundColor = window.mw.top().app.layoutBackground.setBackgroundColor;
                if (originalSetBackgroundColor) {
                    window.mw.top().app.layoutBackground.setBackgroundColor = (...args) => {
                        originalSetBackgroundColor.apply(window.mw.top().app.layoutBackground, args);
                        setTimeout(() => {
                            this.updateCurrentLayout();
                            this.updateBackgroundIndicators();
                        }, 100);
                    };
                }

                // Listen for background video changes
                const originalSetBackgroundVideo = window.mw.top().app.layoutBackground.setBackgroundVideo;
                if (originalSetBackgroundVideo) {
                    window.mw.top().app.layoutBackground.setBackgroundVideo = (...args) => {
                        originalSetBackgroundVideo.apply(window.mw.top().app.layoutBackground, args);
                        setTimeout(() => {
                            this.updateCurrentLayout();
                            this.updateBackgroundIndicators();
                        }, 100);
                    };
                }
            }


        },

        cleanupEventListeners() {
            // Clean up any event listeners if needed
        },

        updateCurrentLayout() {
            try {
                const layoutElement = this.getCurrentLayoutElement();

                if (layoutElement !== this.currentLayoutElement) {
                    this.currentLayoutElement = layoutElement;
                    this.extractLayoutModules(layoutElement);
                }
            } catch (error) {
                console.warn('Error updating current layout:', error);
            }
        },

        updateActiveModule() {
            // Update which module is currently active/selected
            const activeModule = window.mw?.top()?.app?.liveEdit?.getSelectedModuleNode();
            this.activeModuleId = activeModule?.id || null;

            // Update the active state in our modules list
            this.currentLayoutModules.forEach(module => {
                module.isActive = module.id === this.activeModuleId;
            });
        },

        getCurrentLayoutElement() {
            // Try to get the currently selected layout
            let layoutElement = window.mw.top().app.liveEdit.getSelectedLayoutNode();

            return layoutElement;
        },

        extractLayoutModules(layoutElement) {
            this.currentLayoutModules = [];
            this.currentLayoutTitle = '';

            if (!layoutElement) {
                return;
            }

            // Get layout title
            this.currentLayoutTitle = this.getLayoutTitle(layoutElement);

            // Find only direct child modules within this layout (not nested modules inside other modules)
            const modules = this.getDirectChildModules(layoutElement);
            const moduleData = [];

            modules.forEach((moduleElement, index) => {
                // Check if module is inaccessible
                const isInaccessible = this.isModuleInaccessible(moduleElement);
                if (isInaccessible) {
                    return; // Skip inaccessible modules
                }

                const moduleType = moduleElement.getAttribute('data-type') || moduleElement.getAttribute('type');
                const moduleId = moduleElement.getAttribute('id') || `module-${index}`;
                const moduleTitle = this.getModuleTitle(moduleElement, moduleType);

                if (moduleType && this.isEditableModule(moduleType)) {
                    // Special handling for background modules - only show if they have actual content
                    if (moduleType.toLowerCase() === 'background') {
                        if (!this.hasBackgroundContent(moduleElement)) {
                            return; // Skip background modules without content
                        }
                    }

                    // Get background information for background modules
                    const backgroundInfo = this.getBackgroundInfo(moduleElement, moduleType);

                    moduleData.push({
                        id: moduleId,
                        type: moduleType,
                        title: moduleTitle,
                        element: moduleElement,
                        isActive: moduleId === this.activeModuleId,
                        ...backgroundInfo
                    });
                }
            });

            // Remove duplicates and limit to most important modules
            this.currentLayoutModules = this.deduplicateModules(moduleData).slice(0, 8);
        },

        getLayoutTitle(layoutElement) {
            // Try to get layout name from various sources
            const layoutName = layoutElement.getAttribute('data-layout') ||
                layoutElement.getAttribute('data-layout-name') ||
                layoutElement.getAttribute('data-title') ||
                layoutElement.querySelector('.layout-title')?.textContent;

            if (layoutName) {
                return layoutName.length > 15 ? layoutName.substring(0, 15) + '...' : layoutName;
            }            return 'Layout';
        },

        getDirectChildModules(layoutElement) {
            // Get only direct child modules, not nested modules inside other modules
            const allModules = layoutElement.querySelectorAll('.module[data-type]:not([data-type=""]):not(.module-layouts)');
            const directChildModules = [];

            allModules.forEach(moduleElement => {
                // Check if this module is a direct child of the layout
                // by verifying that there's no other module between this one and the layout
                let parent = moduleElement.parentElement;
                let isDirectChild = false;

                while (parent && parent !== layoutElement) {
                    // If we encounter another module element on the way up,
                    // this means our module is nested inside another module
                    if (parent.classList.contains('module') &&
                        parent.hasAttribute('data-type') &&
                        parent.getAttribute('data-type') !== '' &&
                        !parent.classList.contains('module-layouts')) {
                        isDirectChild = false;
                        break;
                    }
                    parent = parent.parentElement;
                }

                // If we reached the layout without encountering another module,
                // this is a direct child
                if (parent === layoutElement) {
                    isDirectChild = true;
                }

                if (isDirectChild) {
                    directChildModules.push(moduleElement);
                }
            });

            return directChildModules;
        },

        getModuleTitle(moduleElement, moduleType) {
            // Try to get module info from Microweber's module system first
            if (window.mw?.top()?.app?.modules) {
                const info = window.mw.top().app.modules.getModuleInfo(moduleType);
                if (info && info.name) {
                    return info.name;
                }
            }

            // Try to get a meaningful title for the module
            const title = moduleElement.getAttribute('data-title') ||
                moduleElement.getAttribute('data-module-title') ||
                moduleElement.getAttribute('data-mw-title') ||
                moduleElement.querySelector('.module-title')?.textContent ||
                moduleElement.querySelector('h1, h2, h3, h4')?.textContent;

            if (title) {
                return title.length > 20 ? title.substring(0, 20) + '...' : title;
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

        getBackgroundInfo(moduleElement, moduleType) {
            // Only process background modules
            if (moduleType.toLowerCase() !== 'background') {
                return {};
            }

            try {
                // Get the layout element that contains the background
                const layoutElement = this.getCurrentLayoutElement();
                if (!layoutElement) return {};

                // Find background elements within the layout
                const bg = layoutElement.querySelector('.mw-layout-background-block');
                if (!bg) return {};

                const bgNode = bg.querySelector('.mw-layout-background-node');
                const bgOverlay = bg.querySelector('.mw-layout-background-overlay');

                let backgroundInfo = {};

                // Check for background image
                if (bgNode && window.mw?.top()?.app?.layoutBackground) {
                    const bgImage = window.mw.top().app.layoutBackground.getBackgroundImage(bgNode);
                    if (bgImage && bgImage !== 'none' && bgImage.trim() !== '') {
                        backgroundInfo.backgroundImage = bgImage;
                    }

                    // Check for background video
                    const bgVideo = window.mw.top().app.layoutBackground.getBackgroundVideo(bgNode);
                    if (bgVideo && bgVideo !== 'none' && bgVideo.trim() !== '') {
                        backgroundInfo.backgroundVideo = bgVideo;
                    }
                }

                // Check for background color
                if (bgOverlay && window.mw?.top()?.app?.layoutBackground) {
                    const bgColor = window.mw.top().app.layoutBackground.getBackgroundColor(bgOverlay);
                    if (bgColor && bgColor !== 'rgba(0, 0, 0, 0)' && bgColor !== 'transparent' && bgColor.trim() !== '') {
                        backgroundInfo.backgroundColor = bgColor;
                    }
                }

                return backgroundInfo;
            } catch (error) {
                console.warn('Error getting background info:', error);
                return {};
            }
        }, isEditableModule(moduleType) {
            // Filter out non-editable or system modules
            const excludedTypes = [
                'layouts',
                'layout',
                'text',
                'spacer',
                'divider'
            ];

            // Allow background modules to be processed (they will be filtered later by hasBackgroundContent)
            return moduleType && !excludedTypes.includes(moduleType.toLowerCase());
        },

        hasBackgroundContent(moduleElement) {
            // Check if background module has actual content (image, video, or color)
            if (!moduleElement) return false;

            try {
                // Get the layout element that contains the background
                const layoutElement = this.getCurrentLayoutElement();
                if (!layoutElement) return false;

                // Find background elements within the layout
                const bg = layoutElement.querySelector('.mw-layout-background-block');
                if (!bg) return false;

                const bgNode = bg.querySelector('.mw-layout-background-node');
                const bgOverlay = bg.querySelector('.mw-layout-background-overlay');

                // Check for background image
                if (bgNode && window.mw?.top()?.app?.layoutBackground) {
                    const bgImage = window.mw.top().app.layoutBackground.getBackgroundImage(bgNode);
                    if (bgImage && bgImage !== 'none' && bgImage.trim() !== '') {
                        return true;
                    }

                    // Check for background video
                    const bgVideo = window.mw.top().app.layoutBackground.getBackgroundVideo(bgNode);
                    if (bgVideo && bgVideo !== 'none' && bgVideo.trim() !== '') {
                        return true;
                    }
                }

                // Check for background color
                if (bgOverlay && window.mw?.top()?.app?.layoutBackground) {
                    const bgColor = window.mw.top().app.layoutBackground.getBackgroundColor(bgOverlay);
                    if (bgColor && bgColor !== 'rgba(0, 0, 0, 0)' && bgColor !== 'transparent' && bgColor.trim() !== '') {
                        return true;
                    }
                }

                return false;
            } catch (error) {
                console.warn('Error checking background content:', error);
                return false;
            }
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

        deduplicateModules(modules) {
            const seen = new Set();
            return modules.filter(module => {
                const key = `${module.type}-${module.title}`;
                if (seen.has(key)) {
                    return false;
                }
                seen.add(key);
                return true;
            });
        },

        getModuleIcon(module) {
            // Use the new getModuleIcon service function directly
            if (window.mw?.top()?.app?.modules) {
                return window.mw.top().app.modules.getModuleIcon(module.type);
            }

            // Fallback to default icon
            return '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>';
        }, openModuleSettings(module) {
            try {
                // Use Microweber's module settings system
                if (window.mw?.top()?.app?.editor?.dispatch && module.element) {
                    // Set the active module ID
                    this.activeModuleId = module.id;
                    this.updateActiveModule();
                    mw.top().app.canvas.getWindow().mw.tools.scrollTo(module.element, undefined, 100);

                    // Close any in-flight layout-settings or module-settings
                    // panel before we open the new one. Several modules
                    // (notably Background) reuse the very same image/
                    // video/color form the parent Layout Settings drawer
                    // shows, so without an explicit close + re-open the
                    // user sees no visible difference and assumes the
                    // click was a no-op (see task-2026-04-29-6c8e8c).
                    // A 1-tick delay between the close-end and the new
                    // request gives the slideOver-close transition time
                    // to start, which is what the user perceives as
                    // "the panel changed".
                    try {
                        window.mw.top().app.editor.dispatch('onModuleSettingsEnd');
                        window.mw.top().app.editor.dispatch('onLayoutSettingsEnd');
                    } catch (closeErr) {
                        // Non-fatal — listeners may not exist.
                    }

                    // Dispatch the module settings request — wrap in a
                    // microtask so any close-driven DOM updates above
                    // commit first.
                    setTimeout(() => {
                        window.mw.top().app.editor.dispatch('onModuleSettingsRequest', module.element);
                    }, 50);
                }  else {
                    console.warn('Module settings method not available');
                }
            } catch (error) {
                console.error('Error opening module settings:', error);
            }
        }, onModuleHover(module) {
            try {
                // Set the target element handle on hover
                if (window.mw?.top()?.app?.liveEdit?.elementHandle?.set && module.element) {

                    if(module.type !== 'layouts' && module.type != 'background'){
                        window.mw.top().app.liveEdit.elementHandle.set(module.element, true);

                    }



                }
            } catch (error) {
                console.error('Error setting element handle on hover:', error);
            }
        },

        insertModuleIntoLayout() {
            try {
                // Get the current layout element as the target

                const targetElement =
                    window.mw.top().app.liveEdit.getSelectedNode()
                    || window.mw.top().app.liveEdit.getSelectedElementNode()
                    || mw.top().app.liveEdit.elementHandle.getTarget()
                    || this.getCurrentLayoutElement();


                console.log('Inserting module into element:', targetElement);
                if (targetElement && window.mw?.app?.editor?.dispatch) {
                    // Dispatch the insert module request with the layout as target
                    window.mw.app.editor.dispatch('insertModuleRequest', targetElement);
                } else {
                    console.warn('Cannot insert module: target element or editor not available');
                }
            } catch (error) {
                console.error('Error inserting module into layout:', error);
            }
        },

        updateBackgroundIndicators() {
            // Update background information for existing background modules
            this.currentLayoutModules.forEach(module => {
                if (module.type === 'background' && module.element) {
                    const backgroundInfo = this.getBackgroundInfo(module.element, module.type);
                    Object.assign(module, backgroundInfo);
                }
            });
        },
    }
};
</script>
