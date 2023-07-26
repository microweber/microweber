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
    this.settings = $.extend({}, this.defaults, this.options);
    this.active = false;

    this.build = function(){
        this.box = document.createElement('div');
        this.box.className = 'mw-control-box mw-control-box-' + this.settings.position + ' mw-control-box-' + this.settings.skin;
        this.box.id = this.id;
        this.boxContent = document.createElement('div');
        this.boxContent.className = 'mw-control-boxcontent';
        this.box.appendChild(this.boxContent);
        this.createCloseButton();
        document.body.appendChild(this.box);
    };


    this.position = function(position) {
        if(typeof position === 'undefined') {
            return this.settings.position
        }
        this.box.classList.remove('mw-control-box-' + this.settings.position);
        this.settings.position = position;
        this.box.classList.add('mw-control-box-' + this.settings.position);
        return this.settings.position;
    }

    this.createCloseButton = function () {
        if(!this.options.closeButton) return;
        this.closeButton = document.createElement('span');
        this.closeButton.className = 'mw-control-boxclose';
        this.box.appendChild(this.closeButton);
        this.closeButton.onclick = function(){
            scope.hide();
        };
    };

    this.setContentByUrl = function(){
        var cont = this.settings.content.trim();
        return $.get(cont, function(data){
            scope.boxContent.innerHTML = data;
            scope.settings.content = data;
        });
    };
    this.setContent = function(c){
        var cont = c||this.settings.content.trim();
        this.settings.content = cont;
        if(cont.indexOf('http://') === 0 || cont.indexOf('https://') === 0){
            return this.setContentByUrl()
        }
        this.boxContent.innerHTML = cont;
    };

    this.show = function(){
        this.active = true;
        mw.$(this.box).addClass('active') ;
        mw.$(this).trigger('ControlBoxShow')
    };

    this.init = function(){
        this.build();
        this.setContent();
    };
    this.hide = function(){
        this.active = false;
        mw.$(this.box).removeClass('active');
        mw.$(this).trigger('ControlBoxHide');
    };


    this.toggle = function(){
        this[this.active?'hide':'show']();
    };
    this.init();
};
