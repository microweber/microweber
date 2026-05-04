<style>


.mw-live-edit-right-sidebar-wrapper.mw-live-edit-right-sidebar-template-sidebar .mw-live-edit-advanced-settings-popup svg {


    fill: currentColor !important;
    margin: 0 !important;
    margin-inline: 0 !important;

    width: 25px !important;
    max-width: none !important;
    height: auto !important;
    max-height: none !important;
}


.mw-live-edit-right-sidebar-wrapper.mw-live-edit-right-sidebar-template-sidebar .mw-live-edit-advanced-settings-popup,
.mw-live-edit-right-sidebar-wrapper.mw-live-edit-right-sidebar-template-sidebar .btn-icon.live-edit-toolbar-buttons {
    height: 39px;
    width: 39px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto !important;
    gap: 4px;
    user-select: none;

    svg {
        width: 22px;
    }
}


/* Allow titles to show in the tools panel popup - higher specificity */
.advanced-popup .tools-panel .mw-live-edit-advanced-settings-popup svg + span {
    display: inline !important;
}

.mw-live-edit-right-sidebar-wrapper.mw-live-edit-right-sidebar-template-sidebar ul {
    display: flex;
    flex-direction: column;
    align-items: center !important;
    justify-content: start !important;
    width: 100%;
    gap: 22px !important;
}

.mw-live-edit-right-sidebar-wrapper.mw-live-edit-right-sidebar-template-sidebar {
    position: absolute;
    right: 0;
    bottom: 0;
    width: 50px;
    height: calc(100vh - var(--toolbar-height));
    transition: transform .5s, right .5s;
    display: flex;
    flex-direction: column;
    align-items: center !important;
    justify-content: start !important;
    max-height: none !important;
    gap: 22px !important;
    padding: 10px 0 !important;
    margin: 0 !important;
    border-radius: 0 !important;
    z-index: 2;
    background-color: var(--tblr-bg-surface, #ffffff);
    border-left: 1px solid rgba(0, 0, 0, 0.08);
}

html.dark .mw-live-edit-right-sidebar-wrapper.mw-live-edit-right-sidebar-template-sidebar {
    background-color: var(--tblr-bg-surface, #182433);
    border-left: 1px solid rgba(255, 255, 255, 0.1);
}

.live-edit-gui-editor-opened .mw-live-edit-right-sidebar-wrapper.mw-live-edit-right-sidebar-template-sidebar {
    right: var(--sidebar-end-size);
}

.advanced-enter-active,
.advanced-leave-active {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.advanced-enter-from,
.advanced-leave-to {
    opacity: 0;
    transform: scale(0.9) translateY(10px);
}

.advanced-popup {
    position: absolute;
    bottom: 70px;
    right: 60px;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1),
    0 4px 12px rgba(0, 0, 0, 0.08),
    0 0 0 1px rgba(255, 255, 255, 0.2);
    z-index: 1000;
    min-width: 240px;
    border: 1px solid rgba(0, 0, 0, 0.08);
    transform-origin: bottom right;
}

.dark .advanced-popup {
    background: rgba(30, 30, 30, 0.95);
    border: 1px solid rgba(255, 255, 255, 0.1);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4),
    0 4px 12px rgba(0, 0, 0, 0.3),
    0 0 0 1px rgba(255, 255, 255, 0.1);
}

.advanced-popup-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
}

.advanced-popup-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 16px 12px;
    border-radius: 12px;
    background: rgba(0, 0, 0, 0.03);
    border: 1px solid rgba(0, 0, 0, 0.06);
    cursor: pointer;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    text-align: center;
    min-height: 80px;
    position: relative;
    overflow: hidden;
}

.advanced-popup-item:hover {
    background: rgba(0, 0, 0, 0.08);
    border-color: rgba(0, 0, 0, 0.12);
    transform: translateY(-2px) scale(1.02);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
}

