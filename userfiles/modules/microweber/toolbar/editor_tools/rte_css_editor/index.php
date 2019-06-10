

<div id="tree"></div>

<script type="text/javascript">
    //parent.mw.require("external_callbacks.js");
    mw.require("jquery-ui.js");
    mw.require("events.js");
    mw.require("forms.js");
    mw.require("files.js");
    mw.require("tools.js");
    mw.require("url.js");
    mw.require('prop_editor.js');
    mw.require('color.js');
    mw.require('selector.js')
    mw.require('tree.js')
</script>
<script>

var ActiveNode = null;

var CSSShadow;

var _activeTree = null;
var activeTree = function(){
    if(!ActiveNode) {
        return;
    }
    var getParent = function(node){
        if(node === document.body || mw.tools.hasClass(node, 'edit')){
            return false;
        }
        if(node.parentNode.id){
            return node.parentNode
        } else {
            return getParent(node.parentNode);
        }
    };
    var data = [], curr = ActiveNode;
    while(curr && curr !== document.body && !mw.tools.hasClass(curr, 'edit')){
        if(curr.id){
            var parent = getParent(curr);
            var item = {
                id: curr.id,
                type: 'page',
                title: curr.tagName.toLowerCase() + '#' + curr.id,
                parent_id: parent ? parent.id : 0
            };

            data.push(item)
        }
        else {
            parent = null;
        }
        if(parent){
            curr = parent;
        }
        else {
            curr = null;
        }

    }
    data = data.reverse();

    $('#tree').empty();

    _activeTree = new mw.tree({
        element:'#tree',
        data:data,
        saveState: false
    });


};


var _prepare = {
    shadow: function () {
        var root = document.querySelector('#shadow')
        CSSShadow = new mw.propEditor.schema({
            schema: [
                {
                    interface: 'shadow',
                    id: 'boxShadow',
                    pickerPosition: 'top-left'
                }

            ],
            element: root,
            size:'medium'
        });
        $(CSSShadow).on('change', function(e, id, val){
            output(id, val)
        });
        $('.mw-ui-field', root).addClass('mw-ui-field-medium')
        $('.mw-ui-btn', root).addClass('mw-ui-btn-medium')
    },
    border: function () {


        $('#border-size, #border-color, #border-type').on('change input colorChange', function(){

            var prop = 'border',
                propval = $('#border-position').val();
            if(propval !== 'all') {
                prop += (propval)
            }
            var color = $('#border-color').val() || '#111',
                type = $('#border-type').val() || 'solid',
                size = $('#border-size').val() || '1';
            output( prop, size + 'px ' + type + ' ' + color);
        });
    },
    units: function(){
        var units = [
            'px', '%', 'rem', 'em', 'vh', 'vw'
        ];
        $('.unit').each(function(){
            var select = $('<select style="width: 60px"/>');
            var selectHolder = $('<div class="mw-field" data-prop="width" data-size="medium"></div>');
            $.each(units, function(){
                select.append('<option value="'+this+'">'+this+'</option>');
            });
            select.on('change', function(){
                var prev = $(this).parent().prev();
                output(prev.attr('data-prop'), prev.find('input').val() + this.value)
            });
            selectHolder.append(select);
            $(this).after(selectHolder)
            $('input',this).on('input', function(){
                var $el = $(this);
                var parent = $el.parent()
                var next = parent.next().find('select');
                var val = $el.val().trim();
                if(parseFloat(val) == val){
                    output( parent.attr('data-prop'), val + next.val())
                } else {
                    output( parent.attr('data-prop'), val + next.val())
                }
            })
        })
    }
};
var _populate = {
    margin: function(css){
        var margin = css.get.margin(undefined, true);
        mw.$('.margin-top').val(parseFloat(margin.top));
        mw.$('.margin-right').val(parseFloat(margin.right));
        mw.$('.margin-bottom').val(parseFloat(margin.bottom));
        mw.$('.margin-left').val(parseFloat(margin.left));
    },
    padding: function(css){
        var padding = css.get.padding(undefined, true);
        mw.$('.padding-top').val(parseFloat(padding.top));
        mw.$('.padding-right').val(parseFloat(padding.right));
        mw.$('.padding-bottom').val(parseFloat(padding.bottom));
        mw.$('.padding-left').val(parseFloat(padding.left))
    },
    common: function(css){
        $('.unit').each(function(){
            var val = css.css[this.dataset.prop];
            if(val) {
                var nval = parseFloat(val);
                var isn = !isNaN(nval);
                var unit = val.replace(/[0-9]/g, '').replace(/\./g, '');
                val = isn ? nval : val;
                if(isn){

                    $(this).next().find('select').val(unit)
                }
                $('input', this).val(val)
            }

        });
        $(".colorField").each(function(){
            if(this.dataset.prop) {
                var color = css.css[this.dataset.prop];
                this.style.backgroundColor = color;
                this.style.color = mw.color.isDark(color) ? 'white' : 'black';
                this.value = color.indexOf('rgb(') === 0 ? mw.color.rgbToHex(color) : color;
            }
        });
        $(".background-preview").css('backgroundImage', css.css.backgroundImage)
    },
    textAlign: function(css){
        var align = css.get.alignNormalize();
        $(".text-align .active").removeClass('active');
        $(".text-align .ta-" + align).addClass('active');
    },
    regular: function(css){
        $(".regular").each(function(){
            $(this).val(css.css[this.dataset.prop])
        });
    }
};

