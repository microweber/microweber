
<div id="domtree"></div>

<style>
    #css-editor-root .mw-accordion-title{
        font-weight: bold;
    }

    #columns-edit .mw-field{
        padding-bottom: 15px;
    }
    #columns-edit .mdi{
        font-size: 19px;
        position: relative;
        top: 4px;
        margin-inline-end: 15px;
        margin-inline-start: 15px;
    }

</style>

<script type="text/javascript">

    // mw.parent().require("external_callbacks.js");
    mw.require("jquery-ui.js");
    mw.require("events.js");
    mw.require("forms.js");
    mw.require("files.js");
    mw.require("url.js");
    mw.require('prop_editor.js');
    mw.require('color.js');
    mw.require('selector.js');
    mw.require('tree.js');

    mw.require('domtree.js');


    mw.require('css_parser.js');


    $(window).on('load', function () {

       setTimeout(function() {
            mw.top().liveEditDomTree = new mw.DomTree({
                element: '#domtree',
                resizable:true,
                targetDocument: mw.top().win.document,
                onHover: function (e, target, node, element) {
                    mw.top().liveEditSelector.setItem(node, mw.top().liveEditSelector.interactors, false);
                },
                onSelect: function (e, target, node, element) {
                     setTimeout(function () {
                        mw.top().liveEditSelector.select(node);

                        mw.top().tools.scrollTo(node, undefined, (mw.top().$('#live_edit_toolbar').height() + 10))
                    })
                }
            });
        }, 700);
    })

</script>
<script>

var ActiveNode = null;

var reset = function(){
    if(!ActiveNode){
        return;
    }
    var sel = mw.tools.generateSelectorForNode(ActiveNode);
    var data = {};
    data[sel] = {
        selector: sel,
        value: "reset"
    };

    mw.top().$.post(mw.settings.api_url + "current_template_save_custom_css", data, function(data){
        mw.notification.success('Element styles restored');
        mw.tools.refresh(top.document.querySelector('link[href*="live_edit.css"]'))
    }).fail(function(){

    });
    mw.top().wysiwyg.change(ActiveNode)
};


var CSSShadow;

