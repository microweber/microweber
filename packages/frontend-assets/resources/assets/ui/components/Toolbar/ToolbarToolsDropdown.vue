<template>
    <div class="mx-2" v-if="canShowTools">
        <div class="custom-dropdown">
            <a role="button" aria-label="Tools menu" class="dropdown-trigger"
               :aria-expanded="dropdownOpen.toString()" @click.prevent="toggleDropdown">
                <svg fill="currentColor" height="20" viewBox="0 -960 960 960" width="20" xmlns="http://www.w3.org/2000/svg">
                    <path d="M480-160q-33 0-56.5-23.5T400-240q0-33 23.5-56.5T480-320q33 0 56.5 23.5T560-240q0 33-23.5 56.5T480-160Zm0-240q-33 0-56.5-23.5T400-480q0-33 23.5-56.5T480-560q33 0 56.5 23.5T560-480q0 33-23.5 56.5T480-400Zm0-240q-33 0-56.5-23.5T400-720q0-33 23.5-56.5T480-800q33 0 56.5 23.5T560-720q0 33-23.5 56.5T480-640Z"/>
                </svg>

                <span class="hidden">Tools</span>

            </a>

            <ul class="dropdown-content" :class="{ 'show': dropdownOpen }"
                ref="toolsDropdownContent">





                <li>
                    <a @click="handleInsertLayout" v-show="insertLayoutVisible">
                        <svg fill="currentColor" height="20" viewBox="0 -960 960 960" width="20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-480H200v480Zm80-80h200v-120H280v120Zm240 0h200v-280H520v280Zm-240-160h200v-120H280v120Z"/>
                        </svg>
                        Insert Layout
                    </a>
                </li>
                <li>
                    <a @click="handleInsertModule" v-show="canInsertModule">
                        <svg fill="currentColor" height="20" viewBox="0 -960 960 960" width="20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M440-120v-320H120v-80h320v-320h80v320h320v80H520v320h-80Z"/>
                        </svg>
                        Insert Module
                    </a>
                </li>
                <li>
                    <a @click="handleEditTextNode" v-show="hasEditableTextNode">
                        <svg fill="currentColor" height="20" viewBox="0 0 24 24" width="20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                        </svg>
                        Edit Element
                    </a>
                </li>
                <li>
                    <a @click="handleEditModuleNode" v-show="hasEditableModuleNode">
                        <svg fill="currentColor" height="20" viewBox="0 0 24 24" width="20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 15.5A3.5 3.5 0 0 1 8.5 12A3.5 3.5 0 0 1 12 8.5a3.5 3.5 0 0 1 3.5 3.5a3.5 3.5 0 0 1-3.5 3.5m7.43-2.53c.04-.32.07-.64.07-.97c0-.33-.03-.66-.07-1l2.11-1.63c.19-.15.24-.42.12-.64l-2-3.46c-.12-.22-.39-.31-.61-.22l-2.49 1c-.52-.39-1.06-.73-1.69-.98l-.37-2.65A.506.506 0 0 0 14 2h-4c-.25 0-.46.18-.5.42l-.37 2.65c-.63.25-1.17.59-1.69.98l-2.49-1c-.22-.09-.49 0-.61.22l-2 3.46c-.13.22-.07.49.12.64L4.57 11c-.04.34-.07.67-.07 1c0 .33.03.65.07.97l-2.11 1.66c-.19.15-.25.42-.12.64l2 3.46c.12.22.39.3.61.22l2.49-1.01c.52.4 1.06.74 1.69.99l.37 2.65c.04.24.25.42.5.42h4c.25 0 .46-.18.5-.42l.37-2.65c.63-.26 1.17-.59 1.69-.99l2.49 1.01c.22.08.49 0 .61-.22l2-3.46c.12-.22.07-.49-.12-.64l-2.11-1.66Z"/>
                        </svg>
                        Module Settings
                    </a>
                </li>

                <li>
                    <a @click="handleTemplateSettings" v-show="canShowSettingsCustomize" :class="{ active: buttonIsActive && !buttonIsActiveStyleEditor }">
                        <svg fill="currentColor" height="20" viewBox="96 96 960 960" width="20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M480 976q-82 0-155-31.5t-127.5-86Q143 804 111.5 731T80 576q0-83 32.5-156t88-127Q256 239 330 207.5T488 176q80 0 151 27.5t124.5 76q53.5 48.5 85 115T880 538q0 115-70 176.5T640 776h-74q-9 0-12.5 5t-3.5 11q0 12 15 34.5t15 51.5q0 50-27.5 74T480 976Zm0-400Zm-220 40q26 0 43-17t17-43q0-26-17-43t-43-17q-26 0-43 17t-17 43q0 26 17 43t43 17Zm120-160q26 0 43-17t17-43q0-26-17-43t-43-17q-26 0-43 17t-17 43q0 26 17 43t43 17Zm200 0q26 0 43-17t17-43q0-26-17-43t-43-17q-26 0-43 17t-17 43q0 26 17 43t43 17Zm120 160q26 0 43-17t17-43q0-26-17-43t-43-17q-26 0-43 17t-17 43q0 26 17 43t43 17ZM480 896q9 0 14.5-5t5.5-13q0-14-15-33t-15-57q0-42 29-67t71-25h70q66 0 113-38.5T800 538q0-121-92.5-201.5T488 256q-136 0-232 93t-96 227q0 133 93.5 226.5T480 896Z"/>
                        </svg>
                        Template Settings
                    </a>
                </li>

                <li>
                    <a @click="handleStyleEditor" v-show="canShowSettingsCustomize" :class="{ active: !buttonIsActive && buttonIsActiveStyleEditor }">
                        <svg fill="currentColor" height="20" viewBox="0 -960 960 960" width="20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M480-120q-133 0-226.5-92T160-436q0-65 25-121.5T254-658l226-222 226 222q44 44 69 100.5T800-436q0 132-93.5 224T480-120ZM242-400h474q12-72-13.5-123T650-600L480-768 310-600q-27 26-53 77t-15 123Z"/>
                        </svg>
                        Style Editor
                    </a>
                </li>

                <li>
                    <a @click="handleQuickEdit" v-show="canShowSettingsCustomize" :class="{ active: buttonIsActiveQuickEdit }">
                        <svg clip-rule="evenodd" fill="currentColor" fill-rule="evenodd" height="20px" image-rendering="optimizeQuality" shape-rendering="geometricPrecision" text-rendering="geometricPrecision" viewBox="0 0 512 472.403" width="20px" xmlns="http://www.w3.org/2000/svg">
                            <path d="M144.317 85.269h223.368c15.381 0 29.391 6.325 39.567 16.494l.025-.024c10.163 10.164 16.477 24.193 16.477 39.599v189.728c0 15.401-6.326 29.425-16.485 39.584-10.159 10.159-24.183 16.484-39.584 16.484H144.317c-15.4 0-29.437-6.313-39.601-16.476-10.152-10.152-16.47-24.167-16.47-39.592V141.338c0-15.374 6.306-29.379 16.463-39.558l.078-.078c10.178-10.139 24.168-16.433 39.53-16.433zm59.98 204.329h-39.825l30.577-117.964h58.32l30.577 117.964h-39.825l-3.051-18.686h-33.725l-3.048 18.686zm15.645-81.726l-5.801 33.032h19.945l-5.61-33.032h-8.534zm74.007 81.726V171.634h37.749v117.964h-37.749zm161.348-35.797v30.763c0 3.165 2.587 5.751 5.752 5.751h45.199c3.165 0 5.752-2.586 5.752-5.751v-30.763c0-3.165-2.587-5.752-5.752-5.752h-45.199c-3.165 0-5.752 2.587-5.752 5.752zm0-70.639v30.762c0 3.163 2.587 5.752 5.752 5.752h45.199c3.165 0 5.752-2.589 5.752-5.752v-30.762c0-3.168-2.587-5.752-5.752-5.752h-45.199c-3.165 0-5.752 2.584-5.752 5.752zm0 141.278v30.763c0 3.165 2.587 5.752 5.752 5.752h45.199c3.165 0 5.752-2.587 5.752-5.752V324.44c0-3.165-2.587-5.751-5.752-5.751h-45.199c-3.165 0-5.752 2.586-5.752 5.751zm0-211.92v30.763c0 3.164 2.587 5.751 5.752 5.751h45.199c3.165 0 5.752-2.587 5.752-5.751V112.52c0-3.165-2.587-5.752-5.752-5.752h-45.199c-3.165 0-5.752 2.587-5.752 5.752zM56.703 253.801v30.763c0 3.165-2.587 5.751-5.752 5.751H5.752c-3.165 0-5.752-2.586-5.752-5.751v-30.763c0-3.165 2.587-5.752 5.752-5.752h45.199c3.165 0 5.752 2.587 5.752 5.752zm0-70.639v30.762c0 3.163-2.587 5.752-5.752 5.752H5.752c-3.165 0-5.752-2.589-5.752-5.752v-30.762c0-3.168 2.587-5.752 5.752-5.752h45.199c3.165 0 5.752 2.584 5.752 5.752zm0 141.278v30.763c0 3.165-2.587 5.752-5.752 5.752H5.752c-3.165 0-5.752-2.587-5.752-5.752V324.44c0-3.165 2.587-5.751 5.752-5.751h45.199c3.165 0 5.752 2.586 5.752 5.751zm0-211.92v30.763c0 3.164-2.587 5.751-5.752 5.751H5.752c-3.165 0-5.752-2.587-5.752-5.751V112.52c0-3.165 2.587-5.752 5.752-5.752h45.199c3.165 0 5.752 2.587 5.752 5.752zM346.579 415.7h30.763c3.162 0 5.751 2.587 5.751 5.752v45.199c0 3.165-2.589 5.752-5.751 5.752h-30.763c-3.167 0-5.752-2.587-5.752-5.752v-45.199c0-3.165 2.585-5.752 5.752-5.752zm-70.642 0H306.7c3.165 0 5.751 2.587 5.751 5.752v45.199c0 3.165-2.586 5.752-5.751 5.752h-30.763c-3.165 0-5.752-2.587-5.752-5.752v-45.199c0-3.165 2.587-5.752 5.752-5.752zm-70.639 0h30.762c3.165 0 5.752 2.587 5.752 5.752v45.199c0 3.165-2.587 5.752-5.752 5.752h-30.762c-3.165 0-5.752-2.587-5.752-5.752v-45.199c0-3.165 2.587-5.752 5.752-5.752zm-70.64 0h30.763c3.165 0 5.752 2.587 5.752 5.752v45.199c0 3.165-2.587 5.752-5.752 5.752h-30.763c-3.165 0-5.751-2.587-5.751-5.752v-45.199c0-3.165 2.586-5.752 5.751-5.752zM346.579 0h30.763c3.162 0 5.751 2.587 5.751 5.752v45.199c0 3.165-2.589 5.752-5.751 5.752h-30.763c-3.167 0-5.752-2.587-5.752-5.752V5.752c0-3.165 2.585-5.752 5.752-5.752zm-70.642 0H306.7c3.165 0 5.751 2.587 5.751 5.752v45.199c0 3.165-2.586 5.752-5.751 5.752h-30.763c-3.165 0-5.752-2.587-5.752-5.752V5.752c0-3.165 2.587-5.752 5.752-5.752zm-70.639 0h30.762c3.165 0 5.752 2.587 5.752 5.752v45.199c0 3.165-2.587 5.752-5.752 5.752h-30.762c-3.165 0-5.752-2.587-5.752-5.752V5.752c0-3.165 2.587-5.752 5.752-5.752zm-70.64 0h30.763c3.165 0 5.752 2.587 5.752 5.752v45.199c0 3.165-2.587 5.752-5.752 5.752h-30.763c-3.165 0-5.751-2.587-5.751-5.752V5.752c0-3.165 2.586-5.752 5.751-5.752zm233.027 111.097H144.317a30.11 30.11 0 00-21.35 8.844l-.049.049a30.117 30.117 0 00-8.844 21.348v189.728c0 8.292 3.414 15.847 8.9 21.333 5.494 5.493 13.058 8.907 21.343 8.907h223.368c8.273 0 15.833-3.421 21.326-8.914s8.915-13.053 8.915-21.326V141.338c0-8.283-3.414-15.848-8.908-21.341v-.049c-5.454-5.456-13.006-8.851-21.333-8.851z" fill-rule="nonzero"/>
                        </svg>
                        Quick AI Edit
                    </a>
                </li>

                <li>
                    <a @click="handleCurrentLayoutSettings">
                        <svg fill="currentColor" height="20" viewBox="0 -960 960 960" width="20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M440-120v-240h80v80h320v80H520v80h-80Zm-320-80v-80h240v80H120Zm160-160v-80H120v-80h160v-80h80v240h-80Zm160-80v-80h400v80H440Zm160-160v-240h80v80h160v80H680v80h-80Zm-480-80v-80h400v80H120Z"/>
                        </svg>
                        Layout Settings
                    </a>
                </li>

                <li>
                    <a @click="openSetupWizard">
                        <svg fill="currentColor" viewBox="0 -960 960 960" xmlns="http://www.w3.org/2000/svg">
                            <path d="m176-120-56-56 301-302-181-45 198-123-17-234 179 151 216-88-87 217 151 178-234-16-124 198-45-181-301 301Zm24-520-80-80 80-80 80 80-80 80Zm355 197 48-79 93 7-60-71 35-86-86 35-71-59 7 92-79 49 90 22 23 90Zm165 323-80-80 80-80 80 80-80 80ZM569-570Z"/>
                        </svg>
                        Setup Wizard
                    </a>
                </li>



                <!-- More Settings Section -->
                <li class="separator">
                    <hr>
                </li>

                <li class="more-settings-item">
                    <a @click="toggleMoreSettings" :class="{ expanded: moreSettingsExpanded }">
                        <svg fill="currentColor" height="20" viewBox="0 -960 960 960" width="20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M480-160q-33 0-56.5-23.5T400-240q0-33 23.5-56.5T480-320q33 0 56.5 23.5T560-240q0 33-23.5 56.5T480-160Zm0-240q-33 0-56.5-23.5T400-480q0-33 23.5-56.5T480-560q33 0 56.5 23.5T560-480q0 33-23.5 56.5T480-400Zm0-240q-33 0-56.5-23.5T400-720q0-33 23.5-56.5T480-800q33 0 56.5 23.5T560-720q0 33-23.5 56.5T480-640Z"/>
                        </svg>
                        More Settings
                        <svg class="expand-arrow" :class="{ rotated: moreSettingsExpanded }" fill="currentColor" height="16" viewBox="0 0 24 24" width="16" xmlns="http://www.w3.org/2000/svg">
                            <path d="M8.59 16.34l4.58-4.59-4.58-4.59L10 5.75l6 6-6 6z"/>
                        </svg>
                    </a>
                </li>

                <!--
                    task-2026-05-16-3ae87c: user-menu items merged in here.
                    The separate "Menu" button previously held Report
                    issue / See website / Log out + the light/dark mode
                    toggle. The user said "we dont need 2 dordowns" so
                    those items now live at the bottom of THIS dropdown,
                    separated from the tools above by a visual divider.
                    The original `#user-menu-wrapper` stays in the DOM
                    hidden — see Toolbar.vue for the back-compat note.
                -->
                <li class="separator" v-if="userMenuItems.length > 0">
                    <hr>
                </li>

                <li v-for="(menuItem, idx) in userMenuItems" :key="'um-' + idx">
                    <a
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

                <li>
                    <a @click="handleToggleDarkMode" role="button">
                        <svg v-if="theme === 'light'" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 96 960 960" width="20">
                            <path d="M480 936q-150 0-255-105T120 576q0-150 105-255t255-105q14 0 27.5 1t26.5 3q-41 29-65.5 75.5T444 396q0 90 63 153t153 63q55 0 101-24.5t75-65.5q2 13 3 26.5t1 27.5q0 150-105 255T480 936Zm0-80q88 0 158-48.5T740 681q-20 5-40 8t-40 3q-123 0-209.5-86.5T364 396q0-20 3-40t8-40q-78 32-126.5 102T200 576q0 116 82 198t198 82Zm-10-270Z"/>
                        </svg>
                        <svg v-else height="20" width="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path d="M10 2a.75.75 0 0 1 .75.75v1.5a.75.75 0 0 1-1.5 0v-1.5A.75.75 0 0 1 10 2ZM10 15a.75.75 0 0 1 .75.75v1.5a.75.75 0 0 1-1.5 0v-1.5A.75.75 0 0 1 10 15ZM10 7a3 3 0 1 0 0 6 3 3 0 0 0 0-6ZM15.657 5.404a.75.75 0 1 0-1.06-1.06l-1.061 1.06a.75.75 0 0 0 1.06 1.06l1.06-1.06ZM6.464 14.596a.75.75 0 1 0-1.06-1.06l-1.06 1.06a.75.75 0 0 0 1.06 1.06l1.06-1.06ZM18 10a.75.75 0 0 1-.75.75h-1.5a.75.75 0 0 1 0-1.5h1.5A.75.75 0 0 1 18 10ZM5 10a.75.75 0 0 1-.75.75h-1.5a.75.75 0 0 1 0-1.5h1.5A.75.75 0 0 1 5 10ZM14.596 15.657a.75.75 0 0 0 1.06-1.06l-1.06-1.061a.75.75 0 1 0-1.06 1.06l1.06 1.06ZM5.404 6.464a.75.75 0 0 0 1.06-1.06l-1.06-1.06a.75.75 0 1 0-1.061 1.06l1.06 1.06Z"/>
                        </svg>
                        {{ theme === 'light' ? 'Dark mode' : 'Light mode' }}
                    </a>
                </li>

                <!-- Expandable More Settings Content -->
                <li class="more-settings-content" v-show="moreSettingsExpanded">
                    <ul class="submenu">
                        <li>
                            <a @click="handleLayers" :class="{ active: layers }">
                                <svg fill="currentColor" height="18" viewBox="0 -960 960 960" width="18" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M480-400 40-640l440-240 440 240-440 240Zm0 160L63-467l84-46 333 182 333-182 84 46-417 227Zm0 160L63-307l84-46 333 182 333-182 84 46L480-80Zm0-411 273-149-273-149-273 149 273 149Zm0-149Z"/>
                                </svg>
                                Layers
                            </a>
                        </li>

                        <li>
                            <a @click="showCodeEditor">
                                <svg fill="currentColor" height="18" viewBox="0 -960 960 960" width="18" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M0-360v-240h60v80h80v-80h60v240h-60v-100H60v100H0Zm310 0v-180h-70v-60h200v60h-70v180h-60Zm170 0v-200q0-17 11.5-28.5T520-600h180q17 0 28.5 11.5T740-560v200h-60v-180h-40v140h-60v-140h-40v180h-60Zm320 0v-240h60v180h100v60H800Z"/>
                                </svg>
                                Code Editor
                            </a>
                        </li>

                        <li>
                            <a @click="openContentResetContent">
                                <svg fill="currentColor" height="18" viewBox="0 -960 960 960" width="18" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M440-122q-121-15-200.5-105.5T160-440q0-66 26-126.5T260-672l57 57q-38 34-57.5 79T240-440q0 88 56 155.5T440-202v80Zm80 0v-80q87-16 143.5-83T720-440q0-100-70-170t-170-70h-3l44 44-56 56-140-140 140-140 56 56-44 44h3q134 0 227 93t93 227q0 121-79.5 211.5T520-122Z"/>
                                </svg>
                                Reset Content
                            </a>
                        </li>

                        <li>
                            <a @click="clearCache">
                                <svg fill="currentColor" height="18" viewBox="0 -960 960 960" width="18" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M280-720v520-520Zm170 600H280q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v172q-17-5-39.5-8.5T680-560v-160H280v520h132q6 21 16 41.5t22 38.5Zm-90-160h40q0-63 20-103.5l20-40.5v-216h-80v360Zm160-230q17-11 38.5-22t41.5-16v-92h-80v130ZM680-80q-83 0-141.5-58.5T480-280q0-83 58.5-141.5T680-480q83 0 141.5 58.5T880-280q0 83-58.5 141.5T680-80Zm66-106 28-28-74-74v-112h-40v128l86 86Z"/>
                                </svg>
                                Clear Cache
                            </a>
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
    padding: 8px 14px 8px 10px;
    display: flex;
    align-items: center;
    gap: 8px;
    background: #fff;
    border-radius: 6px;
    border: 1px solid #efefef;
    box-shadow: 0 1px 2px rgba(0,0,0,0.03);
    transition: border 0.2s, box-shadow 0.2s;
    font-weight: 500;
    color: #222;
    outline: none;
    position: relative;
    max-height: 35px;
    width: auto;
    min-width: unset;
}