.dark .advanced-popup-item {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.dark .advanced-popup-item:hover {
    background: rgba(255, 255, 255, 0.1);
    border-color: rgba(255, 255, 255, 0.2);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
}

.advanced-popup-item svg {
    width: 24px;
    height: 24px;
    margin-bottom: 8px;
    fill: #374151;
    transition: all 0.2s ease;
}

.dark .advanced-popup-item svg {
    fill: #e5e7eb;
}

.advanced-popup-item:hover svg {
    fill: #1f2937;
    transform: scale(1.1);
}

.dark .advanced-popup-item:hover svg {
    fill: #ffffff;
}

.advanced-popup-item span {
    font-size: 12px;
    color: #374151;
    font-weight: 500;
    transition: color 0.2s ease;
}

.dark .advanced-popup-item span {
    color: #e5e7eb;
}

.advanced-popup-item:hover span {
    color: #1f2937;
}

.dark .advanced-popup-item:hover span {
    color: #ffffff;
}

.tools-panel ul {
    display: flex !important;
    flex-direction: column !important;
    gap: 8px !important;
    padding: 0 !important;
    margin: 0 !important;
    list-style: none !important;
}

.tools-panel li {
    width: 100% !important;
}

.tools-panel .mw-live-edit-advanced-settings-popup {
    display: flex !important;
    align-items: center !important;
    justify-content: flex-start !important;
    width: 100% !important;
    padding: 14px 16px !important;
    border-radius: 10px !important;
    background: rgba(0, 0, 0, 0.03) !important;
    border: 1px solid rgba(0, 0, 0, 0.06) !important;
    color: #374151 !important;
    text-decoration: none !important;
    font-size: 14px !important;
    font-weight: 500 !important;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
    cursor: pointer !important;
    position: relative !important;
    overflow: hidden !important;
    min-height: 48px !important;
}

.dark .tools-panel .mw-live-edit-advanced-settings-popup {
    background: rgba(255, 255, 255, 0.05) !important;
    border: 1px solid rgba(255, 255, 255, 0.1) !important;
    color: #e5e7eb !important;
}

.tools-panel .mw-live-edit-advanced-settings-popup:hover {
    background: rgba(0, 0, 0, 0.08) !important;
    border-color: rgba(0, 0, 0, 0.12) !important;
    transform: translateY(-1px) scale(1.01) !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
    color: #1f2937 !important;
}

.dark .tools-panel .mw-live-edit-advanced-settings-popup:hover {
    background: rgba(255, 255, 255, 0.1) !important;
    border-color: rgba(255, 255, 255, 0.2) !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3) !important;
    color: #ffffff !important;
}

.tools-panel .mw-live-edit-advanced-settings-popup svg {
    width: 20px !important;
    height: 20px !important;
    margin-right: 12px !important;
    fill: #6b7280 !important;
    flex-shrink: 0 !important;
    transition: all 0.2s ease !important;
}

.dark .tools-panel .mw-live-edit-advanced-settings-popup svg {
    fill: #9ca3af !important;
}

.tools-panel .mw-live-edit-advanced-settings-popup:hover svg {
    fill: #374151 !important;
    transform: scale(1.05) !important;
}

.dark .tools-panel .mw-live-edit-advanced-settings-popup:hover svg {
    fill: #e5e7eb !important;
}

/* Add a subtle ripple effect */
.tools-panel .mw-live-edit-advanced-settings-popup::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: radial-gradient(circle, rgba(0, 0, 0, 0.1) 0%, transparent 70%);
    opacity: 0;
    transform: scale(0);
    transition: all 0.3s ease;
}

.dark .tools-panel .mw-live-edit-advanced-settings-popup::before {
    background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
}

.tools-panel .mw-live-edit-advanced-settings-popup:active::before {
    opacity: 1;
    transform: scale(1);
}

.advanced-popup-item::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: radial-gradient(circle, rgba(0, 0, 0, 0.1) 0%, transparent 70%);
    opacity: 0;
    transform: scale(0);
    transition: all 0.3s ease;
}

