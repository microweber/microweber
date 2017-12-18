<?php only_admin_access(); ?>

<div class="module-live-edit-settings">
    <script>
        $(document).ready(function () {
            window.code_editor_main_tabs = window.code_editor_main_tabs || mw.tabs({
                nav: "#code_editor_main_tabnav a",
                tabs: ".code_editor_main_tab",
                onclick:function(){
                    if(typeof cm != 'undefined'){
                        cm.refresh()
                    }
                    if(typeof(css_code_area_editor) != 'undefined'){
                        css_code_area_editor.refresh()
                    }
                    if(typeof(html_code_area_editor) != 'undefined'){
                      html_code_area_editor.refresh()
                    }
                }
            });
        })
    </script>

    <div class="mw-ui-btn-nav mw-ui-btn-nav-tabs" id="code_editor_main_tabnav">
      <a class="mw-ui-btn active"  href="javascript:;">HTML</a>
      <a class="mw-ui-btn" href="javascript:;">CSS</a>
    </div>
    <div class="mw-ui-box mw-ui-box-content">
        <div class="code_editor_main_tab" style="display: block;">
            <module type="editor/html_editor" id="mw_code_html_editor"/>
        </div>
        <div class="code_editor_main_tab" style="display: none;">
            <module type="editor/css_editor" id="mw_code_css_editor"/>
        </div>
        <div class="code_editor_main_tab"  style="display: none;"></div>
    </div>
</div>

