<script>

    $(document).ready(function () {
        mw.tools.dropdown(document.getElementById('mw-admin-text-editor'));
        Editor = mw.$('#mw-admin-text-editor');
        Editor.mousedown(function (e) {
            e.preventDefault()
        });

        Editor.hover(function () {
            $(this).addClass('editor_hover');
        }, function () {
            $(this).removeClass('editor_hover');
        });

        mw.lib.require('font_awesome');
    });


</script>


<style>
    .save-scrolled {
        float: right;
        display: none;
        color: #fff;
        background: #48ad79;
        padding: 0 6px;
        border-radius: 3px;
        border: 0;
    }

    .save-scrolled:hover {
        float: right;
        display: none;
        color: #fff;
        background: #45a674;
        padding: 0 6px;
        border-radius: 3px;
        border: 0;
    }

    .save-scrolled .fa {
        color: #fff;
        margin-top: 2px;
        margin-inline-end: 6px;
    }

    .save-scrolled .label,
    .save-scrolled:hover .label {
        float: right;
        color: #fff;
        display: block;
        margin-top: 2px;
    }

    .scrolled .save-scrolled {
        display: block;
    }
</style>


<div id="mw-admin-text-editor" class="mw_editor">
    <div class="editor_wrapper">

        <span class="mw_editor_btn mw_editor_image" data-command="custom-media" title="<?php _e("Insert Media"); ?>"><span class="ed-ico"></span></span>

        <span class="mw_dlm"></span>

        <span class="mw_editor_btn mw_editor_bold" data-command="bold" title="<?php _e("Bold"); ?>"><span class="ed-ico"></span></span>
        <span class="mw_editor_btn mw_editor_italic" data-command="italic" title="<?php _e("Italic"); ?>"><span class="ed-ico"></span></span>
        <span class="mw_editor_btn mw_editor_underline" data-command="underline" title="<?php _e("Underline"); ?>"><span class="ed-ico"></span></span>

        <span class="mw_dlm"></span>

        <span class="mw_editor_btn mw_editor_alignment mw_editor_justifyleft mw-align-left" data-command="justifyLeft" title="<?php _e("Align Left"); ?>"><span class="ed-ico"></span></span>
        <span class="mw_editor_btn mw_editor_alignment mw_editor_justifycenter mw-align-center" data-command="justifyCenter" title="<?php _e("Align Center"); ?>"><span class="ed-ico"></span></span>
        <span class="mw_editor_btn mw_editor_alignment mw_editor_justifyright mw-align-right" data-command="justifyRight" title="<?php _e("Align Right"); ?>"><span class="ed-ico"></span></span>
        <span class="mw_editor_btn mw_editor_alignment mw_editor_justifyfull mw-align-justify" data-command="justifyFull" title="<?php _e("Align Both Sides"); ?>"><span class="ed-ico"></span></span>


        <span class="mw_dlm"></span>


        <div class="mw-dropdown mw-dropdown-type-wysiwyg mw_dropdown_action_format" id="format_main" title="<?php _e("Format"); ?>" data-value="">
                <span class="mw-dropdown-value">

                    <span class="mw-dropdown-val"><?php _e("Format"); ?></span>
                </span>
            <div class="mw-dropdown-content">
                <ul>
                    <li value="h1"><a href="javascript:;"><h1><?php _e("Heading"); ?> 1</h1></a></li>
                    <li value="h2"><a href="javascript:;"><h2><?php _e("Heading"); ?> 2</h2></a></li>
                    <li value="h3"><a href="javascript:;"><h3><?php _e("Heading"); ?> 3</h3></a></li>
                    <li value="h4"><a href="javascript:;"><h4><?php _e("Heading"); ?> 4</h4></a></li>
                    <li value="h5"><a href="javascript:;"><h5><?php _e("Heading"); ?> 5</h5></a></li>
                    <li value="h6"><a href="javascript:;"><h6><?php _e("Heading"); ?> 6</h6></a></li>
                    <li value="p"><a href="javascript:;"><p><?php _e("Paragraph"); ?></p></a></li>

                </ul>
            </div>
        </div>


        <div class="mw-dropdown mw-dropdown-type-wysiwyg mw_dropdown_action_font_family" id="font_family_selector_main" title="<?php _e("Font"); ?>" data-value="Arial">
              <span class="mw-dropdown-value">

                  <span class="mw-dropdown-val">Arial</span>
              </span>
            <div class="mw-dropdown-content">
                <ul>
                    <li value="Arial"><a href="javascript:;" style="font-family:Arial">Arial</a></li>
                    <li value="Tahoma"><a href="javascript:;" style="font-family:Tahoma">Tahoma</a></li>
                    <li value="Verdana"><a href="javascript:;" style="font-family:Verdana">Verdana</a></li>
                    <li value="Georgia"><a href="javascript:;" style="font-family:Georgia">Georgia</a></li>
                    <li value="Times New Roman"><a href="javascript:;" style="font-family: 'Times New Roman'">Times New Roman</a></li>
                </ul>
            </div>
        </div>


        <div class="mw-dropdown mw-dropdown-type-wysiwyg mw_dropdown_action_font_size" id="font_size_selector_main" title="<?php _e("Font Size"); ?>">
                <span class="mw-dropdown-value">

                  <span class="mw-dropdown-val"><?php _e('Size'); ?></span>
                </span>
            <div class="mw-dropdown-content">
                <ul>
                    <li value="10"><a href="javascript:;">10</a></li>
                    <li value="11"><a href="javascript:;">11</a></li>
                    <li value="12"><a href="javascript:;">12</a></li>
                    <li value="14"><a href="javascript:;">14</a></li>
                    <li value="16"><a href="javascript:;">16</a></li>
                    <li value="18"><a href="javascript:;">18</a></li>
                    <li value="20"><a href="javascript:;">20</a></li>
                    <li value="22"><a href="javascript:;">22</a></li>
                    <li value="24"><a href="javascript:;">24</a></li>
                    <li value="36"><a href="javascript:;">36</a></li>
                    <li value="72"><a href="javascript:;">72</a></li>
                </ul>
            </div>
        </div>


        <span class="mw_dlm"></span>


        <span class="mw_editor_btn mw_editor_ol" data-command="insertorderedlist" title="<?php _e("Ordered List"); ?>"><span class="ed-ico"></span></span>
        <span class="mw_editor_btn mw_editor_ul" data-command="insertunorderedlist" title="<?php _e("Unordered List"); ?>"><span class="ed-ico"></span></span>


        <span class="mw_dlm"></span>


        <span class="mw_editor_btn mw_editor_link" data-command="custom-link" title="<?php _e("Add/Edit Link"); ?>"><span class="ed-ico"></span></span>
        <span class="mw_editor_btn mw_editor_unlink" data-command="custom-unlink" title="<?php _e("Remove Link"); ?>"><span class="ed-ico"></span></span>


        <span title="Paste from word" onclick="mw.wysiwyg.pasteFromWordUI();" class="mw_editor_btn mw_editor_paste_from_word"><span class="ed-ico"></span></span>
        <span class="mw_editor_btn mw_editor_remove_formatting" data-command="removeformat" title="<?php _e("Remove Formatting"); ?>"><span class="ed-ico"></span></span>
        <span class="mw_editor_btn mw_editor_html_editor" id="mw-toolbar-html-editor-btn" title="<?php _e("HTML Editor"); ?>" ><span class="ed-ico mw-icon-code"></span></span>
    </div>

</div>
