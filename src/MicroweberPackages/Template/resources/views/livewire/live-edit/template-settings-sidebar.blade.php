<div>


    @include('admin::layouts.partials.loads-user-custom-fonts')


    <div wire:ignore>
        <style>
            .fi-main-ctn {
                background: transparent !important;
            }
        </style>

        <script>
            function resetTemplateSettings() {
                var askForConfirmText = '<div class="">' +
                    '<h4 class="">' + mw.lang('Are you sure you want to reset template settings ?') + '</h4>' +

                    '</div>';

                mw.tools.confirm_reset_module_by_id('{{$optionGroup}}', function () {
                    // Reset template settings
                }, askForConfirmText);

            }

            function resetAllDeignSelectorsValuesSettings() {
                var askForConfirmText = mw.lang('Are you sure you want to reset stylesheet settings ?');


                var confirmed = confirm(askForConfirmText);

                if (!confirmed) {
                    return;
                }


                let desinSelectors = window.mw_template_settings_styles_and_selectors;
                if (desinSelectors) {
                    desinSelectors = JSON.parse(JSON.stringify(desinSelectors));


                    desinSelectors = preapareAndCleanTemplateStylesAndSelectorsData(desinSelectors);

                    var valuesForEdit = preapareTemplateValuesForEdit(desinSelectors);


                    // FIX: Loop over object keys instead of using for...of
                    for (const selector in valuesForEdit) {
                        if (valuesForEdit.hasOwnProperty(selector)) {
                            const properties = valuesForEdit[selector];
                            for (const property in properties) {
                                if (properties.hasOwnProperty(property)) {
                                    const value = properties[property];

                                    // Apply the property to the selector using the CSS editor
                                    if (mw.top().app.cssEditor) {
                                        // Determine unit if needed (you might need to adjust this based on property type)
                                        const unit = property.includes('color') ? '' : '';

                                        // Apply the CSS property
                                        mw.top().app.cssEditor.setPropertyForSelector(
                                            selector,
                                            property,
                                            '', // Reset value to empty
                                            true,
                                            true
                                        );
                                    }
                                }
                            }
                        }
                    }
                }
            }

            function resetStylesheetSettings() {
                var askForConfirmText = '<div class="">' +
                    '<h4 class="">' + mw.lang('Are you sure you want to reset stylesheet settings ?') + '</h4>' +

                    '</div>';

                mw.tools.confirm_reset_module_by_id('{{$optionGroupLess}}', function () {
                    // Reset template settings
                    mw.top().app.templateSettings.reloadStylesheet('{{$styleSheetSourceFile}}', '{{$optionGroupLess}}');
                    setTimeout(function () {
                        mw.top().win.location.reload();
                    }, 3000)

                }, askForConfirmText);

            }


            mw.top().app.on('fontsChanged', function ($event) {


                if (mw.top().app.fontManager) {
                    mw.top().app.fontManager.reloadLiveEdit();
                }


                // var customFontsStylesheet = mw.top().app.canvas.getDocument().getElementById("mw-custom-user-css");
                // if (customFontsStylesheet != null) {
                //     var customFontsStylesheetRestyle = mw.settings.api_url + 'template/print_custom_css?time=' + Math.random(0, 10000);
                //     customFontsStylesheet.href = customFontsStylesheetRestyle;
                // }

                setTimeout(function () {
                    mw.top().app.templateSettings.reloadStylesheet('{{$styleSheetSourceFile}}', '{{$optionGroupLess}}');
                }, 1000);


            });
        </script>

        <script>
            function openRTECSsEditor2(settings) {

                console.log('openRTECSsEditor2', settings);

                let iframeStyleEdiorId = 'iframeStyleEditorId-1';
                let checkIframeStyleEditor = document.getElementById(iframeStyleEdiorId);

                if (!checkIframeStyleEditor) {
                    var moduleType = 'microweber/toolbar/editor_tools/rte_css_editor2';
                    var attrsForSettings = {};

                    attrsForSettings.live_edit = true;
                    attrsForSettings.module_settings = true;
                    attrsForSettings.id = 'mw_global_rte_css_editor2_editor';
                    attrsForSettings.type = moduleType;
                    attrsForSettings.iframe = true;
                    attrsForSettings.disable_auto_element_change = true;
                    attrsForSettings.output_static_selector = true;
                    attrsForSettings.from_url = mw.top().app.canvas.getWindow().location.href;

                    var src = route('live_edit.module_settings') + "?" + json2url(attrsForSettings);

                    $('#iframe-holder').html('<iframe id="' + iframeStyleEdiorId + '" src="' + src + '" style="width:100%;height:500px;border:none;"></iframe>');

                    document.getElementById(iframeStyleEdiorId).addEventListener('load', function (e) {
                        // alert('iframe loaded');
                        // alert(settings.selectors[0]);
                        mw.top().app.dispatch('cssEditorSelectElementBySelector', settings.selectors[0]);
                    });

                } else {
                    mw.top().app.dispatch('cssEditorSelectElementBySelector', settings.selectors[0]);
                }

            }

            function openRTECSsEditor2Vue(settings) {

                let iframeStyleEdiorId = 'iframeStyleEditorId-Vue';
                let checkIframeStyleEditor = document.getElementById(iframeStyleEdiorId);

                // //temp fix
                // if (checkIframeStyleEditor) {
                //     $('#'+iframeStyleEdiorId).remove();
                //     checkIframeStyleEditor = null;
                // }

                if (!checkIframeStyleEditor) {
                    var moduleType = 'microweber/toolbar/editor_tools/rte_css_editor2/rte_editor_vue';
                    var attrsForSettings = {};

                    attrsForSettings.live_edit = true;
                    attrsForSettings.module_settings = true;
                    attrsForSettings.id = 'mw_global_rte_css_editor2_editor_vue';
                    attrsForSettings.type = moduleType;
                    attrsForSettings.iframe = true;
                    attrsForSettings.disable_auto_element_change = true;
                    attrsForSettings.output_static_selector = true;
                    attrsForSettings.from_url = mw.top().app.canvas.getWindow().location.href;

                    var src = route('live_edit.module_settings') + "?" + json2url(attrsForSettings);

                    $('#iframe-holder').append('<iframe id="' + iframeStyleEdiorId + '" src="' + src + '" style="width:100%;height:500px;border:none;"></iframe>');

                    document.getElementById(iframeStyleEdiorId).addEventListener('load', function (e) {
                        // alert('iframe loaded');
                        // alert(settings.selectors[0]);
                        mw.top().app.dispatch('cssEditorSelectElementBySelector', settings.selectors[0]);
                        mw.top().app.dispatch('cssEditorSettings', settings);
                    });

                } else {
                    mw.top().app.dispatch('cssEditorSelectElementBySelector', settings.selectors[0]);
                    mw.top().app.dispatch('cssEditorSettings', settings);
                }

                // console.log(settings);
            }

            mw.top().app.on('mw.rte.css.editor2.open', function (e) {
                // openRTECSsEditor2(e);
                openRTECSsEditor2Vue(e);
            });

            document.addEventListener('DOMContentLoaded', function () {

                if (mw.top().app && mw.top().app.canvas) {


                    var activeLayout = mw.top().app.liveEdit.handles.get('layout').getTarget();
                    var activeElement = mw.top().app.liveEdit.handles.get('element').getTarget();

                    if (!activeLayout) {
                        if (activeElement) {
                            activeLayout = activeElement.closest('.module-layouts');
                        }
                    }
                    var activeElement = mw.top().app.liveEdit.handles.get('element').getTarget();
                    window.css_vars_design_active_layout = activeLayout;





                    mw.top().app.canvas.on('canvasDocumentClick', () => {
                         var activeLayout = mw.top().app.liveEdit.handles.get('layout').getTarget();
                         var activeElement = mw.top().app.liveEdit.handles.get('element').getTarget();

                         if (!activeLayout) {
                             if (activeElement) {
                                 activeLayout = activeElement.closest('.module-layouts');
                             }
                         }
                        var activeElement = mw.top().app.liveEdit.handles.get('element').getTarget();
                        window.css_vars_design_active_layout = activeLayout;


                    })
                }
            });

        </script>


        @if(isset($styleSettings) and $styleSettings)

            <script>


                //ai-change-template-design-button

                document.addEventListener('DOMContentLoaded', function () {


                    if (typeof mw.top().win.MwAi === 'function') {
                        $('.ai-change-template-design-button').removeClass('d-none')
                    }


                    //add chanfe for css_vars_design_apply_mode

                    $('#css_vars_design_apply_mode').on('change', function () {
                        var selectedValue = $(this).val();


                        window.css_vars_design_apply_mode = selectedValue;



                        var activeLayout = mw.top().app.liveEdit.handles.get('layout').getTarget();
                        var activeElement = mw.top().app.liveEdit.handles.get('element').getTarget();

                        if (!activeLayout) {
                            if (activeElement) {
                                activeLayout = activeElement.closest('.module-layouts');
                            }
                        }
                        var activeElement = mw.top().app.liveEdit.handles.get('element').getTarget();
                        window.css_vars_design_active_layout = activeLayout;





                    });


                });





                window.mw_template_settings_styles_and_selectors = @json($styleSettings)





                window.css_vars_design_apply_mode = 'template';




                function preapareAndCleanTemplateStylesAndSelectorsData(items) {
                    if (!Array.isArray(items)) return items;

                    return items.filter(item => {
                        // Remove items with fieldType clearAll
                        if (item.fieldType === 'clearAll') return false;

                        // Clean unwanted properties
                        ['readSettingsFromFiles', 'parent', 'backUrl', 'url'].forEach(prop => {
                            if (item.hasOwnProperty(prop)) delete item[prop];
                        });

                        // Clean nested settings
                        if (item.settings && Array.isArray(item.settings)) {
                            item.settings = preapareAndCleanTemplateStylesAndSelectorsData(item.settings);
                        }

                        // Clean nested fieldSettings if it's an array
                        if (item.fieldSettings && Array.isArray(item.fieldSettings)) {
                            item.fieldSettings = preapareAndCleanTemplateStylesAndSelectorsData(item.fieldSettings);
                        }

                        return item.settings?.length > 0 || item.fieldSettings || item.selectors;
                    });
                }


                function preapareTemplateValuesForEdit(designSelectors) {
                    // Filter out items without settings after cleaning
                    designSelectors = designSelectors.filter(item => {
                        return item.settings && Array.isArray(item.settings) && item.settings.length > 0;
                    });

                    // Array to collect all selector-property combinations
                    let allSelectorPropertyPairs = [];

                    // Collect all selector-property pairs
                    for (let i = 0; i < designSelectors.length; i++) {
                        let item = designSelectors[i];

                        // Process nested settings
                        for (let k = 0; k < item.settings.length; k++) {
                            let setting = item.settings[k];

                            if (setting.selectors && setting.selectors.length > 0 && setting.fieldSettings) {
                                const nestedSelector = setting.selectors[0];

                                // Handle nested fieldSettings as an object
                                if (!Array.isArray(setting.fieldSettings) && typeof setting.fieldSettings === 'object') {
                                    const property = setting.fieldSettings.property;
                                    if (property) {
                                        allSelectorPropertyPairs.push({
                                            selector: nestedSelector,
                                            property: property,
                                            target: {
                                                object: setting.fieldSettings,
                                                key: 'value'
                                            },
                                            layout: setting.layout || item.layout // collect layout info if present
                                        });
                                    }
                                }
                                // Handle nested fieldSettings as an array
                                else if (Array.isArray(setting.fieldSettings) && setting.fieldSettings.length > 0) {
                                    for (let m = 0; m < setting.fieldSettings.length; m++) {
                                        const property = setting.fieldSettings[m].property;
                                        if (property) {
                                            allSelectorPropertyPairs.push({
                                                selector: nestedSelector,
                                                property: property,
                                                target: {
                                                    object: setting.fieldSettings[m],
                                                    key: 'value'
                                                },
                                                layout: setting.layout || item.layout // collect layout info if present
                                            });
                                        }
                                    }
                                }
                            }
                        }
                    }

                    // Filter unique selector-property combinations
                    var uniquePairs = []; // Using var as in original code
                    var uniqueKeys = new Set();

                    for (const pair of allSelectorPropertyPairs) {
                        const key = `${pair.selector}|${pair.property}`;
                        if (!uniqueKeys.has(key)) {
                            uniqueKeys.add(key);
                            uniquePairs.push(pair);
                        }
                    }

                    if (window.css_vars_design_apply_mode === 'layout' && window.css_vars_design_active_layout) {
                        const activeLayout = window.css_vars_design_active_layout;
                        const layoutId = typeof activeLayout === 'string'
                            ? activeLayout
                            : (activeLayout?.id || activeLayout?.getAttribute?.('id'));

                        if (layoutId) {
                            const layoutSelectorTarget = '#' + layoutId;
                            const processedPairs = [];

                            for (const pair of uniquePairs) {
                                let currentSelector = pair.selector;
                                let finalSelector = pair.selector;

                                if (currentSelector === ':root') {
                                    finalSelector = layoutSelectorTarget;
                                }

                                let includeThisPair = false;
                                if (finalSelector === layoutSelectorTarget) {
                                    includeThisPair = true;
                                } else if (pair.layout && pair.layout == layoutId) {
                                    includeThisPair = true;
                                }

                                if (includeThisPair) {
                                    // Create a shallow copy to modify selector, keeping target reference intact
                                    const pairToAdd = { ...pair };
                                    pairToAdd.selector = finalSelector; // Ensure the selector is the final one
                                    processedPairs.push(pairToAdd);
                                }
                            }
                            uniquePairs = processedPairs;

                            // Deduplicate again, as different original pairs might now target the same finalSelector and property
                            const finalUniquePairsAfterTransform = [];
                            const finalUniqueKeysAfterTransform = new Set();
                            for (const p of uniquePairs) {
                                const key = `${p.selector}|${p.property}`;
                                if (!finalUniqueKeysAfterTransform.has(key)) {
                                    finalUniqueKeysAfterTransform.add(key);
                                    finalUniquePairsAfterTransform.push(p);
                                }
                            }
                            uniquePairs = finalUniquePairsAfterTransform;

                        } else {
                            uniquePairs = []; // No layout ID, so no pairs if in layout mode
                        }
                    }

                    // Now make all the cssEditor calls
                    // This loop uses `pair.target.object` which should be the original reference.
                    for (const pair of uniquePairs) {
                        const propertyValue = mw.top().app?.cssEditor?.getPropertyForSelector(pair.selector, pair.property);
                        if (pair.target && typeof pair.target.object === 'object' && pair.target.object !== null) {
                            pair.target.object[pair.target.key] = propertyValue;
                        }
                    }

                    let valuesForEdit = {};
                    for (const pair of uniquePairs) {
                        // pair.selector is now correctly #layoutId if it was :root in layout mode and processed above
                        const propertyValue = mw.top().app?.cssEditor?.getPropertyForSelector(pair.selector, pair.property);

                        let selectorKey = pair.selector; // Use the processed selector from the pair

                        if (!valuesForEdit[selectorKey]) {
                            valuesForEdit[selectorKey] = {};
                        }

                        valuesForEdit[selectorKey][pair.property] = propertyValue;
                    }

                    // Ensure valuesForEdit has the layout key if empty in layout mode
                    if (Object.keys(valuesForEdit).length === 0 &&
                        window.css_vars_design_apply_mode === 'layout' &&
                        window.css_vars_design_active_layout) {
                        const activeLayout = window.css_vars_design_active_layout;
                        const layoutId = typeof activeLayout === 'string'
                            ? activeLayout
                            : (activeLayout?.id || activeLayout?.getAttribute?.('id'));
                        if (layoutId) {
                            valuesForEdit['#' + layoutId] = {};
                        }
                    }

                    return valuesForEdit;
                }

                async function changeDesign(about) {
                    var designSelectors = JSON.parse(JSON.stringify(window.mw_template_settings_styles_and_selectors));

                    if (!about) {
                        //open a dialog

                        about = prompt('Please enter the design task description:', 'Make it blue and white');
                    }
                    // var about = 'make it blue and white';

                    // First, recursively remove clearAll items and unwanted properties


                    designSelectors = preapareAndCleanTemplateStylesAndSelectorsData(designSelectors);

                    var valuesForEdit = preapareTemplateValuesForEdit(designSelectors);


                    // console.log('Updated design settings:', designSelectors);
                    // console.log('uniquePairs:', uniquePairs);
                    console.log('valuesForEdit:', valuesForEdit);
                    let editSchema = JSON.stringify(valuesForEdit);

                    const message = `Using the existing object IDS,
        By using this schema: \n ${editSchema} \n
        You must write CSS values to the goven object,
        You are CSS values editror, you must edit the values of the css to complete the user design taks,

        The css design task is : ${about}

        You must write the text for the website and will the existing object IDs with the text,



You must respond ONLY with the JSON schema with the following structure. Do not add any additional comments""" + \\
"""[
  JSON
{
   { Populated Schema Definition with the items filled with text ... populate the schema with the existing object IDs and the text  }

"""`


                    let messageOptions = {};
                    //  messageOptions.schema = this.schema();
                    messageOptions.schema = editSchema;
                    mw.top().spinner(({element: mw.top().doc.body, size: 60, decorate: true})).show();


                    let messages = [{role: 'user', content: message}];




                    if (window.css_vars_design_apply_mode === 'layout' && window.css_vars_design_active_layout) {



                        const activeLayout = window.css_vars_design_active_layout;
                        const layoutId = typeof activeLayout === 'string'
                            ? activeLayout
                            : (activeLayout?.id || activeLayout?.getAttribute?.('id'));

                        if (layoutId) {
                            selector  =  '#' + layoutId  ;

                            console.log( 'layoutId:', layoutId);
                        }
                    }
                    let res = await mw.top().win.MwAi().sendToChat(messages, messageOptions)

                    if (res.success && res.data) {


                        for (let selector in res.data) {
                            if (res.data.hasOwnProperty(selector)) {





                                // Loop through all properties for the current selector
                                for (let property in res.data[selector]) {





                                    if (res.data[selector].hasOwnProperty(property)) {
                                        const value = res.data[selector][property];

                                        // Apply the property to the selector using the CSS editor
                                        if (mw.top().app.cssEditor) {
                                            // Determine unit if needed (you might need to adjust this based on property type)
                                            const unit = property.includes('color') ? '' : '';



                                            // Apply the CSS property
                                            mw.top().app.cssEditor.setPropertyForSelector(
                                                selector,
                                                property,
                                                value + unit,
                                                true,
                                                true
                                            );
                                        }
                                    }
                                }
                            }
                        }


                    }
                    mw.top().spinner(({element: mw.top().doc.body, size: 60, decorate: true})).remove();

                    return designSelectors;
                }


            </script>

        @endif



        @if(isset($styleSettings))






        <div class="form-control-live-edit-label-wrapper">
            <select class="form-control form-select "  name="css_vars_design_apply_mode" id="css_vars_design_apply_mode" >
                <option value="template">Template</option>
                <option value="layout">Layout</option>
            </select>
        </div>


            <div
                x-data="{styleEditorData:{}, showStyleSettings: '/'}"

                x-init="()=>{
            $watch('styleEditorData', (value) => {
                if (value.selectors) {
                    mw.top().app.dispatch('mw.rte.css.editor2.open', value);
                }
                });
            }"
            >

                <div class="ai-settings-wrapper">
                    <label for="" class="live-edit-label mb-2">
                        MAKE YOUR WEBSITE FASTER WITH AI
                    </label>
                    <div class="ai-change-template-design-button d-none"></div>


                    <button type="button" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="changeDesign"
                            class="btn btn-link p-0" x-on:click="()=> {
                            changeDesign();
                        }">

                        Go with AI
                    </button>

                </div>

                    <?php if (!empty($styleSettings)): ?>
                <div class="mt-5">
            <span
                x-show="showStyleSettings == '/'"
                class="fs-2 font-weight-bold settings-main-group d-flex align-items-center justify-content-between">
               Styles

                <div class="d-flex align-items-end" style="display: none">



                    <button type="button" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="Reset stylesheet settings"
                            class="reset-template-settings-and-stylesheet-button" x-on:click="()=> {
                            resetAllDeignSelectorsValuesSettings();
                        }">
                        <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960"
                             width="24"><path
                                d="M440-122q-121-15-200.5-105.5T160-440q0-66 26-126.5T260-672l57 57q-38 34-57.5 79T240-440q0 88 56 155.5T440-202v80Zm80 0v-80q87-16 143.5-83T720-440q0-100-70-170t-170-70h-3l44 44-56 56-140-140 140-140 56 56-44 44h3q134 0 227 93t93 227q0 121-79.5 211.5T520-122Z"/></svg>

                    </button>
{{--
                    <button type="button" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="Reset stylesheet settings"
                            class="reset-template-settings-and-stylesheet-button" x-on:click="()=> {
                            resetStylesheetSettings();
                        }">
                        <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960"
                             width="24"><path
                                d="M440-122q-121-15-200.5-105.5T160-440q0-66 26-126.5T260-672l57 57q-38 34-57.5 79T240-440q0 88 56 155.5T440-202v80Zm80 0v-80q87-16 143.5-83T720-440q0-100-70-170t-170-70h-3l44 44-56 56-140-140 140-140 56 56-44 44h3q134 0 227 93t93 227q0 121-79.5 211.5T520-122Z"/></svg>

                    </button>--}}
                </div>
            </span>

                    @foreach($styleSettings as $styleSetting)

                        <div
                            @if(isset($styleSetting['main']))
                                x-show="showStyleSettings == '/'"
                            @else
                                x-show="showStyleSettings == '{{$styleSetting['url']}}'"
                            @endif


                            class="my-3">

                            <div
                                x-show="showStyleSettings == '/'"
                            >
                                @if (isset($styleSetting['title']))
                                    <a x-on:click="showStyleSettings = '{{ $styleSetting['url'] }}'"
                                       class="mw-admin-action-links mw-adm-liveedit-tabs settings-main-group cursor-pointer mb-4">
                                        {{ $styleSetting['title'] }}
                                    </a>
                                @endif

                            </div>

                            <div x-show="showStyleSettings == '{{$styleSetting['url']}}'">

                                <div>
                                    <button x-on:click="showStyleSettings = '{{$styleSetting['backUrl']}}'"
                                            class="d-flex gap-2 btn btn-link mw-live-edit-toolbar-link mw-live-edit-toolbar-link&#45;&#45;arrowed text-start text-start"
                                            type="button">
                                        <svg class="mw-live-edit-toolbar-arrow-icon" xmlns="http://www.w3.org/2000/svg"
                                             width="32" height="32" viewBox="0 0 32 32">
                                            <g fill="none" stroke-width="1.5" stroke-linejoin="round"
                                               stroke-miterlimit="10">
                                                <circle class="arrow-icon&#45;&#45;circle" cx="16" cy="16"
                                                        r="15.12"></circle>
                                                <path class="arrow-icon&#45;&#45;arrow"
                                                      d="M16.14 9.93L22.21 16l-6.07 6.07M8.23 16h13.98"></path>
                                            </g>
                                        </svg>
                                        <div class="ms-1 font-weight-bold">
                                            Back
                                        </div>
                                    </button>
                                </div>

                                <div>
                                    @if(isset($styleSetting['title']))
                                        <h4>{{$styleSetting['title']}}</h4>
                                    @endif
                                    @if(isset($styleSetting['description']))
                                        <p>{{$styleSetting['description']}}</p>
                                    @endif
                                </div>

                                @if(isset($styleSetting['fieldType']))
                                    @include('template::livewire.live-edit.template-setting-render', [
                                            'setting' => $styleSetting,
                                            'parent' => $styleSetting['parent'] ?? false
                                        ])
                                @endif

                                @if(isset($styleSetting['settings']))
                                    @foreach($styleSetting['settings'] as $setting)
                                        @include('template::livewire.live-edit.template-setting-render', [
                                            'setting' => $setting,
                                            'parent' => $styleSetting['parent'] ?? false
                                        ])
                                    @endforeach
                                @endif

                            </div>

                        </div>
                    @endforeach

                </div>
                <?php endif; ?>

                    <?php if (!empty($settingsGroups)): ?>
                <div

                    x-show="showStyleSettings == 'styleEditor'"
                    class="mt-3"
                >

                    <div>
                        <button x-on:click="showStyleSettings = styleEditorData.backUrl"
                                class="d-flex gap-2 btn btn-link mw-live-edit-toolbar-link mw-live-edit-toolbar-link&#45;&#45;arrowed text-start text-start"
                                type="button">
                            <svg class="mw-live-edit-toolbar-arrow-icon" xmlns="http://www.w3.org/2000/svg" width="32"
                                 height="32" viewBox="0 0 32 32">
                                <g fill="none" stroke-width="1.5" stroke-linejoin="round" stroke-miterlimit="10">
                                    <circle class="arrow-icon&#45;&#45;circle" cx="16" cy="16" r="15.12"></circle>
                                    <path class="arrow-icon&#45;&#45;arrow"
                                          d="M16.14 9.93L22.21 16l-6.07 6.07M8.23 16h13.98"></path>
                                </g>
                            </svg>
                            <div class="ms-1 font-weight-bold">
                                Back
                            </div>
                        </button>
                    </div>

                    <b x-show="styleEditorData.title" x-html="styleEditorData.title"></b>
                    <p x-show="styleEditorData.description" x-html="styleEditorData.description"></p>

                    <div class="my-3">
                        <div id="iframe-holder"></div>
                    </div>
                </div>
                @foreach($settingsGroups as $settingGroupName=>$settingGroup)

                    <div wire:key="setting-group-key-{{md5($settingGroupName)}}" class="mt-3">

                        <div class="mt-5 d-flex align-items-center justify-content-between"
                             x-show="showStyleSettings == '/'">
                            <a class="fs-2 font-weight-bold tblr-body-color text-decoration-none settings-main-group">
                                {{$settingGroupName}}
                            </a>

                                <?php /* this is the import export style  button
                            @if($settingGroup['type'] == 'stylesheet')

                                <button type="button" data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="Restore Export template settings"
                                        class="import-export-template-settings-and-stylesheet-button" x-on:click="()=> {
                                            importExportTemplateStylesheetSettings();
                                     }">
                                    <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M280-160v-80h400v80H280Zm160-160v-327L336-544l-56-56 200-200 200 200-56 56-104-103v327h-80Z"/></svg>

                                </button>

                            @endif

                            */ ?>

                                <?php /* this is the reset button*/ ?>


                            <button type="button" data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="Reset template settings"
                                    class="reset-template-settings-and-stylesheet-button" x-on:click="()=> {

                                    @if($settingGroup['type'] == 'stylesheet')
                                        resetStylesheetSettings();
                                    @endif

                                     @if($settingGroup['type'] == 'template')
                                            resetTemplateSettings();
                                      @endif

                                    }">
                                <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24"
                                     viewBox="0 -960 960 960" width="24">
                                    <path
                                        d="M440-122q-121-15-200.5-105.5T160-440q0-66 26-126.5T260-672l57 57q-38 34-57.5 79T240-440q0 88 56 155.5T440-202v80Zm80 0v-80q87-16 143.5-83T720-440q0-100-70-170t-170-70h-3l44 44-56 56-140-140 140-140 56 56-44 44h3q134 0 227 93t93 227q0 121-79.5 211.5T520-122Z"/>
                                </svg>

                            </button>


                        </div>

                        <div>
                            @foreach($settingGroup['values'] as $settingName=>$settingFields)
                                <div wire:key="setting-values-key-{{md5($settingName)}}">

                                    <div x-show="showStyleSettings == 'setting-values-key-{{md5($settingName)}}'">
                                        <button x-on:click="showStyleSettings = '/'"
                                                class="d-flex gap-2 btn btn-link mw-live-edit-toolbar-link mw-live-edit-toolbar-link--arrowed text-start text-start"
                                                type="button">
                                            <svg class="mw-live-edit-toolbar-arrow-icon"
                                                 xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                                 viewBox="0 0 32 32">
                                                <g fill="none" stroke-width="1.5" stroke-linejoin="round"
                                                   stroke-miterlimit="10">
                                                    <circle class="arrow-icon--circle" cx="16" cy="16"
                                                            r="15.12"></circle>
                                                    <path class="arrow-icon--arrow"
                                                          d="M16.14 9.93L22.21 16l-6.07 6.07M8.23 16h13.98"></path>
                                                </g>
                                            </svg>
                                            <div class="ms-1 font-weight-bold">
                                                Back to {{mb_strtolower($settingGroupName)}}
                                            </div>
                                        </button>
                                    </div>

                                    <div x-show="showStyleSettings == '/'" class="my-4">
                                        <a x-on:click="showStyleSettings = 'setting-values-key-{{md5($settingName)}}'"
                                           class="mw-admin-action-links">
                                            <b>{{$settingName}}</b>
                                        </a>
                                    </div>

                                    <div x-show="showStyleSettings == 'setting-values-key-{{md5($settingName)}}'">
                                        @foreach($settingFields as $settingFieldKey=>$settingField)

                                            <div wire:key="setting-field-key-{{md5($settingFieldKey)}}">
                                                @php
                                                    $settingFieldOptionGroup = $settingField['optionGroup'];
                                                @endphp

                                                @if($settingField['type'] == 'font_selector')

                                                    <div class="mt-3">
                                                        <label class="live-edit-label">
                                                            {{$settingField['label']}}
                                                        </label>
                                                        <livewire:microweber-option::font-picker
                                                            label="{{$settingField['label']}}"
                                                            :optionKey="$settingFieldKey"
                                                            :optionGroup="$settingFieldOptionGroup"
                                                        />
                                                    </div>

                                                @elseif($settingField['type'] == 'color')
                                                    <div class="mt-3">
                                                        <livewire:microweber-option::color-picker
                                                            label="{{$settingField['label']}}"
                                                            :optionKey="$settingFieldKey"
                                                            :optionGroup="$settingFieldOptionGroup"/>
                                                    </div>
                                                @elseif($settingField['type'] == 'dropdown')
                                                    <div class="mt-4 mb-3">
                                                        <label class="live-edit-label">
                                                            {{$settingField['label']}} <br/>
                                                            @if(isset($settingField['help']))
                                                                {{$settingField['help']}}
                                                            @endif
                                                        </label>
                                                        @php
                                                            $dropdownOptions = $settingField['options'];
                                                        @endphp


                                                        <livewire:microweber-option::dropdown
                                                            :dropdownOptions="$dropdownOptions"
                                                            :optionKey="$settingFieldKey"
                                                            :optionGroup="$settingFieldOptionGroup"/>
                                                    </div>
                                                @else
                                                    <div>
                                                        <label class="live-edit-label">
                                                            {{$settingField['label']}}
                                                        </label>
                                                        <livewire:microweber-option::text :optionKey="$settingFieldKey"
                                                                                          :optionGroup="$settingFieldOptionGroup"/>
                                                    </div>
                                                @endif

                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>
                @endforeach
            </div>
            <?php endif; ?>

        @endif

    </div>

</div>
