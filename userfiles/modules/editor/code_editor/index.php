<?php must_have_access(); ?>

<div class="module-live-edit-settings" id="code-editor-settings">
    <script>
        $(document).ready(function () {
            mw.tabs({
                nav: "#code_editor_main_tabnav a",
                tabs: ".code_editor_main_tab",
                onclick: function () {
                    if (typeof (css_code_area_editor) != 'undefined') {
                        css_code_area_editor.refresh()
                    }
                    if (typeof (html_code_area_editor) != 'undefined') {
                        html_code_area_editor.refresh()
                    }
                }
            });
        })
    </script>


    <div>
        <ul class="nav nav-pills nav-justified" id="codeEditorTabStyleEditorNav" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" data-bs-toggle="tab"
                        data-bs-target="#style-edit-global-template-settings-holder" type="button" role="tab">
                    HTML Editor
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab"
                        data-bs-target="#style-edit-custom-template-settings-holder" type="button" role="tab">
                    CSS Editor
                </button>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane active tab-pane-slide-right" id="style-edit-global-template-settings-holder"
                 role="tabpanel">
                <module type="editor/html_editor" id="mw_code_html_editor"/>
            </div>
            <div class="tab-pane tab-pane-slide-right" id="style-edit-custom-template-settings-holder"
                 role="tabpanel">
                <module type="editor/css_editor" id="mw_code_css_editor"/>

            </div>
        </div>
    </div>


</div>