var populate = function(css){
    $.each(_populate, function(){
        this(css)
    })
};

var output = function(property, value){
    if(ActiveNode) {
          ActiveNode.style[property] = value;
          top.mw.wysiwyg.change(ActiveNode);
          top.mw.liveEditSelector.positionSelected()
    }
};

var init = function(){
    mw.$('.margin-top').on('input', function(){ output('marginTop', this.value + 'px') });
    mw.$('.margin-right').on('input', function(){ output('marginRight', this.value+ 'px') });
    mw.$('.margin-bottom').on('input', function(){ output('marginBottom', this.value+ 'px') });
    mw.$('.margin-left').on('input', function(){ output('marginLeft', this.value+ 'px') });

    mw.$('.padding-top').on('input', function(){ output('paddingTop', this.value+ 'px') });
    mw.$('.padding-right').on('input', function(){ output('paddingRight', this.value+ 'px') });
    mw.$('.padding-bottom').on('input', function(){ output('paddingBottom', this.value+ 'px') });
    mw.$('.padding-left').on('input', function(){ output('paddingrginLeft', this.value+ 'px') });

    $('.text-align > span').on('click', function(){
        output('textAlign', this.dataset.value);
        $('.text-align > .active').removeClass('active')
        $(this).addClass('active')
    });
    $(".colorField").each(function(){
        var el = this;
        mw.colorPicker({
            element:this,
            position:'bottom-center',
            onchange:function(color){
                if(el.dataset.prop) {
                    output(el.dataset.prop, color);
                } else if(el.dataset.func) {
                    eval(el.dataset.func + '(' + color + ')');
                } else {
                    $(el).trigger('colorChange', color)
                }
                el.style.backgroundColor = color;
                el.style.color = mw.color.isDark(color) ? 'white' : 'black';
            }
        });
    });

    $(".regular").on('input', function(){
        output(this.dataset.prop, this.value)
    });

    $("#background-select-item").on("click", function () {
        mw.fileWindow({
            types: 'images',
            change: function (url) {
                url = url.toString();
                output('backgroundImage', 'url(' + url + ')')
                $('.background-preview').css('backgroundImage', 'url(' + url + ')')
            }
        });
    });

    _prepare.units();
    _prepare.border();
    _prepare.shadow();

    $('.mw-ui-box-header').on('click', function(){
        setTimeout(function(){
          $(document.body).trigger('click')
        }, 400)
    })


};



