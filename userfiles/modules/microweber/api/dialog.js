
(function(mw){



mw.dialog = function(options){
    return new mw.Dialog(options);
}

mw.dialogIframe = function(options){
    options.pauseInit = true;
    options.content = '<iframe src="'+options.url.trim()+'"><iframe>';
    return new mw.Dialog(options);
}

mw.dialog.get = function (selector) {
    var el = mw.$(selector),
        child_cont = el.querySelector('.mw-dialog-holder'),
        parent_cont = el.parents(".mw-dialog-holder:first");
    if (child_cont) {
        return child_cont._dialog;
    }
    else if(parent_cont.length !== 0){
        return parent_cont[0]._dialog;
    }
    else {
        return false;
    }
}

mw.Dialog = function(options){

        options.content = options.content || '';

        this.options = options;
        this.id = this.options.id || 'mw-dialog-' + new Date().getTime();
        this.options.id = this.id;
        this.options.overlay = typeof this.options.overlay === 'undefined' ? true : this.options.overlay;
        this.options.overlayClose = typeof this.options.overlayClose === 'undefined' ? true : this.options.overlayClose;
        this.options.autoCenter = typeof this.options.autoCenter === 'undefined' ? true : this.options.overlayClose;
        this.options.root = this.options.root || document.body;

       this.hasBeenCreated = function(){
           return document.getElementById(this.id) !== null;
       }
        if(this.hasBeenCreated()){
            return  document.getElementById(this.id)._dialog;
        }

        mw.__dialogs = mw.__dialogs || [];

        mw.__dialogs.push(this);

        this.build = function(){
            this.dialogHolder = document.createElement('div');
            this.dialogHolder.id = this.id;
            this.dialogHolder._dialog = this;

            this.dialogHeader = document.createElement('div')
            this.dialogFooter = document.createElement('div')

            this.dialogContainer = document.createElement('div');
            var cls = !this.options.className ? '' : ' '+ this.options.className;
            this.dialogContainer.className = 'mw-dialog-container';
            this.dialogHolder.className = 'mw-dialog-holder' + cls;
            $(this.dialogContainer).append(this.options.content)

            this.dialogHolder.appendChild(this.dialogHeader);
            this.dialogHolder.appendChild(this.dialogContainer);
            this.dialogHolder.appendChild(this.dialogFooter);

            this.closeButton = document.createElement('div');
            this.closeButton.className = 'mw-dialog-close';

            this.closeButton.$scope = this;

            this.closeButton.onclick = function(){
                this.$scope.remove();
            }

            this.dialogHolder.appendChild(this.closeButton);

            this.width(this.options.width || 600);
            this.height(this.options.height || 320);

            this.options.root.appendChild(this.dialogHolder);
            return this;
        }

        this.dialogOverlay = function(){
            this.overlay = document.createElement('div');
            this.overlay.className = 'mw-dialog-overlay';
            this.overlay.$scope = this;
            if(this.options.overlay === true){
                this.options.root.appendChild(this.overlay);
            }
            $(this.overlay).on('click', function(){
                if(this.$scope.options.overlayClose === true){
                    this.$scope.remove();
                }
            });

            return this;
        }

        this.show = function(){
            $(this.overlay).addClass('active');
            $(this.dialogHolder).addClass('active');
            this.center();
            $(this).trigger('show');
            return this;
        }

        this.hide = function(){
            $(this.overlay).removeClass('active');
            $(this.dialogHolder).removeClass('active');
            $(this).trigger('hide');
            return this;
        }

        this.remove = this.destroy = function(){
            this.hide();
            if(this.$scope.onremove){
                this.$scope.onremove.call(this.$scope, this.$scope);
            }
            $(this.$scope).trigger('remove');
            $(this.overlay).remove();
            $(this.dialogHolder).remove();
            return this;
        }


        this.center = function(){
            var $holder = $(this.dialogHolder), $window = $(window);
            var dtop = $window.height()/2 -  $holder.outerHeight()/2
            $holder.css({
                left: $window.outerWidth()/2 -  $holder.outerWidth()/2,
                top: dtop > 0 ? dtop : 0,
            });
            $(this).trigger('center');
            return this;
        }

       this.width = function(width){
            $(this.dialogContainer).width(width);
       }
       this.height = function(height){
            $(this.dialogContainer).height(height);
       }

       this.content = function(content){
            this.options.content = content || '';
            this.dialogContainer.innerHTML =  this.options.content;
            return this;
       }


    this.init = function(){
            this.dialogOverlay()
            this.build();
            this.center();
            scope.show();
            if(this.options.autoCenter){
                (function(scope){
                    $(window).on('resize orientationchange load', function(){
                        scope.center()
                    });
                })(this);
            }
            if(!this.options.pauseInit){
                $(this).trigger('init');
            }

        return this;
    }
    this.init();

}


})(window.mw);