import axios from 'axios';

export const Modules = {

    modulesListData: null,


    list:   function (cb) {

        if (this.modulesListData) {
            if (cb) {
                cb.call(undefined, this.modulesListData)
            }
            return this.modulesListData;
        }

        axios.get(route('api.module.list') + '?layout_type=module')
        .then((response) => {

            this.modulesListData = response.data;


            if (cb) {
                cb.call(undefined, this.modulesListData)
            }
        });



    },
    modulesSkinsData: [],
    getSkins: async function (module) {
        if (this.modulesSkinsData[module]) {
            return this.modulesSkinsData[module];
        }

        await axios.get(route('api.module.getSkins') + '?module=' + module)
            .then((response) => {
                this.modulesSkinsData[module] = response.data;
            });

        if (this.modulesSkinsData[module]) {
            return this.modulesSkinsData[module];
        }

    },


    getModuleInfo: function (module) {
        if (this.modulesListData && this.modulesListData.modules) {
            var foundModule = this.modulesListData.modules.find(function (element) {
                return element.module == module;
            });

            if (foundModule) {
                // Process icon if exists
                if (foundModule.icon) {
                    if (foundModule.icon.startsWith('data:image/svg+xml;base64,')) {
                        foundModule.processedIcon = `<img src="${foundModule.icon}" alt="${foundModule.name || module}" style="width: 24px; height: 24px;" />`;
                    } else if (foundModule.icon.includes('<svg')) {
                        foundModule.processedIcon = foundModule.icon;
                    } else if (foundModule.icon.startsWith('http') || foundModule.icon.startsWith('/')) {
                        foundModule.processedIcon = `<img src="${foundModule.icon}" alt="${foundModule.name || module}" style="width: 16px; height: 16px;" />`;
                    } else {
                        foundModule.processedIcon = foundModule.icon;
                    }
                } else {
                    // Default icon if none provided — use a cog/settings icon
                    // so module buttons look like settings affordances rather
                    // than the star that was here before.
                    foundModule.processedIcon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.09.63-.09.94s.02.64.07.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6 3.6 1.62 3.6 3.6-1.62 3.6-3.6 3.6z"/></svg>';
                }
            }

            return foundModule;
        }
    },

    getModuleIcon: function (module) {
        const moduleType = typeof module === 'string' ? module : module.type;
        const info = this.getModuleInfo(moduleType);

        if (info && info.processedIcon) {
            return info.processedIcon;
        }

        // Default icon if none found — cog/settings, not a star
        return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.09.63-.09.94s.02.64.07.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6 3.6 1.62 3.6 3.6-1.62 3.6-3.6 3.6z"/></svg>';
    },

    getModuleInlineViewData: function (moduleId) {
        try {

            const canvasDoc = window.mw.top().app.canvas.getDocument();

            let moduleElement;
            let actualModuleId;

            // Check if moduleId is a DOM node or a string
            if (typeof moduleId === 'string') {
                // Find the module element in the canvas document
                moduleElement = canvasDoc.getElementById(moduleId);
                actualModuleId = moduleId;
            } else if (moduleId && moduleId.nodeType === Node.ELEMENT_NODE) {
                // moduleId is a DOM node
                moduleElement = moduleId;
                actualModuleId = moduleElement.id;
            } else {
                console.warn('Invalid moduleId parameter: must be a string or DOM element');
                return null;
            }

            if (!moduleElement) {
                console.warn(`Module element with ID ${actualModuleId} not found`);
                return null;
            }

            // Look for script tag with specific data-module-settings-id attribute
            const scriptTag = moduleElement.querySelector(`script[data-module-settings-id="${actualModuleId}"]`);
            if (!scriptTag) {
                console.warn(`Script tag with data-module-settings-id="${actualModuleId}" not found in module ${actualModuleId}`);
                return null;
            }

            const scriptContent = scriptTag.innerHTML;

            const encodedData =scriptContent;
            const decodedData = encodedData
                .replace(/&quot;/g, '"')
                .replace(/&amp;/g, '&')
                .replace(/&lt;/g, '<')
                .replace(/&gt;/g, '>')
                .replace(/&#039;/g, "'");

            try {
                const parsedData = JSON.parse(decodedData);
                return parsedData;
            } catch (parseError) {
                console.error(`Failed to parse JSON data for module ${actualModuleId}:`, parseError);
                return null;
            }

        } catch (error) {
            console.error(`Error extracting module data for ${actualModuleId}:`, error);
            return null;
        }
    },

}


Modules.list();
