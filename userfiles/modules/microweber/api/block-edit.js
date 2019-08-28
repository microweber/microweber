mw.blockEdit = function (options) {
    options = options || {};
    var defaults = {
        element: document.body,
        mode: 'wrap' // wrap | in
    };

    var scope = this;

    var settings = $.extend({}, defaults, options);

    settings.$element = mw.$(settings.element);
    settings.element = settings.$element[0];
    this.settings = settings;
    if(!settings.element) {
        return;
    }

    this.set = function(mode){
        if(mode === 'edit'){
            this.$slider.stop().animate({
                left: '-100%',
                height: this.$editSlide.outerHeight()
            }, function(){
                scope.$holder.addClass('mw-block-edit-editing');
                scope.$slider.height('auto');
            });
        } else {
            this.$slider.stop().animate({
                left: '0',
                height: this.$mainSlide.outerHeight()
            }, function(){
                scope.unEditByElement();
                scope.$holder.removeClass('mw-block-edit-editing');
                scope.$slider.height('auto');
            });
        }
    };

    this.close = function(content){
        this.set();
        $(this).trigger('CloseEdit');
    };
    this.edit = function(content){
        if(content){
            this.$editSlide.empty().append(content);
        }
        this.set('edit');
        $(this).trigger('Edit');
    };

    this._editByElement = null;
    this._editByElementTemp = null;

    this.unEditByElement = function(){
      if(this._editByElement){
          $(this._editByElementTemp).replaceWith(this._editByElement);
          $(this._editByElement).hide()
      }
      this._editByElement = null;
      this._editByElementTemp = null;
    };

    this.editByElement = function(el){
        if(!el){
            return;
        }
        this.unEditByElement();
        this._editByElementTemp = document.createElement('mw-temp');
        this._editByElement = el;
        $(el).before(this._editByElementTemp);
        this.editSlide.appendChild(el);
        $(el).show()
    };
    this.moduleEdit = function(module){
        mw.tools.loading(this.holder, 90);
        mw.load_module(module, this.editSlide, function(){
            scope.edit();
            mw.tools.loading(scope.holder, false);
        });
    };

    this.build = function(){
        this.holder = document.createElement('div');
        this.$holder = $(this.holder);
        this.holder.className = 'mw-block-edit-holder';
        this.holder._blockEdit = this;
        this.slider = document.createElement('div');
        this.$slider = $(this.slider);
        this.slider.className = 'mw-block-edit-slider';
        this.mainSlide = document.createElement('div');
        this.$mainSlide = $(this.mainSlide);
        this.editSlide = document.createElement('div');
        this.$editSlide = $(this.editSlide);
        this.mainSlide.className = 'mw-block-edit-main-slide';
        this.editSlide.className = 'mw-block-edit-edit-slide';

        this.slider.appendChild(this.mainSlide);
        this.slider.appendChild(this.editSlide);
        this.holder.appendChild(this.slider);
        //this.settings.$element.before(this.holder);
        // this.mainSlide.appendChild(settings.element);

    };

    this.initMode = function(){
        if(this.settings.mode === 'wrap') {
            this.settings.$element.after(this.holder);
            this.$mainSlide.append(this.settings.$element);
        } else if(this.settings.mode === 'in') {
            this.settings.$element.wrapInner(this.holder);
        }
    };

    this.init = function () {
        this.build();
        this.initMode();
    };

    this.init();

};

mw.blockEdit.get = function(target){
    target = target || '.mw-block-edit-holder';
    target = mw.$(target);
    if(!target[0]) return;
    if(target.hasClass('mw-block-edit-holder')){
        return target[0]._blockEdit;
    } else {
        var node = mw.tools.firstParentWithClass(target[0], 'mw-block-edit-holder') || target[0].querySelector('.mw-block-edit-holder');
        if(node){
            return node._blockEdit;
        }
    }
};

$.fn.mwBlockEdit = function (options) {
    options = options || {};
    return this.each(function(){
        this.mwBlockEdit =  new mw.blockEdit($.extend({}, options, {element: this }));
    });
};

mw.registerComponent('block-edit', function(el){
    var options = mw.components._options(el);
    mw.$(el).mwBlockEdit(options);
});
mw.registerComponent('block-edit-closeButton', function(el){
    mw.$(el).on('click', function(){
        mw.blockEdit.get(this).close();
    });
});
mw.registerComponent('block-edit-editButton', function(el){
    mw.$(el).on('click', function(){
        var options = mw.components._options(this);
        if(options.module){
            mw.blockEdit.get(options.for || this).moduleEdit(options.module);
            return;
        } else if(options.element){
            var el = mw.$(options.element)[0];
            if(el){
                mw.blockEdit.get(options.for || this).editByElement(el);
            }
        }
        mw.blockEdit.get(this).edit();
    });
});
