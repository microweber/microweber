<style>
    #my-colors {
        width: 235px;
        max-height: 90px;
        overflow-x: hidden;
        overflow-y: auto;
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

</style>


<script type="text/javascript"
        src="<?php print mw_includes_url(); ?>toolbar/editor_tools/color_picker/jscolor.js"></script>
<script>
    parent.mw.require('external_callbacks.js');
    mw.require('color.js');
</script>
<script type="text/javascript">


    _command = window.location.hash.replace("#", "");

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


        $(window).bind('haschange', function () {
            _command = window.location.hash.replace("#", "");
        });

        if (_hide_selection.indexOf(_command) != -1) {
            $(parent.mwd.body).addClass('hide_selection');
        }


        color_holder = mwd.getElementById('my-colors');
        document_colors = {};
        parent.mw.$("body *").each(function () {
            var css = parent.getComputedStyle(this, null);
            if(css !== null){
                !document_colors[css.color] ? document_colors[css.color] = css.color : '';
                !document_colors[css.backgroundColor] ? document_colors[css.backgroundColor] = css.backgroundColor : '';
            }

        });

        var f = mwd.createDocumentFragment();
        for (var x in document_colors) {
            var color = mw.color.rgbToHex(document_colors[x]);
            if (color != 'transparent') {
                var span = mwd.createElement('span');
                span.style.background = color;
                span.title = color.toUpperCase();
                span.setAttribute('onclick', '_do("' + color.replace(/#/g, '') + '");');
                f.appendChild(span);
            }
        }
        var span = mwd.createElement('span');
        $(span).addClass("transparent");
        span.title = "Transparent Color";
        span.setAttribute('onclick', '_do("' + 'transparent' + '");');
        f.appendChild(span)

        color_holder.appendChild(f);


        $(document.body).mouseenter(function () {
            if(!!parent.mw.wysiwyg){
               parent.mw.wysiwyg.save_selected_element();
            }

        });
        $(document.body).mouseleave(function () {
          if(!!parent.mw.wysiwyg){
            parent.mw.wysiwyg.deselect_selected_element();
          }
        });


        $(document.body).mousedown(function (e) {
            e.preventDefault()
        })


        var input = mwd.getElementById('colorpicker');

        picker = new jscolor.color(input);

        picker.showPicker();

    });

    _do = function (val) {
        if( !!this.frameElement){

          parent.$(this.frameElement).trigger('colorChange', [val]);
        }
        if (!!parent.mw.iframecallbacks && typeof parent.mw.iframecallbacks[_command] === 'function') {
            parent.mw.iframecallbacks[_command](val);
        }
        else if (typeof parent[_command] === 'function') {
            parent[_command](val);
        }

        RegisterChange(val);

    }


</script>

<div id="main_holder">

    <label class="mw-ui-label"><?php _e("Colors used in this page"); ?></label>

    <div id="my-colors"></div>

    <input type="hidden" id="colorpicker" onchange="_do(this.value);"/>

    <label class="mw-ui-label"><?php _e("Custom color"); ?></label>

    <div id="mwpicker"></div>

</div>