.dark .advanced-popup-item::before {
    background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
}

.advanced-popup-item:active::before {
    opacity: 1;
    transform: scale(1);
}

</style>


<template>


    <div :class="'mw-live-edit-right-sidebar-template-' + template" class="mw-live-edit-right-sidebar-wrapper me-2 ">


        <!-- task-2026-05-04-39767a (UX P1-5) — these toolbar
             icon-only buttons used to be raw <div>/<span> with
             v-tooltip but no role / tabindex / aria-label, so
             screen-reader and keyboard-only users got nothing.
             Adding role=button + tabindex=0 + aria-label
             (mirrors the v-tooltip text) + keydown.enter/space
             so they activate via keyboard. WCAG 2.1.1
             Keyboard, 4.1.2 Name/Role/Value. -->
        <div v-if="insertLayoutVisible"
             class="btn-icon live-edit-toolbar-buttons"
             role="button"
             tabindex="0"
             aria-label="Insert layout"
             v-on:click="handleInsertLayout()"
             v-on:keydown.enter.prevent="handleInsertLayout()"
             v-on:keydown.space.prevent="handleInsertLayout()">
            <v-tooltip activator="parent" location="start">
                <Lang>Insert layout</Lang>
            </v-tooltip>
            <span v-html="iconInsertlayout" aria-hidden="true"></span>
        </div>


        <span v-if="canShowSettingsCustomize"
              :class="{'live-edit-right-sidebar-active': buttonIsActive && !buttonIsActiveStyleEditor }"
              class="btn-icon live-edit-toolbar-buttons live-edit-toolbar-button-css-editor-toggle"
              role="button"
              tabindex="0"
              aria-label="Template settings"
              :aria-pressed="buttonIsActive && !buttonIsActiveStyleEditor"
              v-on:click="toggle('template-settings')"
              v-on:keydown.enter.prevent="toggle('template-settings')"
              v-on:keydown.space.prevent="toggle('template-settings')">
            <v-tooltip activator="parent" location="start"><Lang>Template settings</Lang></v-tooltip>

            <svg fill="currentColor" height="22" viewBox="96 96 960 960" width="22" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path
                d="M480 976q-82 0-155-31.5t-127.5-86Q143 804 111.5 731T80 576q0-83 32.5-156t88-127Q256 239 330 207.5T488 176q80 0 151 27.5t124.5 76q53.5 48.5 85 115T880 538q0 115-70 176.5T640 776h-74q-9 0-12.5 5t-3.5 11q0 12 15 34.5t15 51.5q0 50-27.5 74T480 976Zm0-400Zm-220 40q26 0 43-17t17-43q0-26-17-43t-43-17q-26 0-43 17t-17 43q0 26 17 43t43 17Zm120-160q26 0 43-17t17-43q0-26-17-43t-43-17q-26 0-43 17t-17 43q0 26 17 43t43 17Zm200 0q26 0 43-17t17-43q0-26-17-43t-43-17q-26 0-43 17t-17 43q0 26 17 43t43 17Zm120 160q26 0 43-17t17-43q0-26-17-43t-43-17q-26 0-43 17t-17 43q0 26 17 43t43 17ZM480 896q9 0 14.5-5t5.5-13q0-14-15-33t-15-57q0-42 29-67t71-25h70q66 0 113-38.5T800 538q0-121-92.5-201.5T488 256q-136 0-232 93t-96 227q0 133 93.5 226.5T480 896Z"/></svg>
        </span>


        <div v-if="canShowSettingsCustomize"
             :class="{'live-edit-right-sidebar-active': !buttonIsActive && buttonIsActiveStyleEditor }"
             class="btn-icon live-edit-toolbar-buttons live-edit-toolbar-button-css-editor-toggle"
             role="button"
             tabindex="0"
             aria-label="Design"
             :aria-pressed="!buttonIsActive && buttonIsActiveStyleEditor"
             v-on:click="toggle('style-editor')"
             v-on:keydown.enter.prevent="toggle('style-editor')"
             v-on:keydown.space.prevent="toggle('style-editor')">
            <v-tooltip activator="parent" location="start">
                <Lang>Design</Lang>
            </v-tooltip>
            <svg fill="currentColor"
                 height="22" viewBox="0 -960 960 960"
                 width="22" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path
                    d="M480-120q-133 0-226.5-92T160-436q0-65 25-121.5T254-658l226-222 226 222q44 44 69 100.5T800-436q0 132-93.5 224T480-120ZM242-400h474q12-72-13.5-123T650-600L480-768 310-600q-27 26-53 77t-15 123Z"/>
            </svg>
        </div>

        <div v-if="canShowSettingsCustomize" :class="{'live-edit-right-sidebar-active': buttonIsActiveQuickEdit }"
             class="btn-icon live-edit-toolbar-buttons"
             role="button"
             tabindex="0"
             aria-label="Quick AI edit"
             :aria-pressed="buttonIsActiveQuickEdit"
             v-on:click="handleQuickEdit()"
             v-on:keydown.enter.prevent="handleQuickEdit()"
             v-on:keydown.space.prevent="handleQuickEdit()">
            <v-tooltip activator="parent" location="start">
                <Lang>Quick AI edit</Lang>
            </v-tooltip>
            <svg clip-rule="evenodd" fill="currentColor" fill-rule="evenodd" height="22px"
                 image-rendering="optimizeQuality" shape-rendering="geometricPrecision"
                 text-rendering="geometricPrecision" viewBox="0 0 512 472.403" width="22px"
                 xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M144.317 85.269h223.368c15.381 0 29.391 6.325 39.567 16.494l.025-.024c10.163 10.164 16.477 24.193 16.477 39.599v189.728c0 15.401-6.326 29.425-16.485 39.584-10.159 10.159-24.183 16.484-39.584 16.484H144.317c-15.4 0-29.437-6.313-39.601-16.476-10.152-10.152-16.47-24.167-16.47-39.592V141.338c0-15.374 6.306-29.379 16.463-39.558l.078-.078c10.178-10.139 24.168-16.433 39.53-16.433zm59.98 204.329h-39.825l30.577-117.964h58.32l30.577 117.964h-39.825l-3.051-18.686h-33.725l-3.048 18.686zm15.645-81.726l-5.801 33.032h19.945l-5.61-33.032h-8.534zm74.007 81.726V171.634h37.749v117.964h-37.749zm161.348-35.797v30.763c0 3.165 2.587 5.751 5.752 5.751h45.199c3.165 0 5.752-2.586 5.752-5.751v-30.763c0-3.165-2.587-5.752-5.752-5.752h-45.199c-3.165 0-5.752 2.587-5.752 5.752zm0-70.639v30.762c0 3.163 2.587 5.752 5.752 5.752h45.199c3.165 0 5.752-2.589 5.752-5.752v-30.762c0-3.168-2.587-5.752-5.752-5.752h-45.199c-3.165 0-5.752 2.584-5.752 5.752zm0 141.278v30.763c0 3.165 2.587 5.752 5.752 5.752h45.199c3.165 0 5.752-2.587 5.752-5.752V324.44c0-3.165-2.587-5.751-5.752-5.751h-45.199c-3.165 0-5.752 2.586-5.752 5.751zm0-211.92v30.763c0 3.164 2.587 5.751 5.752 5.751h45.199c3.165 0 5.752-2.587 5.752-5.751V112.52c0-3.165-2.587-5.752-5.752-5.752h-45.199c-3.165 0-5.752 2.587-5.752 5.752zM56.703 253.801v30.763c0 3.165-2.587 5.751-5.752 5.751H5.752c-3.165 0-5.752-2.586-5.752-5.751v-30.763c0-3.165 2.587-5.752 5.752-5.752h45.199c3.165 0 5.752 2.587 5.752 5.752zm0-70.639v30.762c0 3.163-2.587 5.752-5.752 5.752H5.752c-3.165 0-5.752-2.589-5.752-5.752v-30.762c0-3.168 2.587-5.752 5.752-5.752h45.199c3.165 0 5.752 2.584 5.752 5.752zm0 141.278v30.763c0 3.165-2.587 5.752-5.752 5.752H5.752c-3.165 0-5.752-2.587-5.752-5.752V324.44c0-3.165 2.587-5.751 5.752-5.751h45.199c3.165 0 5.752 2.586 5.752 5.751zm0-211.92v30.763c0 3.164-2.587 5.751-5.752 5.751H5.752c-3.165 0-5.752-2.587-5.752-5.751V112.52c0-3.165 2.587-5.752 5.752-5.752h45.199c3.165 0 5.752 2.587 5.752 5.752zM346.579 415.7h30.763c3.162 0 5.751 2.587 5.751 5.752v45.199c0 3.165-2.589 5.752-5.751 5.752h-30.763c-3.167 0-5.752-2.587-5.752-5.752v-45.199c0-3.165 2.585-5.752 5.752-5.752zm-70.642 0H306.7c3.165 0 5.751 2.587 5.751 5.752v45.199c0 3.165-2.586 5.752-5.751 5.752h-30.763c-3.165 0-5.752-2.587-5.752-5.752v-45.199c0-3.165 2.587-5.752 5.752-5.752zm-70.639 0h30.762c3.165 0 5.752 2.587 5.752 5.752v45.199c0 3.165-2.587 5.752-5.752 5.752h-30.762c-3.165 0-5.752-2.587-5.752-5.752v-45.199c0-3.165 2.587-5.752 5.752-5.752zm-70.64 0h30.763c3.165 0 5.752 2.587 5.752 5.752v45.199c0 3.165-2.587 5.752-5.752 5.752h-30.763c-3.165 0-5.751-2.587-5.751-5.752v-45.199c0-3.165 2.586-5.752 5.751-5.752zM346.579 0h30.763c3.162 0 5.751 2.587 5.751 5.752v45.199c0 3.165-2.589 5.752-5.751 5.752h-30.763c-3.167 0-5.752-2.587-5.752-5.752V5.752c0-3.165 2.585-5.752 5.752-5.752zm-70.642 0H306.7c3.165 0 5.751 2.587 5.751 5.752v45.199c0 3.165-2.586 5.752-5.751 5.752h-30.763c-3.165 0-5.752-2.587-5.752-5.752V5.752c0-3.165 2.587-5.752 5.752-5.752zm-70.639 0h30.762c3.165 0 5.752 2.587 5.752 5.752v45.199c0 3.165-2.587 5.752-5.752 5.752h-30.762c-3.165 0-5.752-2.587-5.752-5.752V5.752c0-3.165 2.587-5.752 5.752-5.752zm-70.64 0h30.763c3.165 0 5.752 2.587 5.752 5.752v45.199c0 3.165-2.587 5.752-5.752 5.752h-30.763c-3.165 0-5.751-2.587-5.751-5.752V5.752c0-3.165 2.586-5.752 5.751-5.752zm233.027 111.097H144.317a30.11 30.11 0 00-21.35 8.844l-.049.049a30.117 30.117 0 00-8.844 21.348v189.728c0 8.292 3.414 15.847 8.9 21.333 5.494 5.493 13.058 8.907 21.343 8.907h223.368c8.273 0 15.833-3.421 21.326-8.914s8.915-13.053 8.915-21.326V141.338c0-8.283-3.414-15.848-8.908-21.341v-.049c-5.454-5.456-13.006-8.851-21.333-8.851z"
                    fill-rule="nonzero"/>
            </svg>
        </div>


        <div>
            <!--            <CurrentNodeTextEditButton></CurrentNodeTextEditButton>
                        <CurrentNodeModuleEditButton></CurrentNodeModuleEditButton>
                        <CurrentLayoutSettingsButtons></CurrentLayoutSettingsButtons>-->
            <CurrentContextMenu></CurrentContextMenu>
        </div>


        <div style="margin-top:auto;">
            <Transition name="advanced">
                <div v-if="advanced" class="advanced-popup">
                    <!-- Tools Panel - Shows by default when advanced popup opens -->
                    <div class="tools-panel">
                        <ToolsButtons template="menu"></ToolsButtons>
                    </div>
                </div>
            </Transition>


            <div :class="{'live-edit-right-sidebar-active': buttonIsActiveQuickEdit }"
                 class="btn-icon live-edit-toolbar-buttons live-edit-toolbar-button-advanced"
                 role="button"
                 tabindex="0"
                 aria-label="Advanced"
                 :aria-pressed="advanced"
                 v-on:click="handleAdvanced()"
                 v-on:keydown.enter.prevent="handleAdvanced()"
                 v-on:keydown.space.prevent="handleAdvanced()">
                <v-tooltip activator="parent" location="start">
                    <Lang>Advanced</Lang>
                </v-tooltip>
                <svg fill="#e8eaed" height="24px" viewBox="0 -960 960 960" width="24px"
                     xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path
                        d="M480-320q17 0 28.5-11.5T520-360q0-17-11.5-28.5T480-400q-17 0-28.5 11.5T440-360q0 17 11.5 28.5T480-320Zm-40-120h80v-200h-80v200ZM370-80l-16-128q-13-5-24.5-12T307-235l-119 50L78-375l103-78q-1-7-1-13.5v-27q0-6.5 1-13.5L78-585l110-190 119 50q11-8 23-15t24-12l16-128h220l16 128q13 5 24.5 12t22.5 15l119-50 110 190-103 78q1 7 1 13.5v27q0 6.5-2 13.5l103 78-110 190-118-50q-11 8-23 15t-24 12L590-80H370Zm70-80h79l14-106q31-8 57.5-23.5T639-327l99 41 39-68-86-65q5-14 7-29.5t2-31.5q0-16-2-31.5t-7-29.5l86-65-39-68-99 42q-22-23-48.5-38.5T533-694l-13-106h-79l-14 106q-31 8-57.5 23.5T321-633l-99-41-39 68 86 64q-5 15-7 30t-2 32q0 16 2 31t7 30l-86 65 39 68 99-42q22 23 48.5 38.5T427-266l13 106Zm40-320Z"/>
                </svg>

            </div>
        </div>

    </div>