top.$(top.mw.liveEditSelector).on('select', function(e, nodes){
    if(nodes && nodes[0]){
        var css = mw.CSSParser(nodes[0]);
        populate(css);
        ActiveNode = nodes[0];
        activeTree();
    }
});



    $(document).ready(function(){

        $(".mw-element-spacing-editor input")
            .on('focus', function(){

                $(".mw-ese-holder.active").removeClass('active');
                $(this).parent().addClass('active');
            })
            .on('blur', function(){
                $(".mw-ese-holder.active").removeClass('active');
        });

        init();

        var editorRoot = document.getElementById('css-editor-root');

        setInterval(function(){
            editorRoot.classList[ActiveNode ? 'remove' : 'add']('disabled');
        }, 700)

    });

    $(window).on('load', function () {
        setTimeout(function(){
            $(document.body).trigger('click')
        }, 400)

    });
</script>


<style>

    <?php include "style.css";  ?>
</style>
<div id="css-editor-root">

<div data-mwcomponent="accordion" class="mw-ui-box mw-accordion">
    <div class="mw-ui-box-header mw-accordion-title">Size</div>
    <div class="mw-accordion-content mw-ui-box-content">
        <div class="mw-esr">
            <div class="mw-esc">
                <label>Width</label>
                <div class="mw-multiple-fields">
                    <div class="mw-field unit" data-prop="width" data-size="medium"><input type="text"></div>
                </div>
            </div>
            <div class="mw-esc">
                <label>Height</label>
                <div class="mw-multiple-fields">
                    <div class="mw-field unit" data-prop="height" data-size="medium"><input type="text"></div>
                </div>
            </div>
        </div>
        <div class="mw-esr">
            <div class="mw-esc">
                <label>Min Width</label>
                <div class="mw-multiple-fields">
                    <div class="mw-field unit" data-prop="minWidth" data-size="medium"><input type="text"></div>
                </div>
            </div>
            <div class="mw-esc">
                <label>Min Height</label>
                <div class="mw-multiple-fields">
                    <div class="mw-field unit" data-prop="minHeight" data-size="medium"><input type="text"></div>
                </div>
            </div>

        </div>
        <div class="mw-esr">
            <div class="mw-esc">
                <label>Max Width</label>
                <div class="mw-multiple-fields">
                    <div class="mw-field unit" data-prop="maxWidth" data-size="medium"><input type="text"></div>
                </div>
            </div>
            <div class="mw-esc">
                <label>Max Height</label>
                <div class="mw-multiple-fields">
                    <div class="mw-field unit" data-prop="maxHeight" data-size="medium"><input type="text"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div data-mwcomponent="accordion" class="mw-ui-box mw-accordion">
    <div class="mw-ui-box-header mw-accordion-title">Spacing</div>
    <div class="mw-accordion-content mw-ui-box-content">
        <div class="mw-element-spacing-editor">
            <span class="mw-ese-label">Margin</span>
            <div class="mw-ese-holder mw-ese-margin">
                <input class="mw-ese-top margin-top">
                <input class="mw-ese-right margin-right">
                <input class="mw-ese-bottom margin-bottom">
                <input class="mw-ese-left margin-left">
                <div class="mw-ese-holder mw-ese-padding">
                    <input class="mw-ese-top padding-top">
                    <input class="mw-ese-right padding-right">
                    <input class="mw-ese-bottom padding-bottom">
                    <input class="mw-ese-left padding-left">
                    <span class="mw-ese-label">Padding</span>
                </div>
            </div>

        </div>
    </div>
</div>

