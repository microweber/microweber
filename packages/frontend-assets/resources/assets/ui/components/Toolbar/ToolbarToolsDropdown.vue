<template>
    <div class="mx-2" v-if="canShowTools">
        <div class="custom-dropdown">
            <!--
              task-2026-05-16-5fe1f9 / AI-698b item 2 — the `⋯ Tools`
              popover trigger now picks up the .mw-tool-btn primitive
              from slice 1.3a (AI-684 / task-f69d54) so visual weight
              matches the rest of the design-system buttons. Legacy
              `.dropdown-trigger` class kept alongside `.mw-tool-btn`
              for the custom-dropdown JS that targets it.
            -->
            <!--
                task-2026-05-28-4a2e1b / AI-1151 — Tools dropdown ARIA menu pattern + keyboard nav.
                Jira: https://microweber.atlassian.net/browse/AI-1151

                Trigger anchor carries id="mw-tools-dropdown-trigger" so the dropdown
                ul (role="menu") can reference it via aria-labelledby. Down/Up arrow
                keys open the menu and focus first/last menuitem per the WAI-ARIA
                Authoring Practices Guide Menu pattern. ref="toolsDropdownTrigger"
                lets the keydown handler return focus on Escape close.
            -->
            <a role="button" id="mw-tools-dropdown-trigger" ref="toolsDropdownTrigger"
               aria-label="Tools menu" aria-haspopup="menu" class="dropdown-trigger mw-tool-btn"
               :aria-expanded="dropdownOpen.toString()" @click.prevent="toggleDropdown"
               @keydown.down.prevent="openAndFocusFirstMenuItem"
               @keydown.up.prevent="openAndFocusLastMenuItem"
               @keydown.enter.prevent="toggleDropdown"
               @keydown.space.prevent="toggleDropdown">
                <svg fill="currentColor" height="20" viewBox="0 -960 960 960" width="20" xmlns="http://www.w3.org/2000/svg">
                    <path d="M480-160q-33 0-56.5-23.5T400-240q0-33 23.5-56.5T480-320q33 0 56.5 23.5T560-240q0 33-23.5 56.5T480-160Zm0-240q-33 0-56.5-23.5T400-480q0-33 23.5-56.5T480-560q33 0 56.5 23.5T560-480q0 33-23.5 56.5T480-400Zm0-240q-33 0-56.5-23.5T400-720q0-33 23.5-56.5T480-800q33 0 56.5 23.5T560-720q0 33-23.5 56.5T480-640Z"/>
                </svg>

                <span class="hidden">Tools</span>

            </a>

            <!--
                task-2026-05-28-4a2e1b / AI-1151 — Tools dropdown ul carries
                role="menu" + aria-labelledby pointing at the trigger anchor's
                id. handleMenuKeydown owns ArrowUp/ArrowDown/Home/End/Escape/Tab
                roving-tabindex navigation per WAI-ARIA Authoring Practices
                Guide Menu pattern.

                task-2026-05-28-f8a3c1 / AI-1225 — IA reorganisation:
                - Preferences section (Dark mode) separated from editing tools
                - Session section (user menu items) separated from preferences
                - "Back to Admin" filtered from userMenuItems (already in MainDrawer Navigate)
            -->
            <ul class="dropdown-content" :class="{ 'show': dropdownOpen }"
                ref="toolsDropdownContent"
                role="menu"
                aria-labelledby="mw-tools-dropdown-trigger"
                @keydown="handleMenuKeydown">





                <li role="none">
                    <button type="button" role="menuitem" tabindex="-1" @click="handleInsertLayout" v-show="insertLayoutVisible">
                        <svg fill="currentColor" height="20" viewBox="0 -960 960 960" width="20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-480H200v480Zm80-80h200v-120H280v120Zm240 0h200v-280H520v280Zm-240-160h200v-120H280v120Z"/>
                        </svg>
                        Insert layout
                    </button>
                </li>
                <li role="none">
                    <button type="button" role="menuitem" tabindex="-1" @click="handleInsertModule" v-show="canInsertModule">
                        <svg fill="currentColor" height="20" viewBox="0 -960 960 960" width="20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M440-120v-320H120v-80h320v-320h80v320h320v80H520v320h-80Z"/>
                        </svg>
                        Insert module
                    </button>
                </li>
                <li role="none">
                    <button type="button" role="menuitem" tabindex="-1" @click="handleEditTextNode" v-show="hasEditableTextNode">
                        <svg fill="currentColor" height="20" viewBox="0 0 24 24" width="20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                        </svg>
                        Edit element
                    </button>
                </li>
                <li role="none">
                    <button type="button" role="menuitem" tabindex="-1" @click="handleEditModuleNode" v-show="hasEditableModuleNode">
                        <svg fill="currentColor" height="20" viewBox="0 0 24 24" width="20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 15.5A3.5 3.5 0 0 1 8.5 12A3.5 3.5 0 0 1 12 8.5a3.5 3.5 0 0 1 3.5 3.5a3.5 3.5 0 0 1-3.5 3.5m7.43-2.53c.04-.32.07-.64.07-.97c0-.33-.03-.66-.07-1l2.11-1.63c.19-.15.24-.42.12-.64l-2-3.46c-.12-.22-.39-.31-.61-.22l-2.49 1c-.52-.39-1.06-.73-1.69-.98l-.37-2.65A.506.506 0 0 0 14 2h-4c-.25 0-.46.18-.5.42l-.37 2.65c-.63.25-1.17.59-1.69.98l-2.49-1c-.22-.09-.49 0-.61.22l-2 3.46c-.13.22-.07.49.12.64L4.57 11c-.04.34-.07.67-.07 1c0 .33.03.65.07.97l-2.11 1.66c-.19.15-.25.42-.12.64l2 3.46c.12.22.39.3.61.22l2.49-1.01c.52.4 1.06.74 1.69.99l.37 2.65c.04.24.25.42.5.42h4c.25 0 .46-.18.5-.42l.37-2.65c.63-.26 1.17-.59 1.69-.99l2.49 1.01c.22.08.49 0 .61-.22l2-3.46c.12-.22.07-.49-.12-.64l-2.11-1.66Z"/>
                        </svg>
                        Module settings
                    </button>
                </li>

                <li role="none">
                    <!-- task-2026-05-16-5fe1f9 / AI-698b item 2 — label
                         disambiguated. This 3-dots dropdown item opens
                         the same scope-picker panel as the right-rail
                         button + left-nav-menu item. Method name
                         handleTemplateSettings stays — internal API.

                         task-2026-05-17-c3f232 / AI-800b — recon-driven
                         Slice B bundle: cross-surface case cascade
                         continued from AI-800a. 3-dots dropdown is the
                         third entry point to template-settings panel;
                         aligned with MainDrawer + bootstrap.js controlBox
                         + RightSidebar h3 + SettingsCustomize right-rail
                         button + LeftSidebar nav-menu to render "Templates
                         & layouts" (sentence-case plural-plural). Designer's
                         AI-800b dispatch named the right-rail button only;
                         recon found this dropdown item as a sibling cascade
                         site per the recon-driven Slice B uniformity rule
                         (task-46127c). -->
                    <button type="button" role="menuitem" tabindex="-1" @click="handleTemplateSettings" v-show="canShowSettingsCustomize" :class="{ active: buttonIsActive && !buttonIsActiveStyleEditor }">
                        <svg fill="currentColor" height="20" viewBox="96 96 960 960" width="20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M480 976q-82 0-155-31.5t-127.5-86Q143 804 111.5 731T80 576q0-83 32.5-156t88-127Q256 239 330 207.5T488 176q80 0 151 27.5t124.5 76q53.5 48.5 85 115T880 538q0 115-70 176.5T640 776h-74q-9 0-12.5 5t-3.5 11q0 12 15 34.5t15 51.5q0 50-27.5 74T480 976Zm0-400Zm-220 40q26 0 43-17t17-43q0-26-17-43t-43-17q-26 0-43 17t-17 43q0 26 17 43t43 17Zm120-160q26 0 43-17t17-43q0-26-17-43t-43-17q-26 0-43 17t-17 43q0 26 17 43t43 17Zm200 0q26 0 43-17t17-43q0-26-17-43t-43-17q-26 0-43 17t-17 43q0 26 17 43t43 17Zm120 160q26 0 43-17t17-43q0-26-17-43t-43-17q-26 0-43 17t-17 43q0 26 17 43t43 17ZM480 896q9 0 14.5-5t5.5-13q0-14-15-33t-15-57q0-42 29-67t71-25h70q66 0 113-38.5T800 538q0-121-92.5-201.5T488 256q-136 0-232 93t-96 227q0 133 93.5 226.5T480 896Z"/>
                        </svg>
                        Templates &amp; layouts
                    </button>
                </li>

                <li role="none">
                    <!-- task-2026-05-16-5fe1f9 / AI-698b item 2 — label
                         renamed "Style Editor" → "Design" to match the
                         AI-710 right-rail icon-label fix (Design icon
                         opens the Element Style Editor). Method name
                         handleStyleEditor stays — internal API. -->
                    <button type="button" role="menuitem" tabindex="-1" @click="handleStyleEditor" v-show="canShowSettingsCustomize" :class="{ active: !buttonIsActive && buttonIsActiveStyleEditor }">
                        <svg fill="currentColor" height="20" viewBox="0 -960 960 960" width="20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M480-120q-133 0-226.5-92T160-436q0-65 25-121.5T254-658l226-222 226 222q44 44 69 100.5T800-436q0 132-93.5 224T480-120ZM242-400h474q12-72-13.5-123T650-600L480-768 310-600q-27 26-53 77t-15 123Z"/>
                        </svg>
                        Design
                    </button>
                </li>

                <li role="none">
                    <button type="button" role="menuitem" tabindex="-1" @click="handleQuickEdit" v-show="canShowSettingsCustomize" :class="{ active: buttonIsActiveQuickEdit }">
                        <svg clip-rule="evenodd" fill="currentColor" fill-rule="evenodd" height="20px" image-rendering="optimizeQuality" shape-rendering="geometricPrecision" text-rendering="geometricPrecision" viewBox="0 0 512 472.403" width="20px" xmlns="http://www.w3.org/2000/svg">
                            <path d="M144.317 85.269h223.368c15.381 0 29.391 6.325 39.567 16.494l.025-.024c10.163 10.164 16.477 24.193 16.477 39.599v189.728c0 15.401-6.326 29.425-16.485 39.584-10.159 10.159-24.183 16.484-39.584 16.484H144.317c-15.4 0-29.437-6.313-39.601-16.476-10.152-10.152-16.47-24.167-16.47-39.592V141.338c0-15.374 6.306-29.379 16.463-39.558l.078-.078c10.178-10.139 24.168-16.433 39.53-16.433zm59.98 204.329h-39.825l30.577-117.964h58.32l30.577 117.964h-39.825l-3.051-18.686h-33.725l-3.048 18.686zm15.645-81.726l-5.801 33.032h19.945l-5.61-33.032h-8.534zm74.007 81.726V171.634h37.749v117.964h-37.749zm161.348-35.797v30.763c0 3.165 2.587 5.751 5.752 5.751h45.199c3.165 0 5.752-2.586 5.752-5.751v-30.763c0-3.165-2.587-5.752-5.752-5.752h-45.199c-3.165 0-5.752 2.587-5.752 5.752zm0-70.639v30.762c0 3.163 2.587 5.752 5.752 5.752h45.199c3.165 0 5.752-2.589 5.752-5.752v-30.762c0-3.168-2.587-5.752-5.752-5.752h-45.199c-3.165 0-5.752 2.584-5.752 5.752zm0 141.278v30.763c0 3.165 2.587 5.752 5.752 5.752h45.199c3.165 0 5.752-2.587 5.752-5.752V324.44c0-3.165-2.587-5.751-5.752-5.751h-45.199c-3.165 0-5.752 2.586-5.752 5.751zm0-211.92v30.763c0 3.164 2.587 5.751 5.752 5.751h45.199c3.165 0 5.752-2.587 5.752-5.751V112.52c0-3.165-2.587-5.752-5.752-5.752h-45.199c-3.165 0-5.752 2.587-5.752 5.752zM56.703 253.801v30.763c0 3.165-2.587 5.751-5.752 5.751H5.752c-3.165 0-5.752-2.586-5.752-5.751v-30.763c0-3.165 2.587-5.752 5.752-5.752h45.199c3.165 0 5.752 2.587 5.752 5.752zm0-70.639v30.762c0 3.163-2.587 5.752-5.752 5.752H5.752c-3.165 0-5.752-2.589-5.752-5.752v-30.762c0-3.168 2.587-5.752 5.752-5.752h45.199c3.165 0 5.752 2.584 5.752 5.752zm0 141.278v30.763c0 3.165-2.587 5.752-5.752 5.752H5.752c-3.165 0-5.752-2.587-5.752-5.752V324.44c0-3.165 2.587-5.751 5.752-5.751h45.199c3.165 0 5.752 2.586 5.752 5.751zm0-211.92v30.763c0 3.164-2.587 5.751-5.752 5.751H5.752c-3.165 0-5.752-2.587-5.752-5.751V112.52c0-3.165 2.587-5.752 5.752-5.752h45.199c3.165 0 5.752 2.587 5.752 5.752zM346.579 415.7h30.763c3.162 0 5.751 2.587 5.751 5.752v45.199c0 3.165-2.589 5.752-5.751 5.752h-30.763c-3.167 0-5.752-2.587-5.752-5.752v-45.199c0-3.165 2.585-5.752 5.752-5.752zm-70.642 0H306.7c3.165 0 5.751 2.587 5.751 5.752v45.199c0 3.165-2.586 5.752-5.751 5.752h-30.763c-3.165 0-5.752-2.587-5.752-5.752v-45.199c0-3.165 2.587-5.752 5.752-5.752zm-70.639 0h30.762c3.165 0 5.752 2.587 5.752 5.752v45.199c0 3.165-2.587 5.752-5.752 5.752h-30.762c-3.165 0-5.752-2.587-5.752-5.752v-45.199c0-3.165 2.587-5.752 5.752-5.752zm-70.64 0h30.763c3.165 0 5.752 2.587 5.752 5.752v45.199c0 3.165-2.587 5.752-5.752 5.752h-30.763c-3.165 0-5.751-2.587-5.751-5.752v-45.199c0-3.165 2.586-5.752 5.751-5.752zM346.579 0h30.763c3.162 0 5.751 2.587 5.751 5.752v45.199c0 3.165-2.589 5.752-5.751 5.752h-30.763c-3.167 0-5.752-2.587-5.752-5.752V5.752c0-3.165 2.585-5.752 5.752-5.752zm-70.642 0H306.7c3.165 0 5.751 2.587 5.751 5.752v45.199c0 3.165-2.586 5.752-5.751 5.752h-30.763c-3.165 0-5.752-2.587-5.752-5.752V5.752c0-3.165 2.587-5.752 5.752-5.752zm-70.639 0h30.762c3.165 0 5.752 2.587 5.752 5.752v45.199c0 3.165-2.587 5.752-5.752 5.752h-30.762c-3.165 0-5.752-2.587-5.752-5.752V5.752c0-3.165 2.587-5.752 5.752-5.752zm-70.64 0h30.763c3.165 0 5.752 2.587 5.752 5.752v45.199c0 3.165-2.587 5.752-5.752 5.752h-30.763c-3.165 0-5.751-2.587-5.751-5.752V5.752c0-3.165 2.586-5.752 5.751-5.752zm233.027 111.097H144.317a30.11 30.11 0 00-21.35 8.844l-.049.049a30.117 30.117 0 00-8.844 21.348v189.728c0 8.292 3.414 15.847 8.9 21.333 5.494 5.493 13.058 8.907 21.343 8.907h223.368c8.273 0 15.833-3.421 21.326-8.914s8.915-13.053 8.915-21.326V141.338c0-8.283-3.414-15.848-8.908-21.341v-.049c-5.454-5.456-13.006-8.851-21.333-8.851z" fill-rule="nonzero"/>
                        </svg>
                        Quick AI edit
                    </button>
                </li>

                <li role="none">
                    <button type="button" role="menuitem" tabindex="-1" @click="handleCurrentLayoutSettings">
                        <svg fill="currentColor" height="20" viewBox="0 -960 960 960" width="20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M440-120v-240h80v80h320v80H520v80h-80Zm-320-80v-80h240v80H120Zm160-160v-80H120v-80h160v-80h80v240h-80Zm160-80v-80h400v80H440Zm160-160v-240h80v80h160v80H680v80h-80Zm-480-80v-80h400v80H120Z"/>
                        </svg>
                        Layout settings
                    </button>
                </li>

                <li role="none">
                    <button type="button" role="menuitem" tabindex="-1" @click="openSetupWizard">
                        <svg fill="currentColor" viewBox="0 -960 960 960" xmlns="http://www.w3.org/2000/svg">
                            <path d="m176-120-56-56 301-302-181-45 198-123-17-234 179 151 216-88-87 217 151 178-234-16-124 198-45-181-301 301Zm24-520-80-80 80-80 80 80-80 80Zm355 197 48-79 93 7-60-71 35-86-86 35-71-59 7 92-79 49 90 22 23 90Zm165 323-80-80 80-80 80 80-80 80ZM569-570Z"/>
                        </svg>
                        Setup wizard
                    </button>
                </li>



                <!-- More Settings Section -->
                <li class="separator" role="separator">
                    <hr>
                </li>

                <!-- task-2026-05-28-f8a3c1 / AI-1225 — Preferences section
                     Dark mode is a global preference, not an editing tool.
                     Separated here with a section label so it's visually
                     distinct from the editing tools above. -->
                <li class="section-label" role="none" aria-hidden="true">
                    <span>Preferences</span>
                </li>

                <li role="none">
                    <button type="button" role="menuitem" tabindex="-1" @click="handleToggleDarkMode">
                        <svg v-if="theme === 'light'" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 96 960 960" width="20">
                            <path d="M480 936q-150 0-255-105T120 576q0-150 105-255t255-105q14 0 27.5 1t26.5 3q-41 29-65.5 75.5T444 396q0 90 63 153t153 63q55 0 101-24.5t75-65.5q2 13 3 26.5t1 27.5q0 150-105 255T480 936Zm0-80q88 0 158-48.5T740 681q-20 5-40 8t-40 3q-123 0-209.5-86.5T364 396q0-20 3-40t8-40q-78 32-126.5 102T200 576q0 116 82 198t198 82Zm-10-270Z"/>
                        </svg>
                        <svg v-else height="20" width="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path d="M10 2a.75.75 0 0 1 .75.75v1.5a.75.75 0 0 1-1.5 0v-1.5A.75.75 0 0 1 10 2ZM10 15a.75.75 0 0 1 .75.75v1.5a.75.75 0 0 1-1.5 0v-1.5A.75.75 0 0 1 10 15ZM10 7a3 3 0 1 0 0 6 3 3 0 0 0 0-6ZM15.657 5.404a.75.75 0 1 0-1.06-1.06l-1.061 1.06a.75.75 0 0 0 1.06 1.06l1.06-1.06ZM6.464 14.596a.75.75 0 1 0-1.06-1.06l-1.06 1.06a.75.75 0 0 0 1.06 1.06l1.06-1.06ZM18 10a.75.75 0 0 1-.75.75h-1.5a.75.75 0 0 1 0-1.5h1.5A.75.75 0 0 1 18 10ZM5 10a.75.75 0 0 1-.75.75h-1.5a.75.75 0 0 1 0-1.5h1.5A.75.75 0 0 1 5 10ZM14.596 15.657a.75.75 0 0 0 1.06-1.06l-1.06-1.061a.75.75 0 1 0-1.06 1.06l1.06 1.06ZM5.404 6.464a.75.75 0 0 0 1.06-1.06l-1.06-1.06a.75.75 0 1 0-1.061 1.06l1.06 1.06Z"/>
                        </svg>
                        {{ theme === 'light' ? 'Dark mode' : 'Light mode' }}
                    </button>
                </li>

                <li class="separator" role="separator">
                    <hr>
                </li>

                <li class="more-settings-item" role="none">
                    <button type="button" role="menuitem" tabindex="-1" aria-haspopup="menu" aria-controls="mw-tools-more-settings" :aria-expanded="moreSettingsExpanded.toString()" @click="toggleMoreSettings" :class="{ expanded: moreSettingsExpanded }">
                        <svg fill="currentColor" height="20" viewBox="0 -960 960 960" width="20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M480-160q-33 0-56.5-23.5T400-240q0-33 23.5-56.5T480-320q33 0 56.5 23.5T560-240q0 33-23.5 56.5T480-160Zm0-240q-33 0-56.5-23.5T400-480q0-33 23.5-56.5T480-560q33 0 56.5 23.5T560-480q0 33-23.5 56.5T480-400Zm0-240q-33 0-56.5-23.5T400-720q0-33 23.5-56.5T480-800q33 0 56.5 23.5T560-720q0 33-23.5 56.5T480-640Z"/>
                        </svg>
                        More settings
                        <svg class="expand-arrow" :class="{ rotated: moreSettingsExpanded }" fill="currentColor" height="16" viewBox="0 0 24 24" width="16" xmlns="http://www.w3.org/2000/svg">
                            <path d="M8.59 16.34l4.58-4.59-4.58-4.59L10 5.75l6 6-6 6z"/>
                        </svg>
                    </button>
                </li>

                <!--
                    task-2026-05-16-3ae87c: user-menu items merged in here.
                    task-2026-05-28-f8a3c1 / AI-1225: reorganised into a
                    "Session" section. "Back to Admin" filtered in
                    fetchUserMenu() — it duplicates MainDrawer Navigate.
                    The original `#user-menu-wrapper` stays in the DOM
                    hidden — see Toolbar.vue for the back-compat note.
                -->
                <li class="separator" role="separator" v-if="userMenuItems.length > 0">
                    <hr>
                </li>

                <li class="section-label" role="none" aria-hidden="true" v-if="userMenuItems.length > 0">
                    <span>Session</span>
                </li>

                <li role="none" v-for="(menuItem, idx) in userMenuItems" :key="'um-' + idx">
                    <a
                        role="menuitem"
                        tabindex="-1"
                        :href="menuItem.href"
                        :onclick="menuItem.onclick"
                        :target="menuItem.target"
                        :id="menuItem.id ? menuItem.id + '-tools' : null"
                        :class="menuItem.class || ''"
                        @click="hideToolsDropdown"
                    >
                        <span v-html="menuItem.icon_html"></span>
                        {{ menuItem.title }}
                    </a>
                </li>

                <!-- Expandable More Settings Content -->
                <li class="more-settings-content" role="none" v-show="moreSettingsExpanded">
                    <ul class="submenu" id="mw-tools-more-settings" role="menu" aria-labelledby="mw-tools-dropdown-trigger">
                        <li role="none">
                            <button type="button" role="menuitem" tabindex="-1" @click="handleLayers" :class="{ active: layers }">
                                <svg fill="currentColor" height="18" viewBox="0 -960 960 960" width="18" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M480-400 40-640l440-240 440 240-440 240Zm0 160L63-467l84-46 333 182 333-182 84 46-417 227Zm0 160L63-307l84-46 333 182 333-182 84 46L480-80Zm0-411 273-149-273-149-273 149 273 149Zm0-149Z"/>
                                </svg>
                                Layers
                            </button>
                        </li>

                        <li role="none">
                            <button type="button" role="menuitem" tabindex="-1" @click="showCodeEditor">
                                <svg fill="currentColor" height="18" viewBox="0 -960 960 960" width="18" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M0-360v-240h60v80h80v-80h60v240h-60v-100H60v100H0Zm310 0v-180h-70v-60h200v60h-70v180h-60Zm170 0v-200q0-17 11.5-28.5T520-600h180q17 0 28.5 11.5T740-560v200h-60v-180h-40v140h-60v-140h-40v180h-60Zm320 0v-240h60v180h100v60H800Z"/>
                                </svg>
                                Code editor
                            </button>
                        </li>

                        <li role="none">
                            <button type="button" role="menuitem" tabindex="-1" @click="openContentResetContent">
                                <svg fill="currentColor" height="18" viewBox="0 -960 960 960" width="18" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M440-122q-121-15-200.5-105.5T160-440q0-66 26-126.5T260-672l57 57q-38 34-57.5 79T240-440q0 88 56 155.5T440-202v80Zm80 0v-80q87-16 143.5-83T720-440q0-100-70-170t-170-70h-3l44 44-56 56-140-140 140-140 56 56-44 44h3q134 0 227 93t93 227q0 121-79.5 211.5T520-122Z"/>
                                </svg>
                                Reset content
                            </button>
                        </li>

                        <li role="none">
                            <button type="button" role="menuitem" tabindex="-1" @click="clearCache">
                                <svg fill="currentColor" height="18" viewBox="0 -960 960 960" width="18" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M280-720v520-520Zm170 600H280q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v172q-17-5-39.5-8.5T680-560v-160H280v520h132q6 21 16 41.5t22 38.5Zm-90-160h40q0-63 20-103.5l20-40.5v-216h-80v360Zm160-230q17-11 38.5-22t41.5-16v-92h-80v130ZM680-80q-83 0-141.5-58.5T480-280q0-83 58.5-141.5T680-480q83 0 141.5 58.5T880-280q0 83-58.5 141.5T680-80Zm66-106 28-28-74-74v-112h-40v128l86 86Z"/>
                                </svg>
                                Clear cache
                            </button>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</template>

