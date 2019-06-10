
(function(mw){



    mw.paddingEditor = function(options){
        this.create = function(){
            this.paddingTop = document.createElement('div');
            this.paddingTop.className = 'mw-padding-ctrl mw-padding-ctrl-top';

            this.paddingBottom = document.createElement('div');
            this.paddingBottom.className = 'mw-padding-ctrl mw-padding-ctrl-bottom';
        };
        this.init = function(){
            this.create()
        };

        this.init()
    };


})(window.mw);
