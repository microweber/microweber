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

    this.createItemHolderHeader = function(){
        if(this.options.header){
            var header = document.createElement('div');
            header.className = "mw-ui-box-header";
            header.innerHTML = this.options.header;
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
        holder.className = 'mw-ui-box mw-ui-box-content';
        this.options.element.appendChild(holder);
        return holder;
    };

    this.addNew = function(pos){
        if(typeof pos === 'undefined'){
            pos = this.items.length;
        }

        var _new = mw.tools.cloneObject(this.value[0]);
        this.value.splice(pos, 0, _new);
        this.createItem(_new, this.value.length-1);
    };
    this.remove = function(pos){
        if(typeof pos === 'undefined') return;
        this.value.splice(pos, 1);
        this.items.splice(pos, 1);
        console.log(pos, $(this.options.element).children().eq(pos))
        $(this.options.element).children().eq(pos).slideUp(function(){
            $(this).remove();
        });
        $(scope).trigger('change', [scope.value/*, scope.value[i]*/]);
    };

    this.createItem = function(curr, i){
        var box = this.createItemHolder();
        var header = this.createItemHolderHeader();
        var item = new mw.propEditor.schema({
            schema: this.options.schema,
            element: box
        });
        $(box).prepend(header);
        this.headerAnalize(i, header);
        this.items.push(item);
        item.options.element._prop = item;
        item.setValue(curr);
        $(item).on('change', function(){
            $.each(this.getValue(), function(a, b){
                scope.value[i][a] = b;
            });
            $(scope).trigger('change', [scope.value/*, scope.value[i]*/]);
        });
    };

    this._autoSaveTime = null;
    this.autoSave = function(){
        if(this.options.autoSave){
            clearTimeout(this._autoSaveTime);
            this._autoSaveTime = setTimeout(function(){
                scope.save()
            }, 500);
        }
    };

    this.refactorDomPosition = function(){
        scope.items = [];
        scope.value = [];
        $(this.options.element).children().each(function(){
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
                    scope.refactorDomPosition();
                    scope.autoSave();
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
        var val = this.toString();

        // ...options.save(this.options.key, this.options.group, val)
    };


    this.toString = function(){
        return JSON.stringify(this.value)
    };

    this.init();
};