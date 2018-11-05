mw.propEditor = {
    addInterface:function(name, func){
        this.interfaces[name] = this.interfaces[name] || func;
    },
    helpers:{
        wrapper:function(){
            var el = document.createElement('div');
            el.className = 'prop-ui-field-holder';
            return el;
        },
        quatroWrapper:function(){
            var el = document.createElement('div');
            el.className = 'prop-ui-field-quatro';
            return el;
        },
        label:function(content){
            var el = document.createElement('label');
            el.className = 'prop-ui-label';
            el.innerHTML = content
            return el;
        },
        button:function(content){
            var el = document.createElement('button');
            el.className = 'mw-ui-btn';
            el.innerHTML = content
            return el;
        },
        field: function(val, type, options){
            type = type || 'text';
            if(type == 'select'){
                var el = document.createElement('select');
                if(options && options.length){
                    var option = document.createElement('option');
                        option.innerHTML = 'Choose...';
                        option.value = '';
                        el.appendChild(option);
                    for(var i=0;i<options.length;i++){
                        var option = document.createElement('option');
                        option.innerHTML = options[i];
                        option.value = options[i];
                        el.appendChild(option);
                    }
                }
            }
            else{
                var el = document.createElement('input');
                el.type = type
            }

            el.className = 'prop-ui-field';
            el.value = val

            return el;
        },
        fieldPack:function(label, type){
            var field = mw.propEditor.helpers.field('', type);
            var holder = mw.propEditor.helpers.wrapper();
            var label = mw.propEditor.helpers.label(label);
            holder.appendChild(label)
            holder.appendChild(field);
            return{
                label:label,
                holder:holder,
                field:field
            }
        }
    },
    rend:function(element, rend){
        for(var i=0;i<rend.length;i++){
            element.appendChild(rend[i].node);
        }
    },
    schema:function(options){
        this.setSchema = function(schema){
            this.options.schema = schema;
            this._rend = [];
            this._valSchema = this._valSchema || {};
            for(var i =0; i< this.options.schema.length;i++){
                var item = this.options.schema[i];
                if(typeof this._valSchema[item.id] === 'undefined'){
                    this._rend.push(mw.propEditor.interfaces[item.interface](this, item));
                    if(item.id){
                        this._valSchema[item.id] = this._valSchema[item.id] || ''
                    }
                }
            }
            this.options.element.innerHTML = '';
            mw.propEditor.rend(this.options.element, this._rend)
        }
        this.updateSchema = function(schema){
            for(var i =0; i<schema.length;i++){
                var item = schema[i];
                if(typeof this._valSchema[item.id] === 'undefined'){
                    this.options.schema.push(item);
                    var create = mw.propEditor.interfaces[item.interface](this, item)
                    this._rend.push(create);
                    if(item.id){
                        this._valSchema[item.id] = this._valSchema[item.id] || ''
                    }
                    this.options.element.appendChild(create.node);
                }
            }
        }
        this.setValue = function(val){
            for(var i in val){
                var rend = this.getRendById(i);
                if(!!rend){
                    rend.setValue(val[i])
                }
            }
        }
        this.getValue = function(){
            return this._valSchema;
        }
        this.getRendById = function(id){
            for(var i in this._rend){
                if(this._rend[i].id == id){
                    return this._rend[i]
                }
            }
        }
        this.options = options;
        this.options.element = typeof this.options.element == 'string' ? document.querySelector(options.element) : this.options.element;

        this.setSchema(this.options.schema)

    },
    interfaces:{
        quatro:function(proto, config){
            //"2px 4px 8px 122px"
            var holder = mw.propEditor.helpers.quatroWrapper();

            for(var i = 0; i<4; i++){
                var item = mw.propEditor.helpers.fieldPack(config.label[i], 'number');
                holder.appendChild(item.holder);
                item.field.oninput = function(){
                    var final = '';
                    var all = holder.querySelectorAll('input'), i = 0;
                    for( ; i<all.length; i++){
                        var unit = all[i].dataset.unit || ''
                        final+= ' ' + all[i].value + unit ;
                    }
                    proto._valSchema[config.id] = final.trim();
                     $(proto).trigger('change', [config.id, final.trim()]);
                }
            }

            return {
                node:holder,
                setValue:function(value){
                    value = value.trim();
                    var arr = value.split(' ');
                    var all = holder.querySelectorAll('input'), i = 0;
                    for( ; i<all.length; i++){
                        all[i].value = parseInt(arr[i], 10);
                        var unit = arr[i].replace(/[0-9]/g, '');
                        all[i].dataset.unit = unit;
                    }
                    proto._valSchema[config.id] = value;
                },
                id:config.id
            };
        },
        hr:function(proto, config){
            var el = document.createElement('hr');
            el.className = ' ';
            return {
                node:el
            };
        },
        block:function(proto, config){
            var el = document.createElement('div');
            el.innerHTML = config.content;
            return {
                node:el
            };
        },
        size:function(proto, config){
            //"2px"
            var field = mw.propEditor.helpers.field('', 'number');
            var holder = mw.propEditor.helpers.wrapper();
            var label = mw.propEditor.helpers.label(config.label);
            holder.appendChild(label);
            holder.appendChild(field);
            field.oninput = function(){
                proto._valSchema[config.id] = this.value + this.dataset.unit;
                $(proto).trigger('change', [config.id, this.value + this.dataset.unit]);
            }
            return {
                node:holder,
                setValue:function(value){
                    field.value = parseInt(value, 10);
                    proto._valSchema[config.id] = value
                    var unit = value.replace(/[0-9]/g, '');
                    field.dataset.unit = unit;
                },
                id:config.id
            };
        },
        text:function(proto, config){
            var field = mw.propEditor.helpers.field('', 'text');
            var holder = mw.propEditor.helpers.wrapper();
            var label = mw.propEditor.helpers.label(config.label);
            holder.appendChild(label);
            holder.appendChild(field);
            field.oninput = function(){
                proto._valSchema[config.id] = this.value;
                $(proto).trigger('change', [config.id, this.value]);
            }
            return {
                node:holder,
                setValue:function(value){
                    field.value = value;
                    proto._valSchema[config.id] = value
                },
                id:config.id
            };
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
            }
            return {
                node:holder,
                setValue:function(value){
                    field.value = parseInt(value, 10);
                    proto._valSchema[config.id] = value
                },
                id:config.id
            };
        },
        color:function(proto, config){
            var field = mw.propEditor.helpers.field('', 'color');
            if(field.type != 'color'){
                mw.colorPicker({
                    element:field,
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
            return {
                node:holder,
                setValue:function(value){
                    field.value = value;
                    proto._valSchema[config.id] = value
                },
                id:config.id
            };
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
            }
            return {
                node:holder,
                setValue:function(value){
                    field.value = value;
                    proto._valSchema[config.id] = value
                },
                id:config.id
            };
        },
        file:function(proto, config){
            var btn = mw.propEditor.helpers.button('<span class="mw-icon-upload"></span>');
            var field = mw.propEditor.helpers.field('', 'text');
            var holder = mw.propEditor.helpers.wrapper();
            var label = mw.propEditor.helpers.label(config.label);
            holder.appendChild(label);
            holder.appendChild(field);
            holder.appendChild(btn);
            btn.onclick = function(){
                mw.fileWindow({
                    types:'images',
                    change:function(url){
                        field.value = url;
                        proto._valSchema[config.id] = this.value;
                        $(proto).trigger('change', [config.id, this.value]);
                    }
                });
            }
            field.oninput = function(){
                proto._valSchema[config.id] = this.value;
                $(proto).trigger('change', [config.id, this.value]);
            };
            return {
                node:holder,
                setValue:function(value){
                    field.value = value;
                    proto._valSchema[config.id] = value
                },
                id:config.id
            };
        },
        icon:function(proto, config){
            mw.iconSelector.iconDropdown("#icon-picker-<?php print $slide['id'] ?>", {
                onchange: function (iconClass) {
                    $('.tab-icon').val(iconClass).trigger('change')
                },
                mode: 'absolute',
                value: '<?php print $slide['icon']; ?>'
            });
            return {
                node:holder,
                setValue:function(value){
                    field.value = value;
                    proto._valSchema[config.id] = value
                },
                id:config.id
            };
        }
    }
};
