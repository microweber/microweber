mw.blockEdit = function (options) {
    options = options || {};
    var defaults = {
        element: document.body,
        mode: 'in' // in | out
    };

    var settings = $.extend({}, defaults, options);

    settings.$element = mw.$(settings.element);
    settings.element = settings.$element[0];
    this.settings = settings;
    if(!settings.element) {
        return;
    }

    this.build = function(){
        this.holder = document.createElement('div');
        this.slider = document.createElement('div');
        this.mainSlide = document.createElement('div');
        this.editSlide = document.createElement('div');

        this.slider.appendChild(this.mainSlide);
        this.slider.appendChild(this.editSlide);
        this.holder.appendChild(this.slider);
        this.settings.$element.before(this.holder);
        this.mainSlide.appendChild(settings.element);
    };

    this.init = function () {
        this.build();
    };

};
