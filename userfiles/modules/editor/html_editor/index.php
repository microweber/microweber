<?php only_admin_access(); ?>
<script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.16.0/codemirror.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.16.0/mode/css/css.min.js"></script>

<script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.16.0/mode/htmlmixed/htmlmixed.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.16.0/mode/php/php.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.16.0/mode/xml/xml.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.16.0/addon/selection/selection-pointer.js"></script>


<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.16.0/codemirror.min.css">
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.16.0/theme/material.css">



<script>
    mw.html_editor = {};
    mw.html_editor.map = {};
    mw.html_editor.init = function () {
        var fields = mw.html_editor.get_edit_fields();
        mw.html_editor.build_dropdown(fields);
        mw.html_editor.populate_editor();

    };


    mw.html_editor.get_edit_fields = function () {
        var fields_arr = new Array();
        var get_edit_fields = $(parent.document).contents().find('.edit').each(function () {
            var is_in_module = mw.tools.firstParentWithClass(this, 'module');
            if(!is_in_module){
            fields_arr.push(this);
            }
        });
        return fields_arr;
    };
    mw.html_editor.build_dropdown = function (fields_array) {
        var html_dd = new Object();
        $(fields_array).each(function () {
            var dd_grp = $(this).attr('rel');
            var dd_field = $(this).attr('field');
            if(dd_grp && dd_grp){
              if (typeof(html_dd[dd_grp]) == 'undefined') {
                  html_dd[dd_grp] = new Array();
              }
              var temp = {};
              temp.field = dd_field;
              temp.rel = dd_grp;
              mw.html_editor.map[dd_grp + '/' + dd_field] = this;
              html_dd[dd_grp].push(temp);
            }

        });


        var $select = $("<select>");
        $select.attr('id', 'select_edit_field');
        $select.attr('onchange', 'mw.html_editor.populate_editor()');

        $select.appendTo("#select_edit_field_wrap");
        $.each(html_dd, function (groupName, options) {
            var $optgroup = $("<optgroup>", {label: groupName, rel: groupName});
            $optgroup.appendTo($select);
            $.each(options, function (j, option) {
                var $option = $("<option>", {
                    text: option.field,
                    value: option.rel,
                    rel: option.rel,
                    field: option.field
                });
                $option.appendTo($optgroup);
            });
        });
    };


    mw.html_editor.populate_editor = function () {
        var value = $('select#select_edit_field option:selected');

        if (value.length == 0) {
            var value = $('select#select_edit_field option:first');
        }
        if (value.length == 0) {
            return;
        }
        $('#fragment-holder').remove();
        var ed_val = '';
        var dd_grp = value.attr('rel');
        var dd_field = value.attr('field');
        if (typeof(mw.html_editor.map[dd_grp + '/' + dd_field]) != 'undefined') {

            var ed_val = $(mw.html_editor.map[dd_grp + '/' + dd_field]).html();


            var frag = document.createDocumentFragment();
            var html = ed_val;
            var holder = document.createElement("div")
            holder.id = 'fragment-holder'
            holder.innerHTML = html
            frag.appendChild(holder)
            var s = $('.module', $(frag)).html('[module]');
            var ed_val = $(holder).html();

        } else {
            var ed_val = 'Select element to edit';
        }


        $('#custom_html_code_mirror').val(ed_val);
        $('#custom_html_code_mirror').attr('current', dd_grp + '/' + dd_field);


    };

    mw.html_editor.apply = function () {
        var cur = $('#custom_html_code_mirror').attr('current');
        var html = $('#custom_html_code_mirror').val();
        if (typeof(mw.html_editor.map[cur]) != 'undefined') {

            var el = mw.html_editor.map[cur];
            $(el).html(html);

            if ($(el).hasClass('edit')) {
                var master_edit_field_holder = el;

            } else {
                var master_edit_field_holder = mw.tools.firstParentWithClass(el, 'edit');

            }


            var modules_ids = {};
            var modules_list = $('.module', $(el));
            $(modules_list).each(function () {
                var id = $(this).attr('id');
                if (id) {
                    id = '#' + id;
                } else {
                    var id = $(this).attr('data-type');
                }
                if (!id) {
                    var id = $(this).attr('type');
                }
                modules_ids[id] = true;
            });


            $.each(modules_ids, function (index, value) {
                mw.reload_module_parent(index);
            });


            if (master_edit_field_holder) {
                $(master_edit_field_holder).addClass("changed");
                setTimeout(function () {
                    window.parent.mw.drag.fix_placeholders(true);
                    window.parent.mw.resizable_columns();
                    window.parent.mw.on.DOMChangePause = false;
                }, 200);
            }
        }

    }


    $(document).ready(function () {

        mw.html_editor.init();


    })


</script>

<script type="text/javascript">
    $time_out_handle = 0;
    $(document).ready(function () {
        var editor = CodeMirror.fromTextArea(document.getElementById("custom_html_code_mirror"), {
            lineNumbers: true,
            indentWithTabs: true,
			matchBrackets: true,
            extraKeys: {"Ctrl-Space": "autocomplete"},
            mode: "htmlmixed"
        });

 editor.setOption("theme", 'material');
        editor.on("change", function (cm, change) {
            var custom_html_code_mirror = document.getElementById("custom_html_code_mirror")
            custom_html_code_mirror.value = cm.getValue();

            window.clearTimeout($time_out_handle);
            $time_out_handle = window.setTimeout(function () {
                $(custom_html_code_mirror).change();
            }, 2000);

        });


    })


</script>

<div id="select_edit_field_wrap">

</div>
<button onclick="mw.html_editor.apply();">Apply</button>
<textarea class="mw-ui-field w100" name="custom_html" id="custom_html_code_mirror" rows="30"
          option-group="template" placeholder="Type your HTML code here"></textarea>
