mw.autoComplete = function(options){
    var scope = this;
    this.prepare = function(options){
        options = options || {};
        if(!options.data && !options.ajaxConfig) return;
        var defaults = {
            size:'normal',
            multiple:false,
            map: { title:'title', value:'id' },
            titleDecorator: function (title, data) {
                return title;
            }
        };
        this.options = $.extend({}, defaults, options);
        this.options.element = mw.$(this.options.element)[0];
        if(!this.options.element){
            return;
        }
        this.element = this.options.element;
        this.data = this.options.data;
        this.searchTime = null;
        this.searchTimeout = this.options.data ? 0 : 500;
        this.results = [];
        this.map = this.options.map;
        this.selected = this.options.selected || [];
    };

    this.createValueHolder = function(){
        this.valueHolder = document.createElement('div');
        this.valueHolder.className = 'mw-autocomplete-value';
        return this.valueHolder;
    };
    this.createListHolder = function(){
        this.listHolder = document.createElement('ul');
        this.listHolder.className = 'mw-ui-box mw-ui-navigation mw-autocomplete-list';
        this.listHolder.style.display = 'none';
        return this.listHolder;
    };

    this.createWrapper = function(){
        this.wrapper = document.createElement('div');
        this.wrapper.className = 'mw-ui-field w100 mw-autocomplete mw-autocomplete-multiple-' + this.options.multiple;
        return this.wrapper;
    };

    this.createField = function(){
        this.inputField = document.createElement('input');
        this.inputField.className = 'mw-ui-invisible-field mw-autocomplete-field mw-ui-field-' + this.options.size;
        if(this.options.placeholder){
            this.inputField.placeholder = this.options.placeholder;
        }
        mw.$(this.inputField).on('input focus', function(e){
            var val = e.type === 'focus' ? '' : this.value;
            scope.search(val);
        });
        return this.inputField;
    };

    this.buildUI = function(){
        this.createWrapper();
        this.wrapper.appendChild(this.createValueHolder());
        this.wrapper.appendChild(this.createField());
        this.wrapper.appendChild(this.createListHolder());
        this.element.appendChild(this.wrapper);
    };

    this.createListItem = function(data){
        var li = document.createElement('li');
        li.value = this.dataValue(data);
        var img = this.dataImage(data);

        mw.$(li)
        .append( '<a href="javascript:;">'+this.dataTitle(data)+'</a>' )
        .on('click', function(){
            scope.select(data);
        });
        if(img){
            mw.$('a',li).prepend(img);
        }
        return li;
    };

    this.uniqueValue = function(){
        var uniqueIds = [], final = [];
        this.selected.forEach(function(item){
            var val = scope.dataValue(item);
            if(uniqueIds.indexOf(val) === -1){
                uniqueIds.push(val);
                final.push(item);
            }
        });
        this.selected = final;
    };

    this.select = function(item){
        if(this.options.multiple){
            this.selected.push(item);
        }
        else{
            this.selected = [item];
        }
        this.rendSelected();
        if(!this.options.multiple){
            this.listHolder.style.display = 'none';
        }
        mw.$(this).trigger('change', [this.selected]);
    };

    this.rendSingle = function(){
        var item = this.selected[0];
        this.inputField.value = item ? item[this.map.title] : '';
        this.valueHolder.innerHTML = '';
        var img = this.dataImage(item);
        if(img){
            this.valueHolder.appendChild(img);
        }

    };

    this.rendSelected = function(){
        if(this.options.multiple){
            this.uniqueValue();
            this.chips.setData(this.selected);
        }
        else{
            this.rendSingle();
        }
    };

    this.rendResults = function(){
        mw.$(this.listHolder).empty().show();
        if(typeof this.results === 'string' || !this.results || typeof this.results.length === 'undefined') {
            console.warn('results object must be array');
        }
        $.each(this.results, function(){
            scope.listHolder.appendChild(scope.createListItem(this));
        });
    };

    this.dataValue = function(data){
        if(!data) return;
        if(typeof data === 'string'){
            return data;
        }
        else{
            return data[this.map.value];
        }
    };
    this.dataImage = function(data){
        if(!data) return;
        if(data.picture){
            data.image = data.picture;
        }
        if(data.image){
            var img = document.createElement('span');
            img.className = 'mw-autocomplete-img';
            img.style.backgroundImage = 'url(' + data.image + ')';
            return img;
        }
    };
    this.dataTitle = function(data){
        if (!data) return;
        var title;
        if (typeof data === 'string') {
            title = data;
        }
        else {
            title = data[this.map.title];
        }

        return this.options.titleDecorator(title, data);
    };

    this.searchRemote = function(val){
        var config = mw.tools.cloneObject(this.options.ajaxConfig);

        if(config.data){
            if(typeof config.data === 'string'){
                config.data = config.data.replace('${val}',val);
            }
            else{
               $.each(config.data, function(key,value){

                    if(value.indexOf && value.indexOf('${val}') !==-1 ){
                        config.data[key] = value.replace('${val}', val);
                    }
               });
            }
        }
        if(config.url){
            config.url = config.url.replace('${val}',val);
        }
        var xhr = $.ajax(config);
        xhr.done(function(data){
            if(data.data){
                scope.data = data.data;
            }
            else{
               scope.data = data;
            }
            scope.results = scope.data || [];
            scope.rendResults();
        })
        .always(function(){
            scope.searching = false;
        });
    };

    this.searchLocal = function(val){

        this.results = [];
        var toSearch;
        $.each(this.data, function(){
           if(typeof this === 'string'){
                toSearch = this.toLowerCase();
           }
           else{
               toSearch = this[scope.map.title].toLowerCase();
           }
           if(toSearch.indexOf(val) !== -1){
            scope.results.push(this);
           }
        });
       this.rendResults();
       scope.searching = false;
    };

    this.search = function(val){
        if(scope.searching) return;
        val = val || '';
        val = val.trim().toLowerCase();

        if(this.options.data){
            this.searchLocal(val);
        }
        else{
            clearTimeout(this.searchTime);
            setTimeout(function(){
                scope.searching = true;
                scope.searchRemote(val);
            }, this.searchTimeout);
        }
    };

    this.init = function(){
        this.prepare(options);
        this.buildUI();
        if(this.options.multiple){
            this.chips = new mw.chips({
                element:this.valueHolder,
                data:[]
            });
        }
        this.rendSelected();
        this.handleEvents();
    };

    this.handleEvents = function(){
        mw.$(document.body).on('click', function(e){
            if(!mw.tools.hasParentsWithClass(e.target, 'mw-autocomplete')){
                scope.listHolder.style.display = 'none';
            }
        });
    };


    this.init();

};
