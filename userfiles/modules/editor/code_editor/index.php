<?php must_have_access(); ?>

<div class="module-live-edit-settings" id="code-editor-settings">
    <script>
        $(document).ready(function () {
              mw.tabs({
                nav: "#code_editor_main_tabnav a",
                tabs: ".code_editor_main_tab",
                onclick:function(){
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
    <style>
        #css-type-tabs > .mw-ui-box-content{
            max-height: calc(100vh - 270px);
            overflow: auto;
        }
        #css-type-tabs > .mw-ui-box-content,
        .module-live-edit-settings > .mw-ui-box-content{
            padding: 0;
        }

        #code_editor_main_tabnav{
            padding: 0 20px;
        }
        #css-type-tabs-nav{
            padding: 20px 20px 0;
        }
    </style>

    <script>
        function popout_css_editor_module_in_new_window(){

            var url = mw.settings.site_url + 'api/module?id=mw_global_css_editor&live_edit=true&module_settings=true&type=editor/css_editor&autosize=true';
            window.open(url, '_blank');
            if(mw.dialog && mw.dialog.get()){
            mw.dialog.get().remove();
            }
        }
    </script>

    <div class="mw-ui-btn-nav mw-ui-btn-nav-tabs" id="code_editor_main_tabnav">
      <a class="mw-ui-btn-tab active"  href="javascript:;">HTML</a>
      <a class="mw-ui-btn-tab" href="javascript:;">CSS</a>
      <a class="mw-ui-btn-tab" href="javascript:;" onclick="popout_css_editor_module_in_new_window()" title="Pop put">

          <i class="mdi mdi-open-in-new mdi-20px lh-1_3" title="Pop put"   style="cursor: pointer"></i>

      </a>

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