.dropdown-trigger:focus,
.dropdown-trigger:hover {
    border: 1.5px solid #b3b3b3;
    box-shadow: 0 2px 8px rgba(0,0,0,0.07);
    background: #fafbfc;
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

.dropdown-content li a {
    display: flex;
    align-items: center;
    padding: 10px 20px;
    text-decoration: none;
    color: #222;
    cursor: pointer;
    font-size: 15px;
    border-radius: 5px;
    transition: background 0.15s, color 0.15s;
    gap: 10px;
}

.dropdown-content li a:hover,
.dropdown-content li a:focus {
    background-color: #f0f4fa;
    color: #1976d2;
}

.dropdown-content li a.active {
    background-color: #e3f2fd;
    color: #1976d2;
    font-weight: 600;
}

.dropdown-content li a svg {
    width: 20px;
    height: 20px;
    flex-shrink: 0;
}

/* Dark mode styles */
html.dark .dropdown-trigger {
    background: rgb(24, 36, 51);
    border: 1px solid #374151;
    color: #e5e7eb;
}

html.dark .dropdown-trigger:hover,
html.dark .dropdown-trigger:focus {
    border: 1.5px solid #6b7280;
    background: rgb(31, 41, 55);
}

html.dark .dropdown-content {
    background-color: rgb(24, 36, 51);
    border: 1px solid #374151;
}

html.dark .dropdown-content li a {
    color: #e5e7eb;
}

html.dark .dropdown-content li a:hover,
html.dark .dropdown-content li a:focus {
    background-color: rgb(31, 41, 55);
    color: #ffffff;
}

html.dark .dropdown-content li a.active {
    background-color: rgb(37, 99, 235, 0.2);
    color: #60a5fa;
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

html.dark .separator hr {
    background-color: #374151;
}

/* More Settings expandable styling */
.more-settings-item a {
    position: relative;
    justify-content: space-between;
}

.more-settings-item a.expanded {
    background-color: #f0f4fa;
    color: #1976d2;
}

html.dark .more-settings-item a.expanded {
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

html.dark .submenu {
    background-color: rgba(255, 255, 255, 0.03);
}

.submenu li a {
    padding: 8px 16px 8px 24px;
    font-size: 14px;
    border-radius: 4px;
    margin: 2px 4px;
}

.submenu li a svg {
    width: 18px;
    height: 18px;
}

.submenu li a:hover,
.submenu li a:focus {
    background-color: rgba(25, 118, 210, 0.1);
    color: #1976d2;
}

html.dark .submenu li a:hover,
html.dark .submenu li a:focus {
    background-color: rgba(96, 165, 250, 0.1);
    color: #60a5fa;
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
        toggleDropdown() {
            this.dropdownOpen = !this.dropdownOpen;
        },

        hideToolsDropdown() {
            this.dropdownOpen = false;
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
                if (Array.isArray(data)) this.userMenuItems = data;
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
            // Sync local theme state with the html.dark class.
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