</template>


<script>


import ToolsButtons from "../RightSidebar/ToolsButtons.vue";
import ToolbarMulilanguageSelector from "./ToolbarMulilanguageSelector.vue";
import CurrentLayoutSettingsButtons from "../ContextMenu/CurrentLayoutSettingsButtons.vue";
import CurrentContextMenu from "../ContextMenu/CurrentContextMenu.vue";
import CurrentNodeTextEditButton from "../ContextMenu/CurrentNodeTextEditButton.vue";
import CurrentNodeModuleEditButton from "../ContextMenu/CurrentNodeModuleEditButton.vue";
import CSSGUIService from "../../../api-core/services/services/css-gui.service.js";


export default {
    components: {
        ToolsButtons,
        ToolbarMulilanguageSelector,
        CurrentContextMenu,
        CurrentNodeTextEditButton,
        CurrentNodeModuleEditButton,
        CurrentLayoutSettingsButtons,

    },
    props: {
        template: {
            type: String,
            default: 'default',
        },
    },
    methods: {
        handleInsertLayout: function () {
            let active = mw.top().app.liveEdit.layoutHandle.getTarget();
            const selector = '.edit[data-layout-container] .module-layouts';

            if(!active) {

                //get layout target from element
                var activeElement = mw.top().app.liveEdit.elementHandle.getTarget();

                if (activeElement) {
                    var layoutTarget = mw.tools.firstParentOrCurrentWithAnyOfClasses(activeElement, ['module-layouts', 'edit']);
                    if (layoutTarget) {
                        active = layoutTarget;
                    }
                }
            }

            if (!active || !active.matches(selector)) {
                const doc = mw.top().app.canvas.getDocument();
                const scrollCenter = doc.defaultView.scrollY + (doc.defaultView.innerHeight / 2);
                const docHeight = Math.max(doc.documentElement.clientHeight, doc.defaultView.innerHeight);

                const arr = Array.from(doc.querySelectorAll(selector))
                const activeIndex = arr
                    .map(node => {
                        const rect = node.getBoundingClientRect();
                        const rectTop = rect.top + doc.defaultView.scrollY;

                        return {
                            top: rectTop,
                            visible: !(rect.bottom < 0 || rectTop - docHeight >= 0),
                            node,
                        };
                    })
                    .reduce(function (prev, curr, index) {

                        return (curr.visible && Math.abs(curr.top - scrollCenter) < Math.abs(prev - scrollCenter) ? index : prev);
                    }, -1);

                if (activeIndex >= 0) {
                    mw.top().app.liveEdit.layoutHandle.set(arr[activeIndex])
                    active = mw.top().app.liveEdit.layoutHandle.getTarget();

                }

            }


            if (active) {
                active.scrollIntoView();
                mw.top().app.editor.dispatch('insertLayoutRequestOnBottom', active)
            }

        },
        handleQuickEdit: function () {
            mw.app.liveEditWidgets.toggleQuickEditComponent()
        },
        handleAdvanced() {
            this.advanced = !this.advanced;
        },
        handleClickOutside(event) {
            if (this.advanced) {
                // Check if click is outside the popup specifically, not the entire component
                const popupElement = this.$el.querySelector('.advanced-popup');
                const advancedButton = this.$el.querySelector('.live-edit-toolbar-button-advanced');

                // Close if click is outside popup and not on the advanced button
                if (popupElement &&
                    !popupElement.contains(event.target) &&
                    !advancedButton.contains(event.target)) {
                    this.advanced = false;
                    this.$refs.moreSettingsDropdown?.classList.remove('show');
                }
            }
        },
        show: function (name) {

            this.emitter.emit('live-edit-ui-show', name);
            this.$refs.moreSettingsDropdown?.classList.remove('show');
            // Hide advanced popup when showing other UI elements
            this.advanced = false;
        },
        toggle: function (name) {

            this.$refs.moreSettingsDropdown?.classList.remove('show');
            // Hide advanced popup when toggling other UI elements
            this.advanced = false;

            if (name !== 'style-editor') {
                CSSGUIService.hide()
            }
            if (name === 'template-settings') {

                mw.top().app.templateSettingsWidget.toggle()

            } else if (name === 'style-editor') {

                CSSGUIService.toggle()

            }

        },

        hideMoreSettingsDropdown() {
            this.$refs.moreSettingsDropdown?.classList.remove('show');
            // Also hide the advanced popup
            this.advanced = false;
        },

        openReportIssueModal() {

            var url = 'https://microweber.org/go/feedback/';
            let linkInModal = mw.top().dialogIframe({
                url: url,
                width: 900,
                height: 900,
                closeOnEscape: true,

            });
            linkInModal.dialogContainer.style.paddingLeft = '0px';
            linkInModal.dialogContainer.style.paddingRight = '0px';
            linkInModal.dialogFooter.style.display = 'none';


        }
    },

    unmounted() {
        // Remove click-outside handler
        document.removeEventListener('click', this.handleClickOutside);
    },
    mounted() {
        this.emitter.on("live-edit-ui-show", () => {
            this.hideMoreSettingsDropdown();
        });
        this.emitter.on("show-content-reset", () => {
            this.hideMoreSettingsDropdown();
        });

        this.emitter.on("show-code-editor", () => {
            this.hideMoreSettingsDropdown();
        });

        window.mw.app.canvas.on('liveEditCanvasLoaded', () => {
            this.hideMoreSettingsDropdown();
        });


        mw.top().app.on('moduleInserted', () => {
            this.hideMoreSettingsDropdown();
        })
        mw.top().app.on('layoutCloned', () => {
            this.hideMoreSettingsDropdown();
        })

        mw.top().app.on('mw.open-template-settings', () => {
            // close the hamburger
            if (document.getElementById('user-menu-wrapper')) {
                document.getElementById('user-menu-wrapper').classList.remove('active');
            }

            this.hideMoreSettingsDropdown();

            this.show('template-settings')
        });

        mw.top().app.on('mw.open-report-issue-modal', () => {
            this.openReportIssueModal();
        });

        mw.top().app.on('showSetupWizard', () => {
            this.hideMoreSettingsDropdown();
        });

        mw.app.canvas.on('canvasDocumentClick', event => {
            this.hideMoreSettingsDropdown();

        });

        mw.app.canvas.on('liveEditCanvasBeforeUnload', event => {
            this.hideMoreSettingsDropdown();
        });


        mw.app.canvas.on('liveEditCanvasLoaded', () => {
            mw.app.editor.on('onModuleSettingsRequest', event => {
                this.hideMoreSettingsDropdown();
            });

            mw.app.editor.on('onLayoutSettingsRequest', event => {
                this.hideMoreSettingsDropdown();
            });
            mw.app.editor.on('insertLayoutRequest', event => {
                this.hideMoreSettingsDropdown();
            });
            mw.app.editor.on('insertModuleRequest', event => {
                this.hideMoreSettingsDropdown();
            });


            if (mw.top().app.liveEditWidgets) {
                // Layers are now handled in ToolsButtons component
            }


            this.insertLayoutVisible = !!mw.app.canvas.getDocument().querySelector('.edit[data-layout-container]')


        });

        let instance = this;
        this.emitter.on("live-edit-ui-show", (show) => {


            if (show == 'template-settings') {
                instance.buttonIsActive = true;
                instance.buttonIsActiveStyleEditor = false;
            } else if (show == 'style-editor') {
                instance.buttonIsActive = false;
                instance.buttonIsActiveStyleEditor = true;
            } else if (show == 'template-settings-close') {
                instance.buttonIsActive = false;
                instance.buttonIsActiveStyleEditor = false;
            } else {
                instance.buttonIsActive = false;
                instance.buttonIsActiveStyleEditor = false;

            }


        });


        mw.top().app.templateSettingsWidget.on('show', () => {
            this.buttonIsActive = true;
            mw.top().app.liveEditWidgets.closeQuickEditComponent();
        });
        mw.top().app.templateSettingsWidget.on('hide', () => this.buttonIsActive = false);

        mw.top().app.guiEditorBox.on('show', () => {
            this.buttonIsActiveStyleEditor = true;
            mw.top().app.liveEditWidgets.closeQuickEditComponent()
        });
        mw.top().app.guiEditorBox.on('hide', () => this.buttonIsActiveStyleEditor = false);

        mw.top().app.readyPromise(() => {
            mw.top().app.liveEditWidgets.on('openQuickEditComponent', () => {
                this.buttonIsActiveQuickEdit = true
            })

            mw.top().app.liveEditWidgets.on('closeQuickEditComponent', () => {
                this.buttonIsActiveQuickEdit = false
            })

        });

        mw.top().app.canvas.on('liveEditCanvasLoaded', () => {
            this.canShowSettingsCustomize = !!mw.top().app.canvas.getLiveEditData()
        });
        // Add click-outside handler for advanced popup
        document.addEventListener('click', this.handleClickOutside);
    },
    data() {

        var canShowSettingsCustomize = !!mw.top().app.canvas.getLiveEditData();

        return {
            canShowSettingsCustomize: canShowSettingsCustomize,
            buttonIsActive: false,
            buttonIsActiveStyleEditor: false,
            buttonIsActiveQuickEdit: false,
            advanced: false,
            insertLayoutVisible: false,
            iconInsertlayout: mw.top().app?.iconService?.icon('add-layout'),

        }
    },

}
</script>
