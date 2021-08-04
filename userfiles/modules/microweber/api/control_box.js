mw.controlBox = function(options){
    var scope = this;
    this.options = options;
    this.defaults = {
        position:'bottom',
        content:'',
        skin:'default',
        id:this.options.id || 'mw-control-box-'+mw.random(),
        closeButton: true
    };
    this.id = this.options.id;
    this.settings = Object.assign({}, this.defaults, this.options);
    this.active = false;

    var _e = {};

    this.on = function (e, f) { _e[e] ? _e[e].push(f) : (_e[e] = [f]); return this; };

    this.dispatch = function (e, f) { _e[e] ? _e[e].forEach(function (c){ c.call(this, f); }) : ''; return this; };

    this.build = function(){
        this.box = document.createElement('div');
        this.box.className = 'mw-control-box mw-control-box-' + this.settings.position + ' mw-control-box-' + this.settings.skin;
        this.box.id = this.id;
        this.boxContent = document.createElement('div');
        this.boxContent.className = 'mw-control-boxcontent';
        if(this.settings.width) {
            this.box.style.width = typeof this.settings.width === 'number' ? (this.settings.width + 'px') : this.settings.width;
        }
        if(this.settings.height) {
            this.box.style.height =  typeof this.settings.height === 'number' ? (this.settings.height + 'px') : this.settings.height;
        }
        this.box.appendChild(this.boxContent);
        this.createCloseButton();
        document.body.appendChild(this.box);
    };

    this.createCloseButton = function () {
        if(!this.options.closeButton) return;
        this.closeButton = document.createElement('span');
        this.closeButton.className = 'mw-control-boxclose';
        this.box.appendChild(this.closeButton);
        this.closeButton.onclick = function(){
            scope.hide();
        };
    };


    this.setContent = function(c){
        var cont = c||this.settings.content.trim();
        this.settings.content = cont;

        this.boxContent.innerHTML = cont;
    };

    this.show = function(){
        this.active = true;
        this.box.classList.add('active')
        this.dispatch('ControlBoxShow')
    };

    this.init = function(){
        this.build();
        this.setContent();
    };
    this.hide = function(){
        this.active = false;
        this.box.classList.remove('active')

        this.dispatch('ControlBoxHide');
    };


    this.toggle = function(){
        this[this.active?'hide':'show']();
    };
    this.init();
};
