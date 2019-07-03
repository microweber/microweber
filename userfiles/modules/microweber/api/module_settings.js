mw.moduleSettings = function(options){
    /*
        options:

            data: [Object],
            element: NodeElement || selector string || jQuery array,
            schema: mw.propEditor.schema,
            key: String
            group: String,
            autoSave: Boolean

    */

    var defaults = {
        sortable: true,
        autoSave: true
    };

    if(!options.schema || !options.data || !options.element) return;

    this.options = $.extend({}, defaults, options);

    this.options.element = $(this.options.element)[0];
    this.value = this.options.data.slice();

    var scope = this;

    this.items = [];

    if(!this.options.element) return;

    this.createItemHolderHeader = function(i){
        if(this.options.header){
            var header = document.createElement('div');
            header.className = "mw-ui-box-header";
            header.innerHTML = this.options.header.replace(/{count}/g, '<span class="mw-module-settings-box-index">'+(i+1)+'</span>');
            $(header).on('click', function(){
                $(this).next().slideToggle();
            });
            return header;

        }
    };
    this.headerAnalize = function(i, header){
        $("[data-action='remove']", header).on('click', function(){
            scope.remove(i);
        });
    };
    this.createItemHolder = function(){
        var holder = document.createElement('div');
        var holderin = document.createElement('div');
        holder.className = 'mw-ui-box mw-module-settings-box';
        holderin.className = 'mw-ui-box-content mw-module-settings-box-content';
        holderin.style.display = 'none';
        this.options.element.appendChild(holder);
        holder.appendChild(holderin);
        return holder;
    };

    this.addNew = function(method){
        method = method || 'new';
        var pos = this.value.length;
        var _new;
        _new = mw.tools.cloneObject(JSON.parse(JSON.stringify(this.value[0])));
        if(method === 'new'){
            $.each(this.options.schema, function(){
                if(this.value) {
                    if(typeof this.value === 'function') {
                        _new[this.id] = this.value();
                    } else {
                        _new[this.id] = this.value;
                    }
                }
            })
        }

        this.value.splice(pos, 0, _new);
        this.createItem(_new, pos);
    };
    this.remove = function(pos){
        if(typeof pos === 'undefined') return;
        this.value.splice(pos, 1);
        this.items.splice(pos, 1);
        $(this.options.element).children().eq(pos).slideUp(function(){
            $(this).remove();
        });
        $(scope).trigger('change', [scope.value/*, scope.value[i]*/]);
    };

    this.createItem = function(curr, i){
        var box = this.createItemHolder();
        var header = this.createItemHolderHeader(i);
        var item = new mw.propEditor.schema({
            schema: this.options.schema,
            element: box.querySelector('.mw-ui-box-content')
        });
        $(box).prepend(header);
        this.headerAnalize(i, header);
        this.items.push(item);
        item.options.element._prop = item;
        item.setValue(curr);
        $(item).on('change', function(){
            $.each(item.getValue(), function(a, b){
                // todo: faster approach
                var index = $(box).parent().children('.mw-module-settings-box').index(box);
                scope.value[index][a] = b;
            });
            $(scope).trigger('change', [scope.value/*, scope.value[i]*/]);
        });
    };

    this._autoSaveTime = null;
    this.autoSave = function(){
        if(this.options.autoSave){
            clearTimeout(this._autoSaveTime);
            this._autoSaveTime = setTimeout(function(){
                scope.save();
            }, 500);
        }
    };

    this.refactorDomPosition = function(){
        scope.items = [];
        scope.value = [];
        $(".mw-module-settings-box-index", this.options.element).each(function (i) {
            $(this).text(i+1);
        });
        $('.mw-module-settings-box-content', this.options.element).each(function(i){
            scope.items.push(this._prop);
            scope.value.push(this._prop.getValue());
        });
        $(scope).trigger('change', [scope.value]);
    };

    this.create = function(){
        this.value.forEach(function(curr, i){
            scope.createItem(curr, i);
        });
        if(this.options.sortable && $.fn.sortable){
            var conf = {
                update: function (event, ui) {
                    setTimeout(function(){
                        scope.refactorDomPosition();
                        scope.autoSave();
                    }, 10)
                },
                handle:this.options.header ? '.mw-ui-box-header' : undefined
            };
            if(typeof this.options.sortable === 'object'){
                conf = $.extend({}, conf, this.options.sortable);
            }
            $(this.options.element).sortable(conf);
        }
    };

    this.init = function(){
        this.create();
    };

    this.save = function(){
        var key = (this.options.key || this.options.option_key);
        var group = (this.options.group || this.options.option_group);
        if( key && group){
            var options = {
                group:this.options.group,
                key:this.options.key,
                value:this.toString()
            };
            mw.options.saveOption(options, function(){
                mw.notification.msg(scope.savedMessage || mw.msg.settingsSaved)
            });
        }
        else{
            if(!key){
                console.warn('Option key is not defined.');
            }
            if(!group){
                console.warn('Option group is not defined.');
            }
        }

    };


    this.toString = function(){
        return JSON.stringify(this.value);
    };

    this.init();
};
