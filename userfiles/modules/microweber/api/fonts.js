mw.fonts = {
    _create:function(){
        var el = document.createElement('link');
        el.rel = 'stylesheet';
        document.documentElement.appendChild(el);
        return el;
    },
    _unique:function(obj){
        var data = {};
        var n;
        for(n in obj){
            data.name = n;
            data.weight = obj[n];
        }
        return data;
    },
    google:{
        create:function(){
            var root = 'https://fonts.googleapis.com/css?';
            var el = mw.fonts._create();
            el._rooturl = root;
            el._config = {};

            return el;
        },
        remove:function(family, weight, el){

        },
        setUrl:function(options, el){
            var url = 'family=';
            for( var i in options.family){
                url += i + ':'+options.family[i].join(',') + '|';
            }
            url = url.substring(0, url.length - 1);

            if(options.subset){
                url += '&amp;subset=' + options.subset.join(',')
            }
            el._config = options;
            el.href = el._rooturl + url;
        },
        config:function(options, el, mode){

            /*
            {
                family:{
                     'Roboto': [300,500] ,
                    'Tajawal': [400,700]
                },

                subset:["cyrillic","cyrillic-ext","korean","latin-ext"]
            }
            */



            else if(mode == 'add'){
                $.each(options.family, function(key,val){
                    if(el._config.family && el._config.family[key]){
                        options.family[key] = el._config.family[key].concat(options.family[key]);
                        options.family[key] = options.family[key].filter( function (value, i, self) {
                            return self.indexOf(value) == i;
                        });
                    }
                });
                $.each(el._config.family, function(key,val){
                    if(options.family && !options.family[key]){
                        options.family[key] = el._config.family[key]
                    }
                });
            }

            this.setUrl(options, el)

        }
    },
    noop:{
        fonts:[],
        config:function(){

        }
    }
}
mw.font = function(){
    this.data = {};
    this.init = function(options){
        options = options || {};
        if(options.provider){
            options.provider = options.provider.trim().toLowerCase();
        }
        else{
            options.provider = 'google';
        }
        if(!this[options.provider]){
            this[options.provider] = mw.fonts[options.provider].create();
        }
        this.options = options;
    }
    this.remove = function(family, weight){
        mw.fonts[this.options.provider].remove(family, weight, this[options.provider]);
    }

    this.add = function(options){
        this.init(options)
        mw.fonts[this.options.provider].config(this.options, this[options.provider], 'add');
    };

    this.set = function(options){
        this.init(options);
        mw.fonts[this.options.provider].config(this.options, this[options.provider]);
    };


}