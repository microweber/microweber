mw.require('editor.js')
mw.propEditor = {
    addInterface:function(name, func){
        this.interfaces[name] = this.interfaces[name] || func;
    },
    getRootElement: function(node){
        if(node.nodeName !== 'IFRAME') return node;
        return $(node).contents().find('body')[0];
    },
    helpers:{
        wrapper:function(){
            var el = document.createElement('div');
            el.className = 'mw-ui-field-holder prop-ui-field-holder';
            return el;
        },
        buttonNav:function(){
            var el = document.createElement('div');
            el.className = 'mw-ui-btn-nav prop-ui-field-holder';
            return el;
        },
        quatroWrapper:function(cls){
            var el = document.createElement('div');
            el.className = cls || 'prop-ui-field-quatro';
            return el;
        },
        label:function(content){
            var el = document.createElement('label');
            el.className = 'control-label d-block prop-ui-label';
            el.innerHTML = content;
            return el;
        },
        button:function(content){
            var el = document.createElement('button');
            el.className = 'mw-ui-btn';
            el.innerHTML = content;
            return el;
        },
        field: function(val, type, options){
            type = type || 'text';
            var el;
            if(type === 'select'){
                el = document.createElement('select');
                if(options && options.length){
                    var option = document.createElement('option');
                        option.innerHTML = 'Choose...';
                        option.value = '';
                        el.appendChild(option);
                    for(var i=0;i<options.length;i++){
                        var opt = document.createElement('option');
                        if(typeof options[i] === 'string' || typeof options[i] === 'number'){
                            opt.innerHTML = options[i];
                            opt.value = options[i];
                        }
                        else{
                            opt.innerHTML = options[i].title;
                            opt.value = options[i].value;
                        }
                        el.appendChild(opt);
                    }
                }
            }
            else if(type === 'textarea'){
                el = document.createElement('textarea');
            } else{
                el = document.createElement('input');
                try { // IE11 throws error on html5 types
                    el.type = type;
                } catch (err) {
                    el.type = 'text';
                }

            }

            el.className = 'form-control prop-ui-field';
            el.value = val;

            return el;
        },
        fieldPack:function(label, type){
            var field = mw.propEditor.helpers.field('', type);
            var holder = mw.propEditor.helpers.wrapper();
            label = mw.propEditor.helpers.label(label);
            holder.appendChild(label);
            holder.appendChild(field);
            return{
                label:label,
                holder:holder,
                field:field
            }
        }
    },
    rend:function(element, rend){

        element = mw.propEditor.getRootElement(element);
        for(var i=0;i<rend.length;i++){
            element.appendChild(rend[i].node);
        }
    },
    schema:function(options){
        this._after = [];
        this.setSchema = function(schema){
            this.options.schema = schema;
            this._rend = [];
            this._valSchema = this._valSchema || {};
            for(var i =0; i< this.options.schema.length; i++){
                var item = this.options.schema[i];
                if(typeof this._valSchema[item.id] === 'undefined' && this._cache.indexOf(item) === -1){
                    this._cache.push(item)
                    var curr = new mw.propEditor.interfaces[item.interface](this, item);

                    this._rend.push(curr);
                    if(item.id){
                        curr.name = item.id;
                        var field =  curr.node.querySelector('input,select,textarea');
                        if(field) {
                            field.name = curr.name;
                        }

                        this._valSchema[item.id] = this._valSchema[item.id] || '';
                    }
                }
            }
            $(this.rootHolder).html(' ').addClass('mw-prop-editor-root');
            mw.propEditor.rend(this.rootHolder, this._rend);
        };
        this.updateSchema = function(schema){
            var final = [];
            for(var i =0; i<schema.length;i++){
                var item = schema[i];

                if(typeof this._valSchema[item.id] === 'undefined' && this._cache.indexOf(item) === -1){
                    this.options.schema.push(item);
                    this._cache.push(item)
                    var create = new mw.propEditor.interfaces[item.interface](this, item);
                    this._rend.push(create);
                    final.push(create);
                    if(item.id){
                        this._valSchema[item.id] = this._valSchema[item.id] || '';
                    }
                    //this.rootHolder.appendChild(create.node);
                }
            }
            return final;
        };
        this.setValue = function(val){
            if(!val){
                return;
            }
            for(var i in val){
                var rend = this.getRendById(i);
                if(!!rend){
                    rend.setValue(val[i]);
                }
            }
        };
        this.getValue = function(){
            return this._valSchema;
        };
        this.disable = function(){
            this.disabled = true;
            $(this.rootHolder).addClass('disabled');
        };
        this.enable = function(){
            this.disabled = false;
            $(this.rootHolder).removeClass('disabled');
        };
        this.getRendById = function(id) {
            for(var i in this._rend) {
                if(this._rend[i].id === id) {
                    return this._rend[i];
                }
            }
        };
        this._cache = [];
        this.options = options;
        this.options.element = typeof this.options.element === 'string' ? document.querySelector(options.element) : this.options.element;
        this.rootHolder = mw.propEditor.getRootElement(this.options.element);
        this.setSchema(this.options.schema);

        this._after.forEach(function (value) {
            value.items.forEach(function (item) {
                value.node.appendChild(item.node);
            });
        });

        mw.trigger('ComponentsLaunch');
    },

    interfaces:{
        quatro:function(proto, config){
            //"2px 4px 8px 122px"
            var holder = mw.propEditor.helpers.quatroWrapper('mw-css-editor-group');

            for(var i = 0; i<4; i++){
                var item = mw.propEditor.helpers.fieldPack(config.label[i], 'size');
                holder.appendChild(item.holder);
                item.field.oninput = function(){
                    var final = '';
                    var all = holder.querySelectorAll('input'), i = 0;
                    for( ; i<all.length; i++){
                        var unit = all[i].dataset.unit || '';
                        final+= ' ' + all[i].value + unit ;
                    }
                    proto._valSchema[config.id] = final.trim();
                     $(proto).trigger('change', [config.id, final.trim()]);
                };
            }
            this.node = holder;
            this.setValue = function(value){
                value = value.trim();
                var arr = value.split(' ');
                var all = holder.querySelectorAll('input'), i = 0;
                for( ; i<all.length; i++){
                    all[i].value = parseInt(arr[i], 10);
                    if(typeof arr[i] === 'undefined'){
                        arr[i] = '';
                    }
                    var unit = arr[i].replace(/[0-9]/g, '');
                    all[i].dataset.unit = unit;
                }
                proto._valSchema[config.id] = value;
            };
            this.id = config.id;
        },
        hr:function(proto, config){
            var el = document.createElement('hr');
            el.className = ' ';
            this.node = el;
        },
        block: function(proto, config){
            var node = document.createElement('div');
            if(typeof config.content === 'string') {
                node.innerHTML = config.content;
            } else {
                var newItems = proto.updateSchema(config.content);
                proto._after.push({node: node, items: newItems});
            }
            if(config.class){
                node.className = config.class;
            }
            this.node = node;
        },
        size:function(proto, config){
            var field = mw.propEditor.helpers.field('', 'text');
            this.field = field;
            config.autocomplete = config.autocomplete || ['auto'];

            var holder = mw.propEditor.helpers.wrapper();
            var buttonNav = mw.propEditor.helpers.buttonNav();
            var label = mw.propEditor.helpers.label(config.label);
            var scope = this;
            var dtlist = document.createElement('datalist');
            dtlist.id = 'mw-datalist-' + mw.random();
            config.autocomplete.forEach(function (value) {
                var option = document.createElement('option');
                option.value = value;
                dtlist.appendChild(option)
            });

            this.field.setAttribute('list', dtlist.id);
            document.body.appendChild(dtlist);

            this._makeVal = function(){
                if(field.value === 'auto'){
                    return 'auto';
                }
                return field.value + field.dataset.unit;
            };

            var unitSelector = mw.propEditor.helpers.field('', 'select', [
                'px', '%', 'rem', 'em', 'vh', 'vw', 'ex', 'cm', 'mm', 'in', 'pt', 'pc', 'ch'
            ]);
            this.unitSelector = unitSelector;
            $(holder).addClass('prop-ui-field-holder-size');
            $(unitSelector)
                .val('px')
                .addClass('prop-ui-field-unit');
            unitSelector.onchange = function(){
                field.dataset.unit = $(this).val() || 'px';

                $(proto).trigger('change', [config.id, scope._makeVal()]);
            };

            $(unitSelector).on('change', function(){

            });

            holder.appendChild(label);
            buttonNav.appendChild(field);
            buttonNav.appendChild(unitSelector);
            holder.appendChild(buttonNav);

            field.oninput = function(){

                proto._valSchema[config.id] = this.value + this.dataset.unit;
                $(proto).trigger('change', [config.id, scope._makeVal()]);
            };

            this.node = holder;
            this.setValue = function(value){
                var an = parseInt(value, 10);
                field.value = isNaN(an) ? value : an;
                proto._valSchema[config.id] = value;
                var unit = value.replace(/[0-9]/g, '').replace(/\./g, '');
                field.dataset.unit = unit;
                $(unitSelector).val(unit);
            };
            this.id = config.id;

        },
        text: function(proto, config){
            var val = '';
            if(config.value){
                if(typeof config.value === 'function'){
                    val = config.value();
                } else {
                    val = config.value;
                }
            }
            var field = mw.propEditor.helpers.field(val, 'text');
            var holder = mw.propEditor.helpers.wrapper();
            var label = mw.propEditor.helpers.label(config.label);
            holder.appendChild(label);
            holder.appendChild(field);
            field.oninput = function(){
                proto._valSchema[config.id] = this.value;
                $(proto).trigger('change', [config.id, this.value]);
            };
            this.node = holder;
            this.setValue = function(value){
                field.value = value;
                proto._valSchema[config.id] = value;
            };
            this.id = config.id;
        },
        textArea:function(proto, config){
            var val = '';
            if(config.value){
                if(typeof config.value === 'function'){
                    val = config.value();
                } else {
                    val = config.value;
                }
            }
            var field = mw.propEditor.helpers.field(val, 'textarea');
            var holder = mw.propEditor.helpers.wrapper();
            var label = mw.propEditor.helpers.label(config.label);
            holder.appendChild(label);
            holder.appendChild(field);
            field.oninput = function(){
                proto._valSchema[config.id] = this.value;
                $(proto).trigger('change', [config.id, this.value]);
            };
            this.node = holder;
            this.setValue = function(value){
                field.value = value;
                proto._valSchema[config.id] = value;
            };
            this.id = config.id;
        },
        hidden:function(proto, config){
            var val = '';
            if(config.value){
                if(typeof config.value === 'function'){
                    val = config.value();
                } else {
                    val = config.value;
                }
            }

            var field = mw.propEditor.helpers.field(val, 'hidden');
            var holder = mw.propEditor.helpers.wrapper();
            var label = mw.propEditor.helpers.label(config.label);
            holder.appendChild(label);
            holder.appendChild(field);
            field.oninput = function(){
                proto._valSchema[config.id] = this.value;
                $(proto).trigger('change', [config.id, this.value]);
            };
            this.node = holder;
            this.setValue = function(value){
                field.value = value;
                proto._valSchema[config.id] = value;
            };
            this.id = config.id;
        },
        shadow: function(proto, config){
            var scope = this;

            this.fields = {
                position : mw.propEditor.helpers.field('', 'select', [{title:'Outside', value: ''}, {title:'Inside', value: 'inset'}]),
                x : mw.propEditor.helpers.field('', 'number'),
                y : mw.propEditor.helpers.field('', 'number'),
                blur : mw.propEditor.helpers.field('', 'number'),
                spread : mw.propEditor.helpers.field('', 'number'),
                color : mw.propEditor.helpers.field('', 'text')
            };

            this.fields.position.placeholder = 'Position';
            this.fields.x.placeholder = 'X offset';
            this.fields.y.placeholder = 'Y offset';
            this.fields.blur.placeholder = 'Blur';
            this.fields.spread.placeholder = 'Spread';
            this.fields.color.placeholder = 'Color';
            this.fields.color.dataset.options = 'position: ' + (config.pickerPosition || 'bottom-center');
            //$(this.fields.color).addClass('mw-color-picker');
            mw.colorPicker({
                element:this.fields.color,
                position:'top-left',
                onchange:function(color){
                    $(scope.fields.color).trigger('change', color)
                    scope.fields.color.style.backgroundColor = color;
                    scope.fields.color.style.color = mw.color.isDark(color) ? 'white' : 'black';
                }
            });

            var labelPosition = mw.propEditor.helpers.label('Position');
            var labelX = mw.propEditor.helpers.label('X offset');
            var labelY = mw.propEditor.helpers.label('Y offset');
            var labelBlur = mw.propEditor.helpers.label('Blur');
            var labelSpread = mw.propEditor.helpers.label('Spread');
            var labelColor = mw.propEditor.helpers.label('Color');

            var wrapPosition = mw.propEditor.helpers.wrapper();
            var wrapX = mw.propEditor.helpers.wrapper();
            var wrapY = mw.propEditor.helpers.wrapper();
            var wrapBlur = mw.propEditor.helpers.wrapper();
            var wrapSpread = mw.propEditor.helpers.wrapper();
            var wrapColor = mw.propEditor.helpers.wrapper();



            this.$fields = $();

            $.each(this.fields, function(){
                scope.$fields.push(this);
            });

            $(this.$fields).on('input change', function(){
                var val = ($(scope.fields.position).val() || '')
                    + ' ' + (scope.fields.x.value || 0) + 'px'
                    + ' ' + (scope.fields.y.value || 0) + 'px'
                    + ' ' + (scope.fields.blur.value || 0) + 'px'
                    + ' ' + (scope.fields.spread.value || 0) + 'px'
                    + ' ' + (scope.fields.color.value || 'rgba(0,0,0,.5)');
                proto._valSchema[config.id] = val;
                $(proto).trigger('change', [config.id, val]);
            });


            var holder = mw.propEditor.helpers.wrapper();

            var label = mw.propEditor.helpers.label(config.label ? config.label : '');
            if(config.label){
                holder.appendChild(label);
            }
            var row1 = mw.propEditor.helpers.wrapper();
            var row2 = mw.propEditor.helpers.wrapper();
            row1.className = 'mw-css-editor-group';
            row2.className = 'mw-css-editor-group';


            wrapPosition.appendChild(labelPosition);
            wrapPosition.appendChild(this.fields.position);
            row1.appendChild(wrapPosition);

            wrapX.appendChild(labelX);
            wrapX.appendChild(this.fields.x);
            row1.appendChild(wrapX);


            wrapY.appendChild(labelY);
            wrapY.appendChild(this.fields.y);
            row1.appendChild(wrapY);

            wrapColor.appendChild(labelColor);
            wrapColor.appendChild(this.fields.color);
            row2.appendChild(wrapColor);

            wrapBlur.appendChild(labelBlur);
            wrapBlur.appendChild(this.fields.blur);
            row2.appendChild(wrapBlur);

            wrapSpread.appendChild(labelSpread);
            wrapSpread.appendChild(this.fields.spread);
            row2.appendChild(wrapSpread);

            holder.appendChild(row1);
            holder.appendChild(row2);

            $(this.fields).each(function () {
                $(this).on('input change', function(){
                    proto._valSchema[config.id] = this.value;
                    $(proto).trigger('change', [config.id, this.value]);
                });
            });


            this.node = holder;
            this.setValue = function(value){
                var parse = this.parseShadow(value);
                $.each(parse, function (key, val) {
                    scope.fields[key].value = this;
                });
                proto._valSchema[config.id] = value;
            };
            this.parseShadow = function(shadow){
                var inset = false;
                if(shadow.indexOf('inset') !== -1){
                    inset = true;
                }
                var arr = shadow.replace('inset', '').trim().replace(/\s{2,}/g, ' ').split(' ');
                var sh = {
                    position: inset ? 'in' : 'out',
                    x:0,
                    y: 0,
                    blur: 0,
                    spread: 0,
                    color: 'transparent'
                };
                if(!arr[2]){
                    return sh;
                }
                sh.x = arr[0];
                sh.y = arr[1];
                sh.blur = (!isNaN(parseInt(arr[2], 10))?arr[2]:'0px');
                sh.spread = (!isNaN(parseInt(arr[3], 10))?arr[3]:'0px');
                sh.color = isNaN(parseInt(arr[arr.length-1])) ? arr[arr.length-1] : 'transparent';
                return sh;
            };
            this.id = config.id;
        },
        number:function(proto, config){
            var field = mw.propEditor.helpers.field('', 'number');
            var holder = mw.propEditor.helpers.wrapper();
            var label = mw.propEditor.helpers.label(config.label);
            holder.appendChild(label);
            holder.appendChild(field);
            field.oninput = function(){
                proto._valSchema[config.id] = this.value;
                $(proto).trigger('change', [config.id, this.value]);
            };
            this.node = holder;
            this.setValue=function(value){
                field.value = parseInt(value, 10);
                proto._valSchema[config.id] = value;
            };
            this.id = config.id;
        },
        color:function(proto, config){
            var field = mw.propEditor.helpers.field('', 'text');
            if(field.type !== 'color'){
                mw.colorPicker({
                    element:field,
                    position: config.position || 'bottom-center',
                    onchange:function(){
                        $(proto).trigger('change', [config.id, field.value]);
                    }
                });
            }
            var holder = mw.propEditor.helpers.wrapper();
            var label = mw.propEditor.helpers.label(config.label);
            holder.appendChild(label);
            holder.appendChild(field);
            field.oninput = function(){
                proto._valSchema[config.id] = this.value;
                $(proto).trigger('change', [config.id, this.value]);
            }
            this.node = holder;
            this.setValue = function(value){
                field.value = value;
                proto._valSchema[config.id] = value
            };
            this.id = config.id
        },
        select:function(proto, config){
            var field = mw.propEditor.helpers.field('', 'select', config.options);
            var holder = mw.propEditor.helpers.wrapper();
            var label = mw.propEditor.helpers.label(config.label);
            holder.appendChild(label);
            holder.appendChild(field);
            field.onchange = function(){
                proto._valSchema[config.id] = this.value;
                $(proto).trigger('change', [config.id, this.value]);
            };
            this.node = holder;
            this.setValue = function(value){
                field.value = value;
                proto._valSchema[config.id] = value
            };
            this.id = config.id;
        },
        file: function(proto, config){
            if(config.multiple === true){
                config.multiple = 99;
            }
            if(!config.multiple){
                config.multiple = 1;
            }
            var scope = this;
            var createButton = function(imageUrl, i, proto){
                imageUrl = imageUrl || '';
                var el = document.createElement('div');
                el.className = 'upload-button-prop mw-ui-box mw-ui-box-content';
                var btn =  document.createElement('span');
                btn.className = ('mw-ui-btn');
                btn.innerHTML = ('<span class="mw-icon-upload"></span>');
                btn.style.backgroundSize = 'cover';
                btn.style.backgroundColor = 'transparent';
                el.style.backgroundSize = 'cover';
                btn._value = imageUrl;
                btn._index = i;
                if(imageUrl){
                    el.style.backgroundImage = 'url(' + imageUrl + ')';
                }
                btn.onclick = function(){
                    var dialog;
                    var picker = new (mw.top()).filePicker({
                        type: 'images',
                        label: false,
                        autoSelect: false,
                        footer: true,
                        _frameMaxHeight: true,

                        onResult: function (res) {
                            var url = res.src ? res.src : res;
                            url = url.toString();
                            proto._valSchema[config.id] = proto._valSchema[config.id] || [];
                            proto._valSchema[config.id][btn._index] = url;
                            el.style.backgroundImage = 'url(' + url + ')';
                            btn._value = url;
                            scope.refactor();
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


                };

                if(config.multiple === true || (typeof config.multiple === 'number' && config.multiple > 1) ) {
                    var close = document.createElement('span');
                    close.className = 'mw-badge mw-badge-important';
                    close.innerHTML = '<span class="mw-icon-close"></span>';

                    close.onclick = function(e){
                        scope.remove(el);
                        e.preventDefault();
                        e.stopPropagation();
                    };
                    el.appendChild(close);
                }


                el.appendChild(btn);
                return el;
            };

            this.remove = function (i) {
                if(typeof i === 'number'){
                    $('.upload-button-prop', el).eq(i).remove();
                }
                else{
                    $(i).remove();
                }
                scope.refactor();
            };

            this.hasMultiple = function () {
                return config.multiple && config.multiple > 1;
            }

            this.addImageButton = function(){
                if(this.hasMultiple()){
                    this.addBtn = document.createElement('div');
                    this.addBtn.className = 'mw-ui-link';
                    //this.addBtn.innerHTML = '<span class="mw-icon-plus"></span>';
                    this.addBtn.innerHTML = mw.msg.addImage;
                    this.addBtn.onclick = function(){
                        el.appendChild(createButton(undefined, 0, proto));
                        scope.manageAddImageButton();
                    };
                    holder.appendChild(this.addBtn);
                }
            };

            this.manageAddImageButton = function(){
                if(this.hasMultiple()) {
                    var isVisible = $('.upload-button-prop', this.node).length < config.multiple;
                    this.addBtn.style.display = isVisible ? 'inline-block' : 'none';
                }

            };

            var btn = createButton(undefined, 0, proto);
            var holder = mw.propEditor.helpers.wrapper();
            var label = mw.propEditor.helpers.label(config.label);
            holder.appendChild(label);
            var el = document.createElement('div');
            el.className = 'mw-ui-box-content';
            el.appendChild(btn);
            holder.appendChild(el);

            this.addImageButton();
            this.manageAddImageButton();

            if($.fn.sortable && this.hasMultiple()){
                $(el).sortable({
                    update:function(){
                        scope.refactor();
                    }
                });
            }



            this.refactor = function () {
                var val = [];
                $('.mw-ui-btn', el).each(function(){
                    val.push(this._value);
                });
                this.manageAddImageButton();
                if(val.length === 0){
                    val = [''];
                }
                proto._valSchema[config.id] = val;
                $(proto).trigger('change', [config.id, proto._valSchema[config.id]]);
            };

            this.node = holder;
            this.setValue = function(value){
                value = value || [''];
                proto._valSchema[config.id] = value;
                $('.upload-button-prop', holder).remove();
                if(typeof value === 'string'){
                    el.appendChild(createButton(value, 0, proto));
                }
                else{
                    $.each(value, function (index) {
                        el.appendChild(createButton(this, index, proto));
                    });
                }

                this.manageAddImageButton();
            };
            this.id = config.id;
        },
        icon: function(proto, config){
            var holder = mw.propEditor.helpers.wrapper();

            var el = document.createElement('span');
            el.className = "btn btn-outline-primary";
            el.innerHTML = "Icon";
            var elTarget = document.createElement('i');

            el.onclick = function () {
                picker.dialog();
            };

            var removeEl = document.createElement('span');
            removeEl.className = "btn btn-outline-danger tip";
            removeEl.dataset.tip = "Remove icon";
            removeEl.innerHTML = "<span class='fa fa-trash'></span>";
            removeEl.style.marginInlineStart = "10px";

            removeEl.onclick = function () {
                proto._valSchema[config.id] = '';
                $(proto).trigger('change', [config.id, '']);
                $(el.firstElementChild).hide();
            };

            $(el).prepend(elTarget);
            $(holder).prepend(removeEl);
            $(holder).prepend(el);
            mw.iconLoader().init();
            var picker = mw.iconPicker({iconOptions: false});
            picker.target = elTarget;
            picker.on('select', function (data) {
                $(el.firstElementChild).show();
                data.render();
                proto._valSchema[config.id] = picker.target.outerHTML;
                $(proto).trigger('change', [config.id, picker.target.outerHTML]);
                picker.dialog('hide');
             });

            var label = mw.propEditor.helpers.label(config.label);

            $(holder).prepend(label);

            this.node = holder;
            this.setValue = function(value){

                if(picker && picker.value) {
                    picker.value(value);
                }
                if(value) {
                    $(elTarget).replaceWith(value);
                } else {
                    $(el.firstElementChild).hide();
                }
                picker.target = el.firstElementChild;
                proto._valSchema[config.id] = value;
            };
            this.id = config.id;

        },
        richtext:function(proto, config){
            var field = mw.propEditor.helpers.field('', 'textarea');
            var holder = mw.propEditor.helpers.wrapper();
            var label = mw.propEditor.helpers.label(config.label);
            holder.appendChild(label);
            holder.appendChild(field);
            $(field).on('change', function(){
                proto._valSchema[config.id] = this.value;
                $(proto).trigger('change', [config.id, this.value]);
            });
            this.node = holder;
            this.setValue = function(value){
                field.value = value;
                this.editor.setContent(value, true);
                proto._valSchema[config.id] = value;
            };
            this.id = config.id;
            var defaults = {
                height: 120,
                mode: 'div',
                smallEditor: false,
                controls: [
                    [
                        'bold', 'italic',
                        {
                            group: {
                                icon: 'mdi xmdi-format-bold',
                                controls: ['underline', 'strikeThrough', 'removeFormat']
                            }
                        },

                        '|', 'align', '|', 'textColor', 'textBackgroundColor', '|', 'link', 'unlink'
                    ],
                ]
            };
            config.options = config.options || {};
            this.editor = mw.Editor($.extend({}, defaults, config.options, {selector: field}));
        }
    }
};
