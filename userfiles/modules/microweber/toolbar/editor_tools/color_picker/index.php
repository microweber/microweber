<style>
    #my-colors {
        width: 235px;
        max-height: 90px;
        overflow-x: hidden;
        overflow-y: auto;
        margin-bottom: 5px;
    }

    #my-colors span {
        display: block;
        float: left;
        width: 20px;
        height: 20px;
        margin: 5px;
        cursor: pointer;
        box-shadow: 0px 0px 2px #ccc;
    }

    #my-colors span:hover {
        box-shadow: 0px 0px 2px #999;
    }

    #main_holder {
        position: relative;
        padding: 10px;
        margin: 0 10px 10px;
        background: white;
        overflow: hidden;
    }

    #hex_color_value {
        font-weight: bold;
        padding: 1px 7px;
        border-radius: 3px;
        box-shadow: 0 2px 2px #0003;
    }
    #mwpicker {
        clear: both;
        position: relative;
        width: 240px;
        height: 130px;
    }

    #mwpicker > div {
        background: none !important;
        border: none !important;
    }

    .transparent {
        background: url("<?php print mw_includes_url(); ?>toolbar/editor_tools/color_picker/ico.transparentbg.png") no-repeat 1px 1px;
    }


    .clear_color_img {
        background: url("<?php print mw_includes_url(); ?>toolbar/editor_tools/color_picker/ico.clearbg.png") no-repeat 1px 1px;
    }

    <?php if(isset($_GET['onlypicker'])){ ?>
    #main_holder > * {
        display: none;
    }

    #main_holder #mwpicker {
        display: block;
    }

    #main_holder {
        padding: 0;
        margin: 0;
    }

    <?php  } ?>

    html[dir='rtl'] #main_holder .mw-ui-label{
        float: right;
    }
    html[dir='rtl'] #hex_color_value{
        float: left;
    }

</style>


<script type="text/javascript"
        src="<?php print mw_includes_url(); ?>toolbar/editor_tools/color_picker/jscolor.js"></script>
<script>
    mw.parent().require('external_callbacks.js');
   // mw.require('color.js');
    mw.lib.require('colorpicker');

</script>
<script type="text/javascript">


    _command = window.location.hash.replace("#", "");

    _prompt_is_open = false;

    _hide_selection = ['fontColor', 'fontbg'];

    setColor = function (color) {
        if (!!window.picker) {
            var color = color.contains('rgb') ? mw.color.rgbToHex(color) : color;
            var color = color.replace("#", "");
            picker.fromString(color);
        }
        else {
            setColor(color);
        }
    }

    $(window).load(function () {


        $(window).on('haschange', function () {
            _command = window.location.hash.replace("#", "");
        });

        if (_hide_selection.indexOf(_command) != -1) {
            $(parent.document.body).addClass('hide_selection');
        }


        color_holder = document.getElementById('my-colors');
        document_colors = {};
        mw.parent().$("body *").each(function () {
            var css = parent.getComputedStyle(this, null);
            if(css !== null){
                !document_colors[css.color] ? document_colors[css.color] = css.color : '';
                !document_colors[css.backgroundColor] ? document_colors[css.backgroundColor] = css.backgroundColor : '';
            }
        });

        var f = document.createDocumentFragment();



        var span = document.createElement('span');
        $(span).addClass("clear_color_img");
        span.title = "Clear Color";
        span.setAttribute('onclick', '_do("' + 'none' + '");');
        f.appendChild(span)

        color_holder.appendChild(f);


        for (var x in document_colors) {
            var color = mw.color.rgbToHex(document_colors[x]);
            if (color != 'transparent') {
                var span = document.createElement('span');
                span.style.background = color;
                span.title = color.toUpperCase();
                span.setAttribute('onclick', '_do("' + color.replace(/#/g, '') + '");');
                f.appendChild(span);
            }
        }
        var span = document.createElement('span');
        $(span).addClass("transparent");
        span.title = "Transparent Color";
        span.setAttribute('onclick', '_do("' + 'transparent' + '");');
        f.appendChild(span)

        color_holder.appendChild(f);

        $(document.body).mouseenter(function () {
            if(!!mw.parent().wysiwyg){
               mw.parent().wysiwyg.save_selected_element();
            }
        });
        $(document.body).mouseleave(function () {
          if(_prompt_is_open == false && !!mw.parent().wysiwyg){
            mw.parent().wysiwyg.deselect_selected_element();
          }
        });

        $(document.body).mousedown(function (e) {
            e.preventDefault()
        })

        var input = document.getElementById('colorpicker');

        picker = new jscolor.color(input);

        picker.showPicker();

    });

    _do = function (val) {
        val = val.contains('rgb') ? mw.color.rgbToHex(val) : val;
        val = val.replace("#", "");



        if( !!this.frameElement){

          parent.$(this.frameElement).trigger('colorChange', [val]);
        }
        if (!!mw.parent().iframecallbacks && typeof mw.parent().iframecallbacks[_command] === 'function') {
            mw.parent().iframecallbacks[_command](val);
        }
        else if (typeof parent[_command] === 'function') {
            parent[_command](val);
        }
        $('#hex_color_value').html('#'+val);
        $('#hex_color_value').css("background-color", '#'+val);
        $('#hex_color_value').css("color", '#'+(mw.color.isDark(val)?'fff':'000'));
        RegisterChange(val);

    }


    _color_prompt = function(){
        _prompt_is_open = true;
        mw.parent().wysiwyg.save_selection()
        var input = document.getElementById('colorpicker');
        var color = prompt("Please enter your color value", input.value);
        if (color != null) {
            mw.parent().wysiwyg.restore_selection();

             _do(color);

             $("#hex_color_value").css('color', '#'+(mw.color.isDark(color)?'fff':'000'))

        } else {
            mw.parent().wysiwyg.restore_selection();
        }
        _prompt_is_open = false;

    }

</script>

<div id="main_holder">

    <label class="mw-ui-label"><?php _e("Colors used in this page"); ?></label>

    <div id="my-colors"></div>

    <input type="hidden" id="colorpicker" onchange="_do(this.value);"/>

    <label class="mw-ui-label"><?php _e("Custom color"); ?> <a href="javascript:_color_prompt()" id="hex_color_value">#</a></label>

    <div id="mwpicker"></div>

</div>
