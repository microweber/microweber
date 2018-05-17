mw.controlBox = function(options){
    this.options = options;
    this.defaults = {
        position:'bottom',
        content:''
    };
    this.settings = $.extend({}, this.defaults, this.options);
    this.active = false;

    this.build = function(){
        this.box = document.createElement('div');
        this.box.className = 'mw-control-box mw-control-box-' + this.settings.position;
        document.body.appendChild(this.box)
    }

    this.setContentByUrl = function(){
        var cont = this.settings.content.trim();
        var scope = this;
        return $.get(cont, function(data){
            scope.box.innerHTML = data;
        });
    }
    this.setContent = function(){
        var cont = this.settings.content.trim();
        if(cont.indexOf('http://') === 0 || cont.indexOf('https://') === 0){
            return this.setContentByUrl()
        }
        this.box.innerHTML = cont;
    }

    this.activate = function(){
        this.active = true;
        $(this.box).addClass('active')
    }

    this.deactivate = function(){
        this.active = false;
        $(this.box).removeClass('active')
    }


    this.toggle = function(){
        this.active = !this.active;
        this[this.active?'activate':'deactivate']();
    }
}