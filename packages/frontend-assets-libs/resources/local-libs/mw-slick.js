if( $.fn.slick ) {
    $.fn._slick =  $.fn.slick;
    $.fn.slick = function(options = {}) {
        return this.each(function() {
            const attr = $(this).attr('data-slick');
            let attrOptions = {};
            if(attr) {
                try {
                    const frag = document.createElement("div");
                    frag.innerHTML = attr;
                    attrOptions = JSON.parse(frag.innerHTML);
                } catch(e) {

                }
            }
            options = $.extend({}, options, attrOptions);
            return $(this)._slick(options);
        })

    }
}
