(function(){


    var Gallery = function (array, startFrom) {
        startFrom = startFrom || 0;

        this.currentIndex = startFrom;

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
                    /*'<span class="mw-gallery-control-fullscreen"></span>' +*/
                    '<span class="mw-gallery-control-close"></span>' +
                '</div>' +
            '</div>';
            return el;
        };

        this.createSingle = function (item, i) {
            var el = document.createElement('div');
            el.className = 'mw-gallery-fullscreen-item mw-gallery-item-' + i + (startFrom === i ? ' active' : '');
            var desc = !item.description ? '' : '<div class="mw-gallery-item-description"><div class="mw-gallery-item-description-content">'+item.description+'</div></div>';
            el.innerHTML = '<div class="mw-gallery-item-image"><img src="'+(item.image || item.url || item.src || item.filename)+'"></div>' + desc;
            this.container.appendChild(el);
            return el;
        };

        this.next = function () {
            this.currentIndex++;
            if(!this._items[this.currentIndex]) {
                this.currentIndex = 0;
            }
            this.goto(this.currentIndex);
        };

        this.prev = function () {
            this.currentIndex--;
            if(!this._items[this.currentIndex]) {
                this.currentIndex = this._items.length - 1;
            }
            this.goto(this.currentIndex);
        };

        this.goto = function (i) {
            if(i > -1 && i < this._items.length) {
                this.currentIndex = i;
                this._items.forEach(function (item, i){
                    item.classList.remove('active');
                    if(i === scope.currentIndex) {
                        item.classList.add('active');
                    }
                });
            }
        };

        this.paused = true;

        this.pause = function () {
            this.paused = true;
            clearTimeout(this.playInterval);
            mw.tools.loading(this.template, false, );
        };

        this.playInterval = null;
        this._play = function () {
            if(this.paused) return;
            mw.tools.loading(this.template, 100, 'slow');
            this.playInterval = setTimeout(function (){
                mw.tools.loading(scope.template, 'hide');
                scope.next();
                scope._play();
            },5000);
        };

        this.play = function () {
            this.next();
            this.paused = false;
            this._play();
        };

        this._items = [];

        this.createHandles = function () {
            this.template.querySelector('.mw-gallery-prev').onclick = function (){ scope.pause(); scope.prev(); };
            this.template.querySelector('.mw-gallery-next').onclick = function (){ scope.pause(); scope.next(); };
            this.template.querySelector('.mw-gallery-control-close').onclick = function (){ scope.remove(); };
            this.template.querySelector('.mw-gallery-control-play').onclick = function (){
                scope[scope.paused ? 'play' : 'pause']();
                this.classList[scope.paused ? 'remove' : 'add']('pause');
            };
        };

        this.createItems = function () {
            this.data.forEach(function (item, i ){
                scope._items.push(scope.createSingle(item, i));
            });
        };

        this.init = function () {
            this.template = this._template();
            document.body.appendChild(this.template);
            this.container = this.template.querySelector('.mw-gallery-content');
            this.createItems();
            this.createHandles();
        };

        this.remove = function () {
            this.template.remove();
        };

        this.init();
    };

    mw.gallery = function (array, startFrom){
        return new Gallery(array, startFrom);
    };

    // obsolate:
    mw.tools.gallery = {
        init: mw.gallery
    };
})();
