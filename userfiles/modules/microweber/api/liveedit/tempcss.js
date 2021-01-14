mw.tempCSS = function(options){

    var scope = this;

    options = options || {};

    var defaults = {
        document: document,
        css: {}
    };

    this.settings = $.extend({}, defaults, options);

    this.styleElement = function() {
        if(!this._styleElement){
            this._styleElement = this.settings.document.createElement('style');
            this._styleElement.type = 'text/css';
            this._styleElement.appendChild(document.createTextNode('')); // webkit
            this.settings.document.body.appendChild(this._styleElement);
        }
        return this._styleElement;
    };

    this.modifyObject = function(){

    };

    this.addStyle = function(element, style, media){
        if(!element) return;
        if(element.tagName) {
            element = mw.tools.generateSelectorForNode(element);
        }
    };
};