<style scoped>
.custom-dropdown {
    position: relative;
    display: inline-block;
}

.dropdown-trigger {
    cursor: pointer;
    /* task-2026-05-28-f8a3c1 / AI-1225 — trigger was 35px, WCAG 2.5.5 requires 44px */
    padding: 8px 14px 8px 10px;
    display: flex;
    align-items: center;
    gap: 8px;
    background: #fff;
    border-radius: 7px;
    border: 1px solid #e0e0e0;
    box-shadow: none;
    transition: border 0.2s, box-shadow 0.2s;
    font-weight: 400;
    font-size: 14px;
    line-height: 20px;
    color: #222;
    outline: none;
    position: relative;
    min-height: 44px;
    width: auto;
    min-width: unset;
}

.dropdown-trigger:focus,
.dropdown-trigger:hover {
    border: 1px solid #c0c0c0;
    box-shadow: none;
    background: #f5f5f5;
}

.dropdown-content {
    display: none;
    position: absolute;
    top: 110%;
    right: 0;
    background-color: #fff;
    min-width: 200px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.13);
    border-radius: 8px;
    padding: 8px 0;
    z-index: 1000;
    border: 1px solid #e0e0e0;
    transition: opacity 0.18s;
}

.dropdown-content.show {
    display: block;
}