<div data-mwcomponent="accordion" class="mw-ui-box mw-accordion">
    <div class="mw-ui-box-header mw-accordion-title">Typography</div>
    <div class="mw-accordion-content mw-ui-box-content">
        <div class="s-field">
            <label>Text align</label>
            <div class="s-field-content">
                <div class="text-align">
                    <span class="ta-left" data-value="left"><i></i><i></i><i></i></span>
                    <span class="ta-center" data-value="center"><i></i><i></i><i></i></span>
                    <span class="ta-right" data-value="right"><i></i><i></i><i></i></span>
                    <span class="ta-justify" data-value="justify"><i></i><i></i><i></i></span>
                </div>
            </div>
        </div>
        <div class="s-field">
            <label>Size</label>
            <div class="s-field-content">
                <div class="mw-multiple-fields">
                    <div class="mw-field unit" data-prop="fontSize" data-size="medium"><input type="text"></div>
                </div>
            </div>
        </div>
        <div class="s-field">
            <label>Color</label>
            <div class="s-field-content">
                <div class="mw-multiple-fields">
                    <div class="mw-field" data-size="medium"><input type="text" class="colorField" data-prop="color"></div>
                </div>
            </div>
        </div>
        <div class="s-field">
            <label>Style</label>
            <div class="s-field-content">
                <div class="mw-multiple-fields">
                    <div class="mw-field" data-size="medium">
                        <select class="regular" data-prop="fontStyle">
                            <option value="normal">normal</option>
                            <option value="italic">italic</option>
                            <option value="oblique">oblique</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="s-field">
            <label>Weight</label>
            <div class="s-field-content">
                <div class="mw-multiple-fields">
                    <div class="mw-field" data-size="medium">
                        <select class="regular" data-prop="fontWeight">
                            <option value="normal">normal</option>
                            <option value="bold">bold</option>
                            <option value="bolder">bolder</option>
                            <option value="lighter">lighter</option>
                            <option value="100">100</option>
                            <option value="200">200</option>
                            <option value="300">300</option>
                            <option value="400">400</option>
                            <option value="500">500</option>
                            <option value="600">600</option>
                            <option value="700">700</option>
                            <option value="800">800</option>
                            <option value="900">900</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="s-field">
            <label>Text transform</label>
            <div class="s-field-content">
                <div class="mw-multiple-fields">
                    <div class="mw-field" data-size="medium">
                        <select class="regular" data-prop="textTransform">
                            <option value="none">none</option>
                            <option value="capitalize">capitalize</option>
                            <option value="uppercase">uppercase</option>
                            <option value="lowercase">lowercase</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="s-field">
            <label>Word Spacing</label>
            <div class="s-field-content">
                <div class="mw-multiple-fields">
                    <div class="mw-field unit" data-prop="wordSpacing" data-size="medium"><input type="text"></div>
                </div>
            </div>
        </div>
        <div class="s-field">
            <label>Letter Spacing</label>
            <div class="s-field-content">
                <div class="mw-multiple-fields">
                    <div class="mw-field unit" data-prop="letterSpacing" data-size="medium"><input type="text"></div>
                </div>
            </div>
        </div>



    </div>
</div>

<div data-mwcomponent="accordion" class="mw-ui-box mw-accordion">
    <div class="mw-ui-box-header mw-accordion-title">Background</div>
    <div class="mw-accordion-content mw-ui-box-content">
        <div class="s-field">
            <label>Image</label>
            <div class="s-field-content">
                <span class="background-preview"></span>
                <span class="mw-ui-btn mw-ui-btn-medium" id="background-select-item">Image</span>
            </div>
        </div>
        <div class="s-field">
            <label>Color</label>
            <div class="s-field-content">
                <div class="mw-field" data-size="medium">
                    <input type="text" class="colorField" data-prop="backgroundColor">
                </div>
            </div>
        </div>

        <div class="s-field">
            <label>Size</label>
            <div class="s-field-content">
                <div class="mw-field" data-size="medium">
                    <select type="text" class="regular" data-prop="backgroundSize">
                        <option value="auto">Auto</option>
                        <option value="contain">Fit</option>
                        <option value="cover">Cover</option>
                        <option value="100% 100%">Scale</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="s-field">
            <label>Repeat</label>
            <div class="s-field-content">
                <div class="mw-field" data-size="medium">
                    <select type="text" class="regular" data-prop="backgroundRepeat">
                        <option value="repeat">repeat</option>
                        <option value="no-repeat">no-repeat</option>
                        <option value="repeat-x">repeat horizontally</option>
                        <option value="repeat-y">repeat vertically </option>
                    </select>
                </div>
            </div>
        </div>
        <div class="s-field">
            <label>Position</label>
            <div class="s-field-content">
                <div class="mw-field" data-size="medium">
                    <select type="text" class="regular" data-prop="backgroundPosition">
                        <option value="0% 0%">Left Top</option>
                        <option value="50% 0%">Center Top</option>
                        <option value="100% 0%">Right Top</option>

                        <option value="0% 50%">Left Center</option>
                        <option value="50% 50%">Center Center</option>
                        <option value="100% 50%">Right Center</option>

                        <option value="0% 100%">Left Bottom</option>
                        <option value="50% 100%">Center Bottom</option>
                        <option value="100% 100%">Right Bottom</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>

