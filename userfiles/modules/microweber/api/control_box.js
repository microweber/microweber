mw.controlBox = function(options){
    this.options = options;
    this.defaults = {
        position:'bottom',
        content:'',
        skin:'default'
    };
    this.settings = $.extend({}, this.defaults, this.options);
    this.active = false;

    this.build = function(){
        this.box = document.createElement('div');
        this.box.className = 'mw-control-box mw-control-box-' + this.settings.position + ' mw-control-box-' + this.settings.skin;
        this.boxContent = document.createElement('div');
        this.boxContent.className = 'mw-control-boxcontent';
        this.box.appendChild(this.boxContent);
        this.closeButton = document.createElement('span');
        this.closeButton.className = 'mw-control-boxclose';
        this.box.appendChild(this.closeButton);
        var scope = this;
        this.closeButton.onclick = function(){
            scope.hide()
        }
        document.body.appendChild(this.box)
    }

    this.setContentByUrl = function(){
        var cont = this.settings.content.trim();
        var scope = this;
        return $.get(cont, function(data){
            scope.boxContent.innerHTML = data;
            this.settings.content = data;
        });
    }
    this.setContent = function(c){
        var cont = c||this.settings.content.trim();
        this.settings.content = cont;
        if(cont.indexOf('http://') === 0 || cont.indexOf('https://') === 0){
            return this.setContentByUrl()
        }
        this.boxContent.innerHTML = cont;
    }

    this.show = function(){
        this.active = true;
        $(this.box).addClass('active')
    }

    this.init = function(){
        this.build();
        this.setContent()
    }
    this.hide = function(){
        this.active = false;
        $(this.box).removeClass('active')
    }


    this.toggle = function(){
        this.active = !this.active;
        this[this.active?'hide':'show']();
    }
    this.init()
}