.dropdown-content li {
    list-style: none;
}

.dropdown-content li a,
.dropdown-content li button {
    /* task-2026-05-28-f8a3c1 / AI-1225 — items were ~38px, min-height 44px for WCAG 2.5.5 */
    display: flex;
    align-items: center;
    min-height: 36px;
    padding: 8px 16px;
    font-size: 14px;
    text-decoration: none;
    color: #222;
    cursor: pointer;
    font-size: 15px;
    border-radius: 5px;
    transition: background 0.15s, color 0.15s;
    gap: 10px;
    background: none;
    border: none;
    width: 100%;
    text-align: left;
    font: inherit;
    line-height: inherit;
}

.dropdown-content li a:hover,
.dropdown-content li a:focus,
.dropdown-content li button:hover,
.dropdown-content li button:focus {
    background-color: #f3f4f6;
    color: #182433;
}

.dropdown-content li a.active,
.dropdown-content li button.active {
    background-color: #eceef1;
    color: #182433;
    font-weight: 600;
}

.dropdown-content li a svg,
.dropdown-content li button svg {
    width: 20px;
    height: 20px;
    flex-shrink: 0;
}

/* Dark mode styles */
:global(.dark) .dropdown-trigger {
    background: rgb(31, 41, 55);
    border: 1px solid #374151;
    color: #e5e7eb;
}

