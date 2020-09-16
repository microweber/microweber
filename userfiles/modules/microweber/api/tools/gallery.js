(function(){

    var gallery = function (array, startFrom) {


        this.data = array;
        var scope = this;


        this._template = function () {
            var el = document.createElement('div');
            el.className = 'mw-gallery';
            el.innerHTML = '' +
            '<div class="">' +
                '<div class="mw-gallery-overlay"></div>' +
                '<div class="mw-gallery-content"></div>' +
                '<div class="mw-gallery-prev"></div>' +
                '<div class="mw-gallery-next"></div>' +
                '<div class="mw-gallery-controls">' +
                    '<span class="mw-gallery-control-play"></span>' +
                    '<span class="mw-gallery-control-fullscreen"></span>' +
                    '<span class="mw-gallery-control-close"></span>' +
                '</div>' +
            '</div>';
            return el;
        };

        this.createSingle = function (item) {
            var el = document.createElement('div');
            el.className = 'mw-gallery-item';
            var desc = !item.description ? '' : '<div class="mw-gallery-item-description">'+item.description+'</div>';
            el.innerHTML = '<div class="mw-gallery-item-image"><img src="'+(item.image || item.url || item.src)+'"></div>' + desc;
            this.container.appendChild(el);
            return el;
        };

        this.createItems = function () {
            this.data.forEach(function (item){
                scope.createSingle(item);
            });
        };

        this.init = function () {
            this.template = this._template();
            this.container = this.template.querySelector('.mw-gallery-content');
            this.createItems();
        };

        this.remove = function () {
            this.template.remove();
        };

        this.init();
    };



    // obsolate


    mw.gallery = function (array, startFrom){
        return new gallery(array, startFrom);
    };
    mw.tools.gallery = {
        init: mw.gallery
    };
})();