<div data-mwcomponent="accordion" class="mw-ui-box mw-accordion">
    <div class="mw-ui-box-header mw-accordion-title">Border</div>
    <div class="mw-accordion-content mw-ui-box-content">
        <div class="s-field">
            <label>Position</label>
            <div class="s-field-content">
                <div class="mw-field" data-size="medium">
                    <select type="text" id="border-position">
                        <option value="all" selected>All</option>
                        <option value="Top">Top</option>
                        <option value="Right">Right</option>
                        <option value="Bottom">Bottom</option>
                        <option value="Left">Left</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="s-field">
            <label>Size</label>
            <div class="s-field-content">
                <div class="mw-multiple-fields">
                    <div class="mw-field" data-size="medium"><input type="text" id="border-size"></div>
                </div>
            </div>
        </div>
        <div class="s-field">
            <label>Color</label>
            <div class="s-field-content">
                <div class="mw-field" data-size="medium">
                    <input type="text" class="colorField" id="border-color">
                </div>
            </div>
        </div>
        <div class="s-field">
            <label>Type</label>
            <div class="s-field-content">
                <div class="mw-field" data-size="medium">
                    <select type="text" id="border-type">
                        <option value="" disabled selected>choose</option>
                        <option value="none">none</option>
                        <option value="solid">solid</option>
                        <option value="dotted">dotted</option>
                        <option value="dashed">dashed</option>
                        <option value="double">double</option>
                        <option value="groove">groove</option>
                        <option value="ridge">ridge</option>
                        <option value="inset">inset</option>
                        <option value="outset">outset</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
<div data-mwcomponent="accordion" class="mw-ui-box mw-accordion">
    <div class="mw-ui-box-header mw-accordion-title">Miscellaneous</div>
    <div class="mw-accordion-content mw-ui-box-content">
        <div class="rouded-corners" >
            <label>Rounded Corners</label>
            <div class="s-field-content">
                <div class="mw-field" data-size="medium">
                    <div class="mw-multiple-fields">
                        <div class="mw-field" data-size="medium">
                            <span class="mw-field-prepend"></span>
                            <input type="text" class="regular" data-prop="borderTopLeftRadius">
                        </div>
                        <div class="mw-field" data-size="medium"><span class="mw-field-prepend"></span><input class="regular" type="text" data-prop="borderTopRightRadius"></div>
                    </div>
                </div>
                <div class="mw-field" data-size="medium">
                    <div class="mw-multiple-fields">
                        <div class="mw-field" data-size="medium"><span class="mw-field-prepend"></span><input class="regular" type="text" data-prop="borderBottomLeftRadius"></div>
                        <div class="mw-field" data-size="medium"><span class="mw-field-prepend"></span><input class="regular" type="text" data-prop="borderBottomRightRadius"></div>
                    </div>
                </div>
            </div>
        </div>
        <label>Element shadow</label>
        <div id="shadow"></div>

    </div>
</div>


<div class="mw-css-editor">

</div>
</div>