:global(.dark) .dropdown-trigger:hover,
:global(.dark) .dropdown-trigger:focus {
    border: 1px solid #4b5563;
    background: rgb(55, 65, 81);
}

:global(.dark) .dropdown-content {
    background-color: rgb(24, 36, 51);
    border: 1px solid #374151;
}

:global(.dark) .dropdown-content li a,
:global(.dark) .dropdown-content li button {
    color: #e5e7eb;
}

:global(.dark) .dropdown-content li a:hover,
:global(.dark) .dropdown-content li a:focus,
:global(.dark) .dropdown-content li button:hover,
:global(.dark) .dropdown-content li button:focus {
    background-color: rgb(31, 41, 55);
    color: #ffffff;
}

:global(.dark) .dropdown-content li a.active,
:global(.dark) .dropdown-content li button.active {
    background-color: rgba(255, 255, 255, 0.08);
    color: #e5e7eb;
}

/* Separator styling */
.separator {
    margin: 8px 0;
}

.separator hr {
    border: none;
    height: 1px;
    background-color: #e0e0e0;
    margin: 0 16px;
}

/* task-2026-06-04-le831 / AI-831 — group divider was #374151 (gray-700)
   on the gray-800 dark dropdown surface: <3:1 non-text contrast, so the
   line read as effectively invisible. Bump to rgba(255,255,255,0.2),
   which composites to ~rgb(76,86,95) over the dark surface (~5:1 vs the
   background) — a clearly visible 1px divider that still reads as subtle
   chrome. Stage-2 cascade-loss dark-mode fix (same shape as AI-786 v2). */
