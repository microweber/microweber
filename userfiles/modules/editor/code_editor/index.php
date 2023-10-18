<?php must_have_access(); ?>

<div class="module-live-edit-settings" id="code-editor-settings">
    <script>
        $(document).ready(function () {

            const tabEl = document.querySelector('#codeEditorTabStyleEditorCssEditorNav');
            tabEl.addEventListener('shown.bs.tab', event => {
                setTimeout(function() {
                    if (typeof (css_code_area_editor) != 'undefined') {
                        css_code_area_editor.refresh()
                    }
                    if (typeof (html_code_area_editor) != 'undefined') {
                        html_code_area_editor.refresh()
                    }
                }, 300);
            })


        })
    </script>

    <style>

#codeEditorTabStyleEditorCssEditorNav{
    gap: 8px;
    padding: 0 20px;
}
        #codeEditorTabStyleEditorNav {
            gap: 20px;
            padding: 0 20px;
        }
    </style>


    <div>
    <div class="navbar navbar-expand-md navbar-transparent" >
        <ul class="navbar-nav" id="codeEditorTabStyleEditorNav" role="tablist">
            <li class="nav-item  " role="presentation">
                <a class="mw-admin-action-links mw-adm-liveedit-tabs  active " data-bs-toggle="tab"
                        data-bs-target="#style-edit-global-template-settings-holder" type="button" role="tab">
                    <span class="nav-link-title">HTML Editor</span>
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="mw-admin-action-links mw-adm-liveedit-tabs  " data-bs-toggle="tab"
                        data-bs-target="#style-edit-custom-template-settings-holder" type="button" role="tab">
                        <span class="nav-link-title">CSS Editor</span>
                </a>
            </li>
        </ul>
    </div>

        <div class="tab-content">
            <div class="tab-pane active tab-pane-slide-right" id="style-edit-global-template-settings-holder"
                 role="tabpanel">
                <module type="editor/html_editor2" id="mw_code_html_editor"/>
            </div>
            <div class="tab-pane tab-pane-slide-right" id="style-edit-custom-template-settings-holder"
                 role="tabpanel">
                <module type="editor/css_editor" id="mw_code_css_editor"/>

            </div>
        </div>
    </div>

</div>

