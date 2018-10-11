mw.autoComplete = function(options){
    var scope = this;
    this.prepare = function(options){
        options = options || {};
        if(!options.data && !options.ajaxConfig) return;
        var defaults = {
            size:'normal'
        }
        this.options = $.extend({}, defaults, options);
        this.searchTime = null;
        this.searchTimeout = this.options.data ? 0 : 500;
        this.results = [];
        this.map = this.options.map;
    }

    this.createListHolder = function(){
        this.listHolder = document.createElement('ul');
        this.listHolder.className = 'mw-autocomplete-list';
        return this.listHolder;
    }

    this.createWrapper = function(){
        this.wrapper = document.createElement('div');
        this.wrapper.className = 'mw-autocomplete';
        return this.wrapper;
    }

    this.createField = function(){
         this.inputField = document.createElement('input');
        this.inputField.className = 'mw-ui-invisible-field mw-ui-field-' + this.options.size;
        $(this.inputField).on('input', function(){
            scope.search(this.value);
        });
        return this.inputField;
    }

    this.buildUI = function(){

    }

    this.createListItem = function(data){
        var li = document.createElement('li');
        li.value = this.dataValue(data)
        li.html = this.dataValue(data)
        var img = this.dataImage(data)
        if(img){
            li.appendChild(img)
        }
    }

    this.rendResults = function(){
        $(this.listHolder).empty();
        $.each(this.data, function(){
            scope.listHolder.appendChild(scope.createListItem(this))
        })
    }

    this.dataValue = function(data){
        if(typeof data === 'string'){
            return data;
        }
        else{
            return data[this.map.value]
        }
    }
    this.dataImage = function(data){
        if(data.image){
            var img = document.createElement('img');
            img.src = data.image;
        }
    }
    this.dataTitle = function(data){
        if(typeof data === 'string'){
            return data;
        }
        else{
            return data[this.map.title]
        }
    }

    this.searchRemote = function(val){
        var config = mw.tools.cloneObject(this.options.ajaxConfig);

        if(config.data){
            if(typeof config.data === 'string'){
                config.data = config.data.replace('${val}',val)
            }
            else{
               $.each(config.data, function(key,val){
                    if(val.indexOf('${val}')!==-1){
                        config.data[key] = val.replace('${val}',val)
                    }
               })
            }
        }
        if(config.url){
            config.url.replace('${val}',val)
        }
        var xhr = $.ajax(config);
        xhr.done(function(data){
            if(data.data){
                scope.data = data.data;
            }
            else{
               scope.data = data;
            }
            scope.results = scope.data
        })
    }

    this.searchLocal = function(val){
        this.results = [];
        var toSearch;
        $.each(this.data, function(){
           if(typeof this === 'string'){
                toSearch = this.toLowerCase()
           }
           else{
               toSearch = this[scope.map.value].toLowerCase()
           }
           if(toSearch.indexOf(val)){
            this.results.push(this)
           }
        })
    }

    this.search = function(val){
        val = val || '';
        val = val.trim().toLowerCase();

        if(this.options.data){
            this.searchLocal(val)
        }
        else{
            clearTimeout(this.searchTime);
            setTimeout(function(){
                scope.searchRemote(val);
            }, this.searchTimeout)
        }
    }



    this.prepare(options);

}