:global(.dark) .separator hr {
    background-color: rgba(255, 255, 255, 0.2);
}

/* More Settings expandable styling */
.more-settings-item a,
.more-settings-item button {
    position: relative;
    justify-content: space-between;
}

.more-settings-item a.expanded,
.more-settings-item button.expanded {
    background-color: #f3f4f6;
    color: #182433;
}

:global(.dark) .more-settings-item a.expanded,
:global(.dark) .more-settings-item button.expanded {
    background-color: rgb(31, 41, 55);
    color: #ffffff;
}

.expand-arrow {
    transition: transform 0.2s ease;
    margin-left: auto;
}

.expand-arrow.rotated {
    transform: rotate(90deg);
}

/* Submenu styling */
.more-settings-content {
    padding: 0;
}

.submenu {
    list-style: none;
    padding: 0;
    margin: 0;
    background-color: rgba(0, 0, 0, 0.02);
    border-radius: 6px;
    margin: 4px 12px 8px 12px;
}

:global(.dark) .submenu {
    background-color: rgba(255, 255, 255, 0.03);
}

.submenu li a,
.submenu li button {
    /* task-2026-05-28-f8a3c1 / AI-1225 — submenu items also need 44px */
    min-height: 44px;
    padding: 8px 16px 8px 24px;
    font-size: 14px;
    border-radius: 4px;
    margin: 2px 4px;
}

.submenu li a svg,
.submenu li button svg {
    width: 18px;
    height: 18px;
}

.submenu li a:hover,
.submenu li a:focus,
.submenu li button:hover,
.submenu li button:focus {
    background-color: #f3f4f6;
    color: #182433;
}

:global(.dark) .submenu li a:hover,
:global(.dark) .submenu li a:focus,
:global(.dark) .submenu li button:hover,
:global(.dark) .submenu li button:focus {
    background-color: rgba(255, 255, 255, 0.08);
    color: #e5e7eb;
}

/* task-2026-05-28-f8a3c1 / AI-1225 — section label rows (Preferences, Session)
   are non-interactive; they carry aria-hidden="true" so screen readers
   skip them. Visually they use a small all-caps weight-600 muted label. */