var _activeTree = null;
var _pauseActiveTree = false;
var activeTree = function(){
    if(!ActiveNode || _pauseActiveTree) {
        return;
    }
    var getParent = function(node){
        if(!node || node === document.body || !node.parentNode || mw.tools.hasClass(node, 'edit')){
            return false;
        }
        if(node.parentNode.id){
            return node.parentNode;
        } else  if(mw.tools.hasClass(node.parentNode, 'edit')){
            return node.parentNode;
        } else {
            return getParent(node.parentNode);
        }
    };
    var data = [], curr = ActiveNode, count = 0;
    while(curr && curr !== document.body){
        var custom = !!curr.className;
        if(curr.id || mw.tools.hasClass(curr, 'edit') || custom){
            count++;
            if (count > 4) {
                break;
            }
            var parent = getParent(curr);
            var selector = mw.tools.generateSelectorForNode(curr)
                .replace(/\[/g, 'mw')
                .replace(/["']/g, '')
                .replace(/\]/g, 'mw');
            var parent_selector = 0;

            if(parent) {
                parent_selector =  mw.tools.generateSelectorForNode(parent)
                    .replace(/\[/g, 'mw')
                    .replace(/["']/g, '')
                    .replace(/\]/g, 'mw');
            }
            var ttitle = curr.tagName.toLowerCase() + (curr.classList.length ? ('.' + curr.className.split(' ').join('.')) : '');
            if(mw.tools.hasClass(curr, 'module')) {
                ttitle = curr.dataset.mwTitle || curr.dataset.type;
            }
            var item = {
                id: selector,
                type: 'page',
                title: ttitle ,
                parent_id: parent_selector,
                parent_type: 'page',
                element: curr
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
        saveState: false,
        selectable: true,
        singleSelect:true,
        contextMenu: [
            {
                title: 'Reset styles',
                icon: 'mw-icon-reload',
                action: function() {
                    reset()
                }
            }
        ]
    });

    _activeTree.openAll();
    _activeTree.select($('#tree li:last')[0]);

    $(_activeTree).on('selectionChange', function(e, data){
        _pauseActiveTree = true;
        if(data[0]){
            mw.top().liveEditSelector.select(data[0].element);
        }
        setTimeout(function(){
            _pauseActiveTree = false;
        }, 10)
    })


};


var _prepare = {
    shadow: function () {
        var root = document.querySelector('#shadow');
        if(!root) {
            return;
        }
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
        $('.mw-ui-field', root).addClass('mw-ui-field-medium');
        $('.mw-ui-btn', root).addClass('mw-ui-btn-medium');
    },
    border: function () {

        var bordercolor = document.querySelector('#border-color')
        mw.colorPicker({
            element: bordercolor,
            position: bordercolor.dataset.position || 'top-right',
            onchange: function (color){

                    $(bordercolor).trigger('colorChange', color)

            },
            color: this.value
        })

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
        units = [];
        $('.unit').each(function(){
            // var select = $('<select style="width: 60px"/>');
            var select = $('<span class="reset-field  tip" data-tipposition="top-right" data-tip="Restore default value"><i class="mdi mdi-history"></i></span>');
            select.on('click', function () {
                var prev = $(this).prev();
                output( prev.attr('data-prop'), '');
                prev.find('input').val(this._defaultValue);
                $('.mw-range.ui-slider', prev).slider('value', this._defaultValue || 0)
            });
            $('input', this)
                .attr('type', 'range');

                //.after('<input>')
            $.each(units, function(){
                select.append('<option value="'+this+'">'+this+'</option>');
            });
            select.on('change', function(){
                var prev = $(this).parent().prev();
                output(prev.attr('data-prop'), prev.find('input').val() + this.value)
            });

            $(this).after(select)
            $('input',this).on('input', function(){
                var $el = $(this);
                var parent = $el.parent()
                var next = parent.next().find('select');
                var val = $el.val().trim();
                if(parseFloat(val) == val){
                    output( parent.attr('data-prop'), val ? val + 'px' : '');
                } else {
                    output( parent.attr('data-prop'), val ? val + 'px' : '');
                }
            })
        })
    }
};
var _populate = {
    margin: function(css){
        if(!css || !css.get) return;
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
            var btn = $('.mw-ui-btn', this.parentNode)[0];
            if(btn) {
                btn._defaultValue = '';
            }

            if(val) {
                var nval = parseFloat(val);
                var isn = !isNaN(nval);
                var unit = val.replace(/[0-9]/g, '').replace(/\./g, '');
                val = isn ? nval : val;
                if(btn) {
                    btn._defaultValue = val;
                }
                $('input', this).val(val);
                $('.mw-range.ui-slider', this).slider('value', isn ? nval : 0)
            }

        });
        $(".colorField").each(function(){
            if(this.dataset.prop) {
                var color = css.css[this.dataset.prop];

                var hasColor = color !== 'rgba(0, 0, 0, 0)';

                if(color) {
                    if(hasColor) {
                        this.value = mw.color.rgbOrRgbaToHex(color);
                    } else {
                        this.value = 'none';
                        this.previousElementSibling.querySelector('.mw-field-color-indicator-display').style.backgroundColor = 'transparent'
                    }

                }

                this.type = 'text'

                var el = this;


                el.placeholder = '#ffffff';
                if(this.parentNode.querySelector('.mw-field-color-indicator') === null) {
                    $(this).before('<span class="mw-field-color-indicator"><span class="mw-field-color-indicator-display"></span></span>')
                }
                this.parentNode.querySelector('.mw-field-color-indicator-display').style.backgroundColor = this.value

                mw.colorPicker({
                    element: this,
                    position: this.dataset.position || 'bottom-right',
                    onchange: function (color){
                         if(el.dataset.prop) {
                            output(el.dataset.prop, color);
                        } else if(el.dataset.func) {
                            eval(el.dataset.func + '(' + color + ')');
                        } else {
                            $(el).trigger('colorChange', color)
                        }
                    },
                    color: this.value
                })

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
            var propName = this.dataset.prop;
            if(propName === 'fontFamily'){
                var val = css.css[this.dataset.prop].replace(/\"/g, "");
                var el = $(this)
                el.val(val);
                if(el.val() === null) {
                    el.append('<option value="'+val+'">'+val+'</option>');
                    el.val(val);
                }
            } else {
                $(this).val(css.css[this.dataset.prop])
            }
        });
    }
};

var populate = function(css){
    $.each(_populate, function(){
        this(css)
    })
};

var sccontainertype = function (value){
    var cnt = mw.tools.firstParentOrCurrentWithAnyOfClasses(ActiveNode, ['container', 'container-fluid']);
    if(cnt && mw.tools.isEditable(cnt)){
        cnt.classList.remove('container');
        cnt.classList.remove('container-fluid');
        cnt.classList.add(value);
        mw.top().wysiwyg.change(cnt);
    }
}
var scColumns = function (property, value){
    var tg = ActiveNode;
    while (tg && tg.classList) {
        if(!tg.classList.contains('col') && !tg.className.contains('col-')) {
            tg = tg.parentNode;
        } else {
            break
        }
    }

    if(property === 'col-desktop') {
        for (var i = 1; i <= 12; i++) {
            tg.classList.remove('col-' + i)
            tg.classList.remove('col-lg-' + i)
        }
        // tg.classList.add('col-' + value)
        tg.classList.add('col-lg-' + value)
    } else if(property === 'col-tablet') {
        for (var i = 1; i <= 12; i++) {
            tg.classList.remove('col-md-' + i)
        }
        tg.classList.add('col-md-' + value)
    } else if(property === 'col-mobile') {
        for (var i = 1; i <= 12; i++) {
            tg.classList.remove('col-xs-' + i)
            tg.classList.remove('col-sm-' + i)
        }
        tg.classList.add('col-sm-' + value)
    }
}

var OverlayNode = null;

var specialCases = function (property, value){
    if(!property) return;
    if(property.includes('col-')){
        scColumns(property, value)
        return true;
    } else if(OverlayNode && property === 'overlay-color') {
        OverlayNode.style.backgroundColor = value;
        mw.top().wysiwyg.change(OverlayNode);
        return true;
    }

}

var populateSpecials = function (css) {
    var holder = document.getElementById('columns-edit');
    var colDesktop = document.querySelector('[data-prop="col-desktop"]')
    var coltablet = document.querySelector('[data-prop="col-tablet"]')
    var colmobile = document.querySelector('[data-prop="col-mobile"]')
    colDesktop.value = ''
    coltablet.value = ''
    colmobile.value = ''
    holder.style.display = 'none';

    var containerType = document.querySelector('#field-conatiner-type');
    containerType.style.display = 'none';
    var ol = document.getElementById('overlay-edit');
    ol.style.display = 'none';
    OverlayNode = null;

    if(ActiveNode) {
         var cnt = mw.tools.firstParentOrCurrentWithAnyOfClasses(ActiveNode, ['container', 'container-fluid']);
        if(cnt && mw.tools.isEditable(cnt)){
            containerType.style.display = '';
            if(cnt.classList.contains('container-fluid')) {
                document.querySelector('[name="containertype"][value="container-fluid"]').checked = true
            } else  if(cnt.classList.contains('container')) {
                document.querySelector('[name="containertype"][value="container"]').checked = true
            }
        }

        var layout = mw.tools.firstParentOrCurrentWithAnyOfClasses(ActiveNode, ['module-layouts']);
        if(layout) {
            var overlay = layout.querySelector('.mw-layout-overlay');
            OverlayNode = overlay;
            if(overlay) {
                var overlayCss = getComputedStyle(overlay);
                var bgColor = overlayCss.backgroundColor;
                var oc = document.getElementById('overlay-color')
                oc.value = bgColor
                oc.style.backgroundColor = bgColor
                ol.style.display = '';
            }

        }



        var col = null;
        var tg = ActiveNode;
        while (tg && tg.classList) {
            if(!tg.classList.contains('col') && !tg.className.contains('col-')) {
                tg = tg.parentNode;
            } else {
                if(mw.tools.isEditable(tg)){
                    col = tg;
                    break
                } else {
                    tg = tg.parentNode;
                }
            }
        }
        if(col) {
            holder.style.display = '';
            var lg = col.className.split('col-lg-')[1] || '';
            var md = col.className.split('col-md-')[1] || '';
            var sm = col.className.split('col-sm-')[1] || '';
            colDesktop.value = lg.split(' ')[0];
            coltablet.value = md.split(' ')[0];
            colmobile.value = sm.split(' ')[0];
        }
    }
}

var output = function(property, value){
    if(!ActiveNode) {
        ActiveNode = mw.top().liveEditSelector.selected
    }
    if(ActiveNode.length) {
        ActiveNode = ActiveNode[0]
    }
    if(ActiveNode) {
        if(!specialCases(property, value)) {
            //  ActiveNode.style[property] = value;
            mw.top().liveedit.cssEditor.temp(ActiveNode, property.replace( /([a-z])([A-Z])/g, '$1-$2' ).toLowerCase(), value + '!important')
            // ActiveNode.style.setProperty(property, value);
            ActiveNode.setAttribute('staticdesign', true);
        }
        mw.top().wysiwyg.change(ActiveNode);
        mw.top().liveEditSelector.positionSelected();
    }
};

var numValue = function (value) {
    return value ? value + 'px' : '';
};

var init = function(){
    mw.$('.margin-top').on('input', function(){ output('marginTop', numValue(this.value)) });
    mw.$('.margin-right').on('input', function(){ output('marginRight', numValue(this.value)) });
    mw.$('.margin-bottom').on('input', function(){ output('marginBottom', numValue(this.value)) });
    mw.$('.margin-left').on('input', function(){ output('marginLeft', numValue(this.value)) });

    mw.$('.padding-top').on('input', function(){ output('paddingTop', numValue(this.value)) });
    mw.$('.padding-right').on('input', function(){ output('paddingRight', numValue(this.value)) });
    mw.$('.padding-bottom').on('input', function(){ output('paddingBottom', numValue(this.value)) });
    mw.$('.padding-left').on('input', function(){ output('paddingLeft', numValue(this.value)) });

    $('.text-align > span').on('click', function(){
        output('textAlign', this.dataset.value);
        $('.text-align > .active').removeClass('active');
        $(this).addClass('active')
    });
    $(".colorField").each(function(){
        var el = this;
        el.oninput = function(){
            var color = this.value;
            if(el.dataset.prop) {
                output(el.dataset.prop, color);
            } else if(el.dataset.func) {
                eval(el.dataset.func + '(' + color + ')');
            } else {
                $(el).trigger('colorChange', color)
            }
        }
    });

    $(".regular").on('input', function(){
        output(this.dataset.prop, this.value)
    });

    $("#background-remove").on("click", function () {
        $('.background-preview').css('backgroundImage', 'none');
        output('backgroundImage', 'none')
    });
    $("#background-reset").on("click", function () {
        output('backgroundImage', '');
    });
    $("#background-select-item").on("click", function () {
        var dialog;
        var picker = new mw.filePicker({
            type: 'images',
            label: false,
            autoSelect: false,
            footer: true,
            _frameMaxHeight: true,

            onResult: function (data) {
                if(!data) return;
                var url = data.src ? data.src : data;
                output('backgroundImage', 'url(' + url + ')');
                $('.background-preview').css('backgroundImage', 'url(' + url + ')')
                dialog.remove()
            },
            cancel: function () {
                dialog.remove()
            }
        });
        dialog = mw.top().dialog({
            content: picker.root,
            title: mw.lang('Select image'),
            footer: false,
            width: 1200
        })


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



mw.top().$(mw.top().liveEditSelector).on('select', function(e, nodes){
    if(nodes && nodes[0] && nodes[0].nodeType === 1){
        var css = mw.CSSParser(nodes[0]);
        populate(css);
        ActiveNode = nodes[0];

        populateSpecials(css);

        var clsdata = [];
        $.each(nodes[0].className.split(' '), function(){
            var cls = this.trim();
            if(cls) {
                clsdata.push({title: cls})
            }
        });
        ( window.classes || initClasses() ).setData(clsdata)
    }

    if(ActiveNode){
        var can = ActiveNode.textContent === ActiveNode.innerHTML;
        mw.$('#text-mask')[can ? 'show' : 'hide']();
        mw.$('#text-mask-field')[0].checked = mw.tools.hasClass(ActiveNode, 'mw-bg-mask');
        if(!mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(ActiveNode.parentNode, ['edit', 'module'])) {
            $('#classtags-accordion').hide();
        } else{
            $('#classtags-accordion').show();
        }
    }
});

    $(document).ready(function(){
        mw.$('.mw-field input').attr('autocomplete', 'off')
        mw.top().$(top.document.body).on('mousedown touchstart', function(e){
            var node = mw.tools.firstMatchesOnNodeOrParent(e.target, ['.element', '.module']);
            if( !node && !mw.tools.firstParentOrCurrentWithAnyOfClasses(e.target, ['mw-control-box', 'mw-defaults']) ){
                ActiveNode = null;
            }
        });

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
        if(mw.top().liveEditSelector.selected[0]){
            ActiveNode = mw.top().liveEditSelector.selected[0];

             if(ActiveNode){
                var css = mw.CSSParser(ActiveNode);
                populate(css);

                var can = ActiveNode.innerText === ActiveNode.innerHTML;
                mw.$('#text-mask')[can ? 'show' : 'hide']();

                mw.$('#text-mask-field')[0].checked = mw.tools.hasClass(ActiveNode, 'mw-bg-mask');
            }
            populateSpecials(css);
        }
        mw.top().liveEditSelector.positionSelected();
        setTimeout(function(){
            $(document.body).trigger('click')
        }, 400)

    });
</script>


<style>

    <?php include "style.css";  ?>
    <?php
        if (_lang_is_rtl()) {
            include "rtl.css";
        }
    ?>

</style>
<div id="css-editor-root">

    <script>
        mw.require('tags.js');

        initClasses = function () {
            if(!window.classes) {
                window.classes = new mw.tags({
                    element: '#classtags',
                    data: [],
                    inputField: true,
                    wrap: true,
                    hideItem: function(item) {
                        return item.title.indexOf('module') !== -1
                            || item.title.indexOf('element') !== -1
                            || item.title.indexOf('allow-drop') !== -1
                            || item.title.indexOf('cloneable') !== -1
                            || item.title.indexOf('ui-draggable') !== -1
                            || item.title.indexOf('ui-draggable-handle') !== -1
                            || item.title === 'edit'
                            || item.title === 'safe-mode'
                            || item.title === 'parallax'
                            || item.title === 'changed'
                            || item.title === 'pull-left'
                            || item.title === 'left'
                            || item.title === 'right'
                            || item.title === 'pull-right'
                            || item.title === 'mw-bg-mask'
                            || item.title === 'lipsum'
                            || item.title.indexOf('nodrop') !== -1;
                    }
                });
                $(classes).on('change', function(e, item, data){
                    var cls = [];
                    $.each(data, function(){
                        cls.push(this.title);
                    });
                    ActiveNode.setAttribute('class', cls.join(' '))
                    mw.top().wysiwyg.change(ActiveNode);
                });
            }
            return window.classes;
        };


         $(window).on('load', function(){
            initClasses()


        })

    </script>
    <div data-mwcomponent="accordion" class="mw-ui-box mw-accordion">




<mw-accordion-item >
    <div class="mw-ui-box-header mw-accordion-title"><?php _e("Background"); ?></div>
    <div class="mw-accordion-content mw-ui-box-content">
        <div class="s-field">
            <label><?php _e("Image"); ?></label>
            <div class="s-field-content">
            <div class="mw-ui-btn-nav" id="background-image-nav">

                <span
                    class="mw-ui-btn mw-ui-btn-outline mw-ui-btn-small tip mdi mdi-folder-image mdi-17px" data-tip="Select background image"
                    id="background-select-item"><span class="background-preview"></span></span>
                <span id="background-remove" class="mw-ui-btn mw-ui-btn-outline mw-ui-btn-small tip" data-tip="Remove background" data-tipposition="top-right"><span class="mdi mdi-delete"></span></span>
                <span id="background-reset" class="mw-ui-btn mw-ui-btn-outline mw-ui-btn-small tip" data-tip="Reset background" data-tipposition="top-right"><span class="mdi mdi-history"></span></span>
            </div>
            </div>
        </div>
        <div class="s-field">
            <label><?php _e("Color"); ?></label>
            <div class="s-field-content">
                <div class="mw-field mw-field-flat" data-size="medium">
                    <span class="mw-field-color-indicator"><span class="mw-field-color-indicator-display"></span></span>
                    <input type="text" class="colorField unit" data-prop="backgroundColor">
                </div>
            </div>
        </div>

        <div class="s-field">
            <label><?php _e("Size"); ?></label>
            <div class="s-field-content">
                <div class="mw-field mw-field-flat" data-size="medium">
                    <select type="text" class="regular" data-prop="backgroundSize">
                        <option value="auto"><?php _e("Auto"); ?></option>
                        <option value="contain"><?php _e("Fit"); ?></option>
                        <option value="cover"><?php _e("Cover"); ?></option>
                        <option value="100% 100%"><?php _e("Scale"); ?></option>
                    </select>
                </div>
            </div>
        </div>
        <div class="s-field">
            <label><?php _e("Repeat"); ?></label>
            <div class="s-field-content">
                <div class="mw-field mw-field-flat" data-size="medium">
                    <select type="text" class="regular" data-prop="backgroundRepeat">
                        <option value="repeat"><?php _e("repeat"); ?></option>
                        <option value="no-repeat"><?php _e("no-repeat"); ?></option>
                        <option value="repeat-x"><?php _e("repeat horizontally"); ?></option>
                        <option value="repeat-y"><?php _e("repeat vertically "); ?></option>
                    </select>
                </div>
            </div>
        </div>
        <div class="s-field" id="text-mask">
            <label>Text mask</label>
            <script>
                mask = function (val) {
                    var $node = $(ActiveNode);
                    var action = val ? 'addClass' : 'removeClass';
                    $node[action]('mw-bg-mask');
                    if (action === 'addClass') {
                        output('color', 'transparent')
                    } else {
                        output('color', '')
                    }
                    mw.top().wysiwyg.change($node[0]);
                }
            </script>
            <div class="s-field-content">
                <label class="mw-ui-check">
                    <input type="checkbox" id="text-mask-field"  onchange="mask(this.checked)">
                    <span></span><span></span>
                </label>
            </div>
        </div>

        <div class="s-field">
            <label><?php _e("Position"); ?></label>
            <div class="s-field-content">
                <div class="mw-field mw-field-flat" data-size="medium">
                    <select class="regular" data-prop="backgroundPosition">
                        <option value="0% 0%"><?php _e("Left Top"); ?></option>
                        <option value="50% 0%"><?php _e("Center Top"); ?></option>
                        <option value="100% 0%"><?php _e("Right Top"); ?></option>

                        <option value="0% 50%"><?php _e("Left Center"); ?></option>
                        <option value="50% 50%"><?php _e("Center Center"); ?></option>
                        <option value="100% 50%"><?php _e("Right Center"); ?></option>

                        <option value="0% 100%"><?php _e("Left Bottom"); ?></option>
                        <option value="50% 100%"><?php _e("Center Bottom"); ?></option>
                        <option value="100% 100%"><?php _e("Right Bottom"); ?></option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</mw-accordion-item>

        <mw-accordion-item>

            <?php $enabled_custom_fonts = get_option("enabled_custom_fonts", "template");


            $enabled_custom_fonts_array = array();

            if (is_string($enabled_custom_fonts) and $enabled_custom_fonts) {
                $enabled_custom_fonts_array = explode(',', $enabled_custom_fonts);
            }

            ?>


            <div class="mw-ui-box-header mw-accordion-title"><?php _e("Typography"); ?></div>
            <div class="mw-accordion-content mw-ui-box-content css-gui-element-typography">

                <div class="s-field">
                    <label><?php _e("Font Family"); ?></label>
                    <div class="s-field-content">
                        <div class="mw-multiple-fields">
                            <div class="mw-field mw-field-flat" data-size="medium">
                                <select class="regular" data-prop="fontFamily">
                                    <option value="inherit">Default</option>

                                    <?php if($enabled_custom_fonts_array): ?>
                                    <?php foreach ($enabled_custom_fonts_array as $font): ?>
                                        <option value='<?php print $font; ?>'><?php print $font; ?></option>
                                    <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div



                <div class="s-field">
                    <label><?php _e("Text align"); ?></label>
                    <div class="s-field-content">
                        <div class="text-align">
                            <span class="ta-left" data-value="left"><span class="mdi mdi-format-align-left"></span></span>
                            <span class="ta-center" data-value="center"><span class="mdi mdi-format-align-center"></span></span>
                            <span class="ta-right" data-value="right"><span class="mdi mdi-format-align-right"></span></span>
                            <span class="ta-justify" data-value="justify"><span class="mdi mdi-format-align-justify"></span></span>
                        </div>
                    </div>
                </div>
                <div class="s-field">
                    <label><?php _e("Size"); ?></label>
                    <div class="s-field-content">
                        <div class="mw-multiple-fields">
                            <div class="mw-field mw-field-flat unit" data-prop="fontSize" data-size="medium"><input type="text"></div>
                        </div>
                    </div>
                </div>
                <div class="s-field">
                    <label><?php _e("Line height"); ?></label>
                    <div class="s-field-content">
                        <div class="mw-multiple-fields">
                            <div class="mw-field mw-field-flat unit" data-prop="lineHeight" data-size="medium"><input type="text"></div>
                        </div>
                    </div>
                </div>
                <div class="s-field">
                    <label><?php _e("Color"); ?></label>
                    <div class="s-field-content">
                        <div class="mw-multiple-fields">
                            <div class="mw-field mw-field-flat" data-size="medium">
                                <span class="mw-field-color-indicator"><span class="mw-field-color-indicator-display"></span></span>
                                <input type="text" class="colorField unit" data-prop="color">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="s-field">
                    <label><?php _e("Style"); ?></label>
                    <div class="s-field-content">
                        <div class="mw-multiple-fields">
                            <div class="mw-field mw-field-flat" data-size="medium">
                                <select class="regular" data-prop="fontStyle">
                                    <option value="normal"><?php _e("normal"); ?></option>
                                    <option value="italic"><?php _e("italic"); ?></option>
                                    <option value="oblique"><?php _e("oblique"); ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="s-field">
                    <label><?php _e("Weight"); ?></label>
                    <div class="s-field-content">
                        <div class="mw-multiple-fields">
                            <div class="mw-field mw-field-flat" data-size="medium">
                                <select class="regular" data-prop="fontWeight">
                                    <option value="normal"><?php _e("normal"); ?></option>
                                    <option value="bold"><?php _e("bold"); ?></option>
                                    <option value="bolder"><?php _e("bolder"); ?></option>
                                    <option value="lighter"><?php _e("lighter"); ?></option>
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
                    <label><?php _e("Text transform"); ?></label>
                    <div class="s-field-content">
                        <div class="mw-multiple-fields">
                            <div class="mw-field mw-field-flat" data-size="medium">
                                <select class="regular" data-prop="textTransform">
                                    <option value="none"><?php _e("none"); ?></option>
                                    <option value="capitalize"><?php _e("capitalize"); ?></option>
                                    <option value="uppercase"><?php _e("uppercase"); ?></option>
                                    <option value="lowercase"><?php _e("lowercase"); ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="s-field">
                    <label><?php _e("Word Spacing"); ?></label>
                    <div class="s-field-content">
                        <div class="mw-multiple-fields">
                            <div class="mw-field mw-field-flat unit" data-prop="wordSpacing" data-size="medium"><input type="text"></div>
                        </div>
                    </div>
                </div>
                <div class="s-field">
                    <label><?php _e("Letter Spacing"); ?></label>
                    <div class="s-field-content">
                        <div class="mw-multiple-fields">
                            <div class="mw-field mw-field-flat unit" data-prop="letterSpacing" data-size="medium"><input type="text"></div>
                        </div>
                    </div>
                </div>


            </div>
        </mw-accordion-item>

    <mw-accordion-item id="overlay-edit">
        <div class="mw-ui-box-header mw-accordion-title"><?php _e("Overlay"); ?></div>
        <div class="mw-accordion-content mw-ui-box-content">
            <div class="s-field">
                <label><?php _e("Color"); ?></label>
                <div class="s-field-content">
                    <div class="mw-field mw-field-flat" data-size="medium">
                        <span class="mw-field-color-indicator"><span class="mw-field-color-indicator-display"></span></span>
                        <input type="text" class="colorField unit" id="overlay-color" data-prop="overlay-color">
                    </div>
                </div>
            </div>
        </div>
    </mw-accordion-item>
    <mw-accordion-item id="columns-edit">

        <div class="mw-ui-box-header mw-accordion-title"><?php _e("Grid"); ?></div>
        <div class="mw-accordion-content mw-ui-box-content">

            <div class="s-field">

                <div class="s-field-content">
                    <div class="mw-field mw-field-flat" data-size="medium">
                        <label><?php _e('Desktop'); ?></label>
                        <i class=" mdi mdi-monitor"></i>
                        <select data-prop="col-desktop" class="regular">
                            <option value='' selected disabled><?php _e('Choose'); ?></option>
                            <?php foreach(template_field_size_options() as $optionKey=>$optionValue): ?>
                                <option value="<?php echo $optionKey; ?>"><?php echo $optionValue; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mw-field mw-field-flat" data-size="medium">
                        <label><?php _e('Tablet'); ?></label>
                        <i class=" mdi mdi-tablet"></i>
                        <select data-prop="col-tablet" class="regular">
                            <option value='' selected disabled><?php _e('Choose'); ?></option>
                            <?php foreach(template_field_size_options() as $optionKey=>$optionValue): ?>
                                <option value="<?php echo $optionKey; ?>"><?php echo $optionValue; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mw-field mw-field-flat" data-size="medium">
                        <label><?php _e('Mobile'); ?></label>
                        <i class=" mdi mdi-cellphone"></i>
                        <select data-prop="col-mobile" class="regular">
                            <option value='' selected disabled><?php _e('Choose'); ?></option>
                            <?php foreach(template_field_size_options() as $optionKey=>$optionValue): ?>
                                <option value="<?php echo $optionKey; ?>"><?php echo $optionValue; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                </div>
            </div>
            <div class="s-field" id="field-conatiner-type">
                <label><?php _e("Container type"); ?></label>
                <div class="s-field-content">
                    <label class="mw-ui-check"> <input type="radio" onchange="sccontainertype(this.value)" name="containertype" value="container"/> <span></span><span> Fixed </span> </label>
                    <label class="mw-ui-check"> <input type="radio" onchange="sccontainertype(this.value)" name="containertype" value="container-fluid"/> <span></span><span> Fluid </span> </label>

                </div>
            </div>
        </div>


    </mw-accordion-item>
    <mw-accordion-item  id="size-box" style="display: none">
        <div class="mw-ui-box-header mw-accordion-title"><?php _e("Size"); ?></div>
        <div class="mw-accordion-content mw-ui-box-content">
            <div class="mw-esr-col">
                <div class="mw-esc">
                    <label><?php _e("Width"); ?></label>
                    <div class="mw-multiple-fields">
                        <div
                            class="mw-field mw-field-flat unit"
                            data-prop="width"
                            data-size="medium"
                            >
                            <input type="text" data-options="min: 50, max: 2000">
                        </div>
                        <span class="mw-ui-btn mw-ui-btn-medium" onclick="output('width', 'auto')">Auto</span>
                    </div>
                </div>
                <div class="mw-esc">
                    <label><?php _e("Height"); ?></label>
                    <div class="mw-multiple-fields">
                        <div class="mw-field mw-field-flat unit" data-prop="height" data-size="medium">
                            <input type="text" data-options="min: 50, max: 2000">

                        </div>
                        <span class="mw-ui-btn mw-ui-btn-medium" onclick="output('height', 'auto')">Auto</span>

                    </div>
                </div>
            </div>
            <div class="size-advanced" style="display: none;">
                <div class="mw-esr-col">
                    <div class="mw-esc">
                        <label><?php _e("Min Width"); ?></label>
                        <div class="mw-multiple-fields">
                            <div class="mw-field mw-field-flat unit" data-prop="minWidth" data-size="medium"><input type="text" data-options="min: 50, max: 2000"></div>
                            <span class="mw-ui-btn mw-ui-btn-medium" onclick="output('minWidth', '0')">None</span>

                        </div>
                    </div>
                    <div class="mw-esc">
                        <label><?php _e("Min Height"); ?></label>
                        <div class="mw-multiple-fields">
                            <div class="mw-field mw-field-flat unit" data-prop="minHeight" data-size="medium"><input type="text" data-options="min: 50, max: 2000"></div>
                            <span class="mw-ui-btn mw-ui-btn-medium" onclick="output('minHeight', '0')">None</span>
                        </div>
                    </div>

                </div>
                <div class="mw-esr-col">
                    <div class="mw-esc">
                        <label><?php _e("Max Width"); ?></label>
                        <div class="mw-multiple-fields">
                            <div class="mw-field mw-field-flat unit" data-prop="maxWidth" data-size="medium"><input type="text" data-options="min: 50, max: 2000"></div>
                            <span class="mw-ui-btn mw-ui-btn-medium" onclick="output('maxWidth', 'none')">None</span>
                        </div>

                    </div>
                    <div class="mw-esc">
                        <label><?php _e("Max Height"); ?></label>
                        <div class="mw-multiple-fields">
                            <div class="mw-field mw-field-flat unit" data-prop="maxHeight" data-size="medium"><input type="text"></div>
                            <span class="mw-ui-btn mw-ui-btn-medium" onclick="output('maxHeight', 'none')">None</span>
                        </div>
                    </div>
                </div>
            </div>
            <span class="mw-ui-link" onclick="mw.$('.size-advanced').slideToggle()">Advanced</span>
        </div>
    </mw-accordion-item>

    <mw-accordion-item >
        <div class="mw-ui-box-header mw-accordion-title"><?php _e("Spacing"); ?></div>
        <div class="mw-accordion-content mw-ui-box-content">
            <div class="mw-element-spacing-editor">
                <span class="mw-ese-label"><?php _e("Margin"); ?></span>
                <div class="mw-ese-holder mw-ese-margin">
                    <input type="number" class="mw-ese-top margin-top">
                    <input type="number" class="mw-ese-right margin-right">
                    <input type="number" class="mw-ese-bottom margin-bottom">
                    <input type="number" class="mw-ese-left margin-left">
                    <div class="mw-ese-holder mw-ese-padding">
                        <input type="number" class="mw-ese-top padding-top">
                        <input type="number" class="mw-ese-right padding-right">
                        <input type="number" class="mw-ese-bottom padding-bottom">
                        <input type="number" class="mw-ese-left padding-left">
                        <span class="mw-ese-label"><?php _e("Padding"); ?></span>
                    </div>
                </div>

            </div>
        </div>
    </mw-accordion-item>


<mw-accordion-item  >
    <div class="mw-ui-box-header mw-accordion-title"><?php _e("Border"); ?></div>
    <div class="mw-accordion-content mw-ui-box-content">
        <div class="s-field">
            <label><?php _e("Position"); ?></label>
            <div class="s-field-content">
                <div class="mw-field mw-field-flat" data-size="medium">
                    <select type="text" id="border-position">
                        <option value="all" selected><?php _e("All"); ?></option>
                        <option value="Top"><?php _e("Top"); ?></option>
                        <option value="Right"><?php _e("Right"); ?></option>
                        <option value="Bottom"><?php _e("Bottom"); ?></option>
                        <option value="Left"><?php _e("Left"); ?></option>
                    </select>
                </div>
            </div>
        </div>
        <div class="s-field">
            <label><?php _e("Size"); ?></label>
            <div class="s-field-content">
                <div class="mw-multiple-fields">
                    <div class="mw-field mw-field-flat" data-size="medium"><input type="text" id="border-size"></div>
                </div>
            </div>
        </div>
        <div class="s-field">
            <label><?php _e("Color"); ?></label>
            <div class="s-field-content">
                <div class="mw-field mw-field-flat" data-size="medium">
                    <span class="mw-field-color-indicator"><span class="mw-field-color-indicator-display"></span></span>
                    <input type="text" placeholder="#ffffff" class="colorField unit" data-position="top-right" id="border-color">
                </div>

            </div>
        </div>
        <div class="s-field">
            <label>Type</label>
            <div class="s-field-content">
                <div class="mw-field mw-field-flat" data-size="medium">
                    <select type="text" id="border-type">
                        <option value="" disabled selected><?php _e("Choose"); ?></option>
                        <option value="none"><?php _e("none"); ?></option>
                        <option value="solid"><?php _e("solid"); ?></option>
                        <option value="dotted"><?php _e("dotted"); ?></option>
                        <option value="dashed"><?php _e("dashed"); ?></option>
                        <option value="double"><?php _e("double"); ?></option>
                        <option value="groove"><?php _e("groove"); ?></option>
                        <option value="ridge"><?php _e("ridge"); ?></option>
                        <option value="inset"><?php _e("inset"); ?></option>
                        <option value="outset"><?php _e("outset"); ?></option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</mw-accordion-item>
<mw-accordion-item  >
    <div class="mw-ui-box-header mw-accordion-title"><?php _e("Miscellaneous"); ?></div>
    <div class="mw-accordion-content mw-ui-box-content">
        <div class="rouded-corners" >
            <label><?php _e("Rounded Corners"); ?></label>
            <div class="s-field-content">
                <div class="mw-field mw-field-flat" data-size="medium">
                    <div class="mw-multiple-fields">
                        <div class="mw-field mw-field-flat" data-size="medium">
                            <input type="text" class="regular order-1" data-prop="borderTopLeftRadius">
                            <span class="mw-field mw-field-flat-prepend order-2"><i class="angle angle-top-left"></i></span>
                        </div>
                        <div class="mw-field mw-field-flat" data-size="medium">
                            <span class="mw-field mw-field-flat-prepend"><i class="angle angle-top-right"></i></span>
                            <input class="regular" type="text" data-prop="borderTopRightRadius">
                        </div>
                    </div>
                </div>
                <div class="mw-field mw-field-flat" data-size="medium">
                    <div class="mw-multiple-fields">
                        <div class="mw-field mw-field-flat" data-size="medium">
                            <input class="regular order-1" type="text" data-prop="borderBottomLeftRadius">
                            <span class="mw-field mw-field-flat-prepend order-2"><i class="angle angle-bottom-left"></i></span>
                        </div>
                        <div class="mw-field mw-field-flat" data-size="medium">
                            <span class="mw-field mw-field-flat-prepend"><i class="angle angle-bottom-right"></i></span>
                            <input class="regular" type="text" data-prop="borderBottomRightRadius">
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
</mw-accordion-item>
        <mw-accordion-item id="classtags-accordion">

            <div class="mw-ui-box-header mw-accordion-title"><?php _e("Attributes"); ?></div>
            <div class="mw-accordion-content mw-ui-box-content">
                <div class="mw-ui-field-holder">
                    <label class="mw-ui-label"><?php _e("Classes"); ?></label>
                    <div class="mw-ui-field w100" id="classtags"></div>
                </div>

            </div>

        </mw-accordion-item>
</div>

<div class="mw-css-editor">

</div>
</div>