.section-label {
    padding: 8px 20px 2px;
    list-style: none;
    pointer-events: none;
}

.section-label span {
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: #9ca3af;
}

:global(.dark) .section-label span {
    color: #6b7280;
}
</style>

<script>
export default {
    data() {
        return {
            canShowTools: false,
            dropdownOpen: false,
            layers: false,
            contentRevisionsDialogInstance: null,
            contentResetContentInstance: null,
            // Context menu integration
            hasEditableTextNode: false,
            hasEditableModuleNode: false,
            canInsertModule: false,
            currentElement: null,
            // More settings expandable state
            moreSettingsExpanded: false,
            // Insert layout visibility
            insertLayoutVisible: false,
            // Settings customize and button states
            canShowSettingsCustomize: false,
            buttonIsActive: false,
            buttonIsActiveStyleEditor: false,
            buttonIsActiveQuickEdit: false,
            // task-2026-05-16-3ae87c: user-menu items merged in.
            userMenuItems: [],
            theme: 'light',
        }
    },
    methods: {
        // task-2026-05-28-4a2e1b / AI-1151 — open the menu and focus the
        // first menuitem so screen-reader + keyboard users land on a
        // tabbable element per WAI-ARIA APG Menu pattern.
        toggleDropdown() {
            const opening = !this.dropdownOpen;
            this.dropdownOpen = opening;
            if (opening) {
                this.$nextTick(() => this.focusFirstMenuItem());
            }
        },

        // task-2026-05-28-4a2e1b / AI-1151 — close the menu. When the
        // close was triggered by Escape, return focus to the trigger
        // anchor per WAI-ARIA APG Menu pattern. Always collapse the
        // nested "More settings" submenu so the next open starts clean.
        hideToolsDropdown(focusTrigger = false) {
            this.dropdownOpen = false;
            this.moreSettingsExpanded = false;
            if (focusTrigger && this.$refs.toolsDropdownTrigger) {
                this.$refs.toolsDropdownTrigger.focus();
            }
        },

        // task-2026-05-28-4a2e1b / AI-1151 — keyboard activators on the
        // trigger anchor. Enter/Space/ArrowDown open + focus first item;
        // ArrowUp opens + focuses last item per WAI-ARIA APG Menu pattern.
        openAndFocusFirstMenuItem() {
            if (!this.dropdownOpen) { this.dropdownOpen = true; }
            this.$nextTick(() => this.focusFirstMenuItem());
        },

        openAndFocusLastMenuItem() {
            if (!this.dropdownOpen) { this.dropdownOpen = true; }
            this.$nextTick(() => this.focusLastMenuItem());
        },

        // Collect currently-visible role=menuitem elements within the
        // dropdown content root. Hidden submenu items (More settings
        // collapsed) are filtered via offsetParent null check.
        getMenuItems() {
            const root = this.$refs.toolsDropdownContent;
            if (!root) return [];
            return Array.from(root.querySelectorAll('[role="menuitem"]'))
                .filter((el) => el.offsetParent !== null);
        },

        focusFirstMenuItem() {
            const items = this.getMenuItems();
            if (items.length > 0) items[0].focus();
        },

        focusLastMenuItem() {
            const items = this.getMenuItems();
            if (items.length > 0) items[items.length - 1].focus();
        },

        // task-2026-05-28-4a2e1b / AI-1151 — roving-tabindex keyboard
        // navigation per WAI-ARIA APG Menu pattern. ArrowDown/Up cycle,
        // Home/End jump to ends, Escape closes + focuses trigger, Tab
        // closes without focus return (lets the natural tab order
        // continue past the menu).
        handleMenuKeydown(event) {
            const items = this.getMenuItems();
            if (items.length === 0) return;
            const currentIndex = items.indexOf(document.activeElement);
            switch (event.key) {
                case 'ArrowDown': {
                    event.preventDefault();
                    const next = currentIndex < 0 || currentIndex === items.length - 1 ? 0 : currentIndex + 1;
                    items[next].focus();
                    break;
                }
                case 'ArrowUp': {
                    event.preventDefault();
                    const prev = currentIndex <= 0 ? items.length - 1 : currentIndex - 1;
                    items[prev].focus();
                    break;
                }
                case 'Home': {
                    event.preventDefault();
                    items[0].focus();
                    break;
                }
                case 'End': {
                    event.preventDefault();
                    items[items.length - 1].focus();
                    break;
                }
                case 'Escape': {
                    event.preventDefault();
                    this.hideToolsDropdown(true);
                    break;
                }
                case 'Tab': {
                    this.hideToolsDropdown(false);
                    break;
                }
            }
        },

        // Close any Filament module/layout-settings slideOver that may
        // already be mounted before opening a non-Filament widget
        // (Template Settings, Style Editor, Quick AI Edit, Setup
        // Wizard, Layers, Code Editor, Reset Content, Clear Cache,
        // Insert Layout/Module, Edit Element). Without this the open
        // slideOver stays painted behind whatever the toolbar action
        // pops up — see task-2026-04-29-2315b5 / -70496a.
        closeFilamentSlideOver() {
            window.dispatchEvent(new Event('closeFilamentSlideOver'));
        },

        openSetupWizard() {
            this.hideToolsDropdown();
            this.closeFilamentSlideOver();
            mw.top().app.dispatch('showSetupWizard');
        },

        showCodeEditor() {
            this.hideToolsDropdown();
            this.closeFilamentSlideOver();
            this.emitter.emit('show-code-editor');
        },

        openContentResetContent() {
            this.hideToolsDropdown();
            this.closeFilamentSlideOver();
            this.emitter.emit('show-content-reset');

            var moduleType = 'editor/reset_content';
            var attrsForSettings = {};

            attrsForSettings.live_edit = true;
            attrsForSettings.module_settings = true;
            attrsForSettings.id = 'mw_global_reset_content_editor';
            attrsForSettings.type = moduleType;
            attrsForSettings.iframe = true;
            attrsForSettings.from_url = mw.app.canvas.getWindow().location.href;

            var src = mw.app.adminModules.getModuleSettingsUrl(moduleType, attrsForSettings);

            if (typeof (root_element_id) != 'undefined') {
                var src = src + '&root_element_id=' + root_element_id;
            }

            var modal = mw.dialogIframe({
                url: src,
                name: 'mw-reset-content-editor-front',
                title: 'Reset content',
                template: 'default',
                center: false,
                resize: true,
                autosize: true,
                autoHeight: true,
                draggable: true
            });

            this.contentResetContentInstance = modal;
        },

        handleLayers() {
            this.hideToolsDropdown();
            this.closeFilamentSlideOver();
            this.layers = !this.layers;
            mw.app.liveEditWidgets.toggleLayers();
        },

        getCurrentLayoutElement() {
            let layoutElement = window.mw.top().app.liveEdit.getSelectedLayoutNode();
            return layoutElement;
        },

        handleCurrentLayoutSettings() {
            this.hideToolsDropdown();
            let layout = this.getCurrentLayoutElement();

            if (layout) {
                window.mw.top().app.editor.dispatch('onModuleSettingsRequest', layout);
            }
        },

        toggleMoreSettings() {
            this.moreSettingsExpanded = !this.moreSettingsExpanded;
        },

        // task-2026-05-16-3ae87c: user-menu integration.
        async fetchUserMenu() {
            try {
                const url = (typeof route === 'function')
                    ? route('api.live-edit.get-top-right-menu')
                    : '/api/live-edit/get-top-right-menu';
                const resp = await fetch(url, { credentials: 'same-origin', headers: { 'Accept': 'application/json' } });
                if (!resp.ok) return;
                const data = await resp.json();
                if (Array.isArray(data)) {
                    // task-2026-05-28-f8a3c1 / AI-1225 — filter "Back to Admin"
                    // which is already present in MainDrawer Navigate section;
                    // showing it here too creates a duplicate nav target and
                    // mixes session/navigation concerns inside the editing-tools
                    // menu. All other top_right_menu items remain.
                    this.userMenuItems = data.filter(item => item.id !== 'js-live-edit-back-to-admin-link');
                }
            } catch (e) {
                // Non-blocking — the toolbar still works without the user menu.
                console.warn('ToolbarToolsDropdown: failed to fetch user menu', e);
            }
        },

        handleToggleDarkMode() {
            this.hideToolsDropdown();
            if (window.mw?.top()?.admin?.theme?.toggle) {
                window.mw.top().admin.theme.toggle();
            }
            // Sync local theme state with the .dark class.
            this.theme = document.documentElement.classList.contains('dark') ? 'dark' : 'light';
        },

        handleMoreSettings() {
            this.hideToolsDropdown();
            const event = new CustomEvent('moreSettingsRequested');
            document.dispatchEvent(event);
        },

        clearCache() {
            this.hideToolsDropdown();
            this.closeFilamentSlideOver();
            mw.confirm("Do you want to clear cache?", function () {
                mw.notification.warning("Clearing cache...");
                $.get(mw.settings.api_url + "clearcache", {}, function () {
                    mw.notification.warning("Cache is cleared! reloading the page...");
                    location.reload();
                });
            });
        },

        hideContentResetDialog() {
            if (this.contentResetContentInstance) {
                this.contentResetContentInstance.remove();
                this.contentResetContentInstance = null;
            }
        },

        // Context menu integration methods
        handleEditTextNode() {
            this.hideToolsDropdown();
            if (this.currentElement && window.mw?.app?.editor?.dispatch) {
                window.mw.app.editor.dispatch('editNodeRequest', this.currentElement);
            }
        },

        handleEditModuleNode() {
            this.hideToolsDropdown();
            if (this.currentElement && window.mw?.app?.editor?.dispatch) {
                if (this.isLayoutElement(this.currentElement)) {
                    window.mw.app.editor.dispatch('onLayoutSettingsRequest', this.currentElement);
                } else {
                    window.mw.app.editor.dispatch('onModuleSettingsRequest', this.currentElement);
                }
            }
        },

        handleInsertModule() {
            this.hideToolsDropdown();
            if (this.currentElement && window.mw?.app?.editor?.dispatch) {
                this.currentElement.scrollIntoView();
                window.mw.app.editor.dispatch('insertModuleRequest', this.currentElement);
            }
        },

        handleInsertLayout: function () {
            this.hideToolsDropdown();
            this.closeFilamentSlideOver();
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

        handleTemplateSettings() {
            this.hideToolsDropdown();
            this.closeFilamentSlideOver();
            mw.top().app.templateSettingsWidget.toggle();
        },

        handleStyleEditor() {
            this.hideToolsDropdown();
            this.closeFilamentSlideOver();
            // Import CSSGUIService dynamically or use global reference
            if (window.mw?.top()?.app?.guiEditorBox) {
                window.mw.top().app.guiEditorBox.toggle();
            }
        },

        handleQuickEdit() {
            this.hideToolsDropdown();
            this.closeFilamentSlideOver();
            mw.app.liveEditWidgets.toggleQuickEditComponent();
        },

        updateCurrentElement() {

            if(!mw.top().app.liveEdit ){
                this.currentElement = null;
                this.resetContextMenuStates();
                return;
            }

            try {
                const activeElement = mw.top().app.liveEdit.elementHandle.getTarget()
                    || window.mw.top().app.liveEdit.getSelectedNode()
                    || window.mw.top().app.liveEdit.getSelectedElementNode();

                if (activeElement !== this.currentElement) {
                    this.currentElement = activeElement;
                    this.updateContextMenuStates();
                }
            } catch (error) {
                console.warn('Error updating current element:', error);
                this.currentElement = null;
                this.resetContextMenuStates();
            }
        },

        updateContextMenuStates() {
            if (!this.currentElement) {
                this.resetContextMenuStates();
                return;
            }

            // Check if text is editable (using same logic as CurrentNodeTextEditButton)
            this.hasEditableTextNode = this.isTextEditable(this.currentElement);

            // Check if module/layout is editable (using same logic as CurrentNodeModuleEditButton)
            this.hasEditableModuleNode = this.isModuleEditable(this.currentElement) || this.isLayoutElement(this.currentElement);

            // Check if we can insert modules (using same logic as CurrentLayoutSettingsButtons)
            this.canInsertModule = this.canInsertModuleIntoElement(this.currentElement);
        },

        resetContextMenuStates() {
            this.hasEditableTextNode = false;
            this.hasEditableModuleNode = false;
            this.canInsertModule = false;
        },

        isTextEditable(element) {
            if (!element || !window.mw?.tools?.isEditable) return false;
            return window.mw.tools.isEditable(element);
        },

        isModuleEditable(element) {
            if (!element) return false;

            const isModule = element.classList.contains('module') ||
                element.hasAttribute('data-type') ||
                element.hasAttribute('data-module');

            if (!isModule) return false;

            // Check if module is inaccessible
            if (this.isModuleInaccessible(element)) {
                return false;
            }

            const moduleType = element.getAttribute('data-type') ||
                element.getAttribute('type') ||
                element.getAttribute('data-module');

            const excludedTypes = ['layouts', 'layout', 'text', 'spacer', 'divider'];
            return moduleType && !excludedTypes.includes(moduleType.toLowerCase());
        },

        isLayoutElement(element) {
            if (!element) return false;

            const isLayout = element.classList.contains('layout') ||
                element.hasAttribute('data-layout') ||
                element.hasAttribute('data-layout-name') ||
                element.classList.contains('module-layouts') ||
                element.classList.contains('edit');

            if (!isLayout) return false;
            return !this.isLayoutInaccessible(element);
        },

        canInsertModuleIntoElement(element) {
            if (!element) return false;

            // Check if element is a layout that can accept modules
            return this.isLayoutElement(element) ||
                   element.classList.contains('edit') ||
                   element.classList.contains('element') ||
                   element.hasAttribute('data-layout-container');
        },

        isModuleInaccessible(moduleElement) {
            if (window.mw?.top()?.app?.liveEdit?.liveEditHelpers?.targetIsInacesibleModule) {
                return window.mw.top().app.liveEdit.liveEditHelpers.targetIsInacesibleModule(moduleElement);
            }
            return moduleElement.classList.contains('no-settings') ||
                moduleElement.classList.contains('inaccessibleModule');
        },

        isLayoutInaccessible(layoutElement) {
            return layoutElement.classList.contains('no-settings') ||
                layoutElement.classList.contains('inaccessibleLayout');
        }
    },

    mounted() {
        // task-2026-05-16-3ae87c: fetch the user-menu items + initial theme.
        this.fetchUserMenu();
        this.theme = document.documentElement.classList.contains('dark') ? 'dark' : 'light';
        // Re-sync theme whenever it changes elsewhere (e.g. the legacy
        // hidden user-menu's own toggleDarkMode in Toolbar.vue or the
        // admin theme switcher).
        const themeObserver = new MutationObserver(() => {
            this.theme = document.documentElement.classList.contains('dark') ? 'dark' : 'light';
        });
        themeObserver.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });

        // Initial setup when app is ready
        mw.app.on('ready', () => {
            this.updateCurrentElement();

            // Set up periodic updates to catch dynamic changes
            setInterval(() => {
                if (this.canShowTools) {
                    this.updateCurrentElement();
                }
            }, 2000);
        });

        mw.app.canvas.on('liveEditCanvasBeforeUnload', () => {
            this.hideToolsDropdown();
            this.hideContentResetDialog();
        });

        // Check if tools should be shown when canvas loads
        mw.app.canvas.on('liveEditCanvasLoaded', () => {
            this.canShowTools = !!mw.app.canvas.getLiveEditData();
            this.canShowSettingsCustomize = !!mw.app.canvas.getLiveEditData();
            this.insertLayoutVisible = !!mw.app.canvas.getDocument().querySelector('.edit[data-layout-container]');
            this.updateCurrentElement();

            // Close dropdown when various events happen
            mw.app.editor.on('insertLayoutRequest', () => {
                this.hideToolsDropdown();
                this.hideContentResetDialog();
            });

            mw.app.editor.on('insertModuleRequest', () => {
                this.hideToolsDropdown();
                this.hideContentResetDialog();
            });

            // Listen for element changes to update context menu states
            mw.app.editor.on('editNodeRequest', () => {
                setTimeout(() => this.updateCurrentElement(), 100);
            });

            mw.app.editor.on('editNodeEnd', () => {
                setTimeout(() => this.updateCurrentElement(), 100);
            });

            mw.app.editor.on('onModuleSettingsRequest', () => {
                setTimeout(() => this.updateCurrentElement(), 100);
            });

            mw.app.editor.on('onModuleSettingsEnd', () => {
                setTimeout(() => this.updateCurrentElement(), 100);
            });
        });

        // Handle canvas clicks to update current element
        mw.app.canvas.on('canvasDocumentClick', () => {
            this.hideToolsDropdown();
            setTimeout(() => this.updateCurrentElement(), 100);
        });

        // Handle layers state changes
        if (mw.top().app.liveEditWidgets) {
            const handleLayersChange = () => {
                this.layers = mw.top().app.liveEditWidgets.status.layersOpened;
            };
            mw.top().app.liveEditWidgets.on('layersOpen', handleLayersChange);
            mw.top().app.liveEditWidgets.on('layersClose', handleLayersChange);
        }

        // Handle DOM tree events
        mw.top().app.on('DOMTreeReady', () => {
            mw.top().app.domTree.on('show', () => {
                this.layers = true;
            });
            mw.top().app.domTree.on('hide', () => {
                this.layers = false;
            });
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', (event) => {
            if (this.dropdownOpen && !this.$el.contains(event.target)) {
                this.hideToolsDropdown();
            }
        });

        // Set up event listeners for button state management
        this.emitter.on("live-edit-ui-show", (show) => {
            if (show == 'template-settings') {
                this.buttonIsActive = true;
                this.buttonIsActiveStyleEditor = false;
            } else if (show == 'style-editor') {
                this.buttonIsActive = false;
                this.buttonIsActiveStyleEditor = true;
            } else if (show == 'template-settings-close') {
                this.buttonIsActive = false;
                this.buttonIsActiveStyleEditor = false;
            } else {
                this.buttonIsActive = false;
                this.buttonIsActiveStyleEditor = false;
            }
        });

        // Template settings widget events
        mw.top().app.templateSettingsWidget.on('show', () => {
            this.buttonIsActive = true;
            mw.top().app.liveEditWidgets.closeQuickEditComponent();
        });
        mw.top().app.templateSettingsWidget.on('hide', () => this.buttonIsActive = false);

        // Style editor events
        mw.top().app.guiEditorBox.on('show', () => {
            this.buttonIsActiveStyleEditor = true;
            mw.top().app.liveEditWidgets.closeQuickEditComponent();
        });
        mw.top().app.guiEditorBox.on('hide', () => this.buttonIsActiveStyleEditor = false);

        // Quick edit events
        mw.top().app.readyPromise(() => {
            mw.top().app.liveEditWidgets.on('openQuickEditComponent', () => {
                this.buttonIsActiveQuickEdit = true;
            });

            mw.top().app.liveEditWidgets.on('closeQuickEditComponent', () => {
                this.buttonIsActiveQuickEdit = false;
            });
        });
    }
}
</script>
