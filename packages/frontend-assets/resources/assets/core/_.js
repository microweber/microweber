mw.temp_reload_module_queue_holder = [];




mw["_"] = function(obj, sendSpecific, DONOTREPLACE) {
    if(mw.on){
        mw.on.DOMChangePause = true;
    }
    var url = typeof obj.url !== 'undefined' ? obj.url : mw.settings.site_url+'module/';
    var selector = typeof obj.selector !== 'undefined' ? obj.selector : '';
    var params =  typeof obj.params !== 'undefined' ? obj.params : {};
    var to_send = params;
    if(typeof $(obj.selector)[0] === 'undefined') {
        mw.pauseSave = false;
        mw.on.DOMChangePause = false;
        return false;
    }
    if(mw.session){
        mw.session.checkPause = true;
    }
    var $node = $(obj.selector);
    var node = $node[0];
    var attrs = node.attributes;



    // wait between many reloads
    if (node.id) {
        if ( mw.temp_reload_module_queue_holder.indexOf(node.id) === -1){
            mw.temp_reload_module_queue_holder.push(node.id);
            setTimeout(function() {
                var reload_index = mw.temp_reload_module_queue_holder.indexOf(node.id);
                delete mw.temp_reload_module_queue_holder[reload_index];
            }, 300);
        } else {
            return;
        }
    }

    if (sendSpecific) {
        attrs["class"] !== undefined ? to_send["class"] = attrs["class"].nodeValue : "";
        attrs["data-module-name"] !== undefined ? to_send["data-module-name"] = attrs["data-module-name"].nodeValue : "";
        attrs["data-type"] !== undefined ? to_send["data-type"] = attrs["data-type"].nodeValue : "";
        attrs["type"] !== undefined ? to_send["type"] = attrs["type"].nodeValue : "";
        attrs["template"] !== undefined ? to_send["template"] = attrs["template"].nodeValue : "";
        attrs["ondrop"] !== undefined ? to_send["ondrop"] = attrs["ondrop"].nodeValue : "";
    }
    else {
        for (var i in attrs) {
            if(attrs[i] !== undefined){
                var name = attrs[i].name;
                var val = attrs[i].nodeValue;
                if(typeof to_send[name] === 'undefined'){
                    to_send[name]  = val;
                }
            }
        }
    }
    var b = true;
    for (var a in to_send) {
        if(to_send.hasOwnProperty(a)) { b = false; }
    }
    if(b){
        mw.tools.removeClass(document.body, 'loading');
        mw.pauseSave = false;
        mw.on.DOMChangePause = false;
        return false;
    }
    console.log(2, $node);
    var storedValues = $node.get(0).dataset['storeValues'] === 'true' ? {} : false;
    if(storedValues) {
        $node.find('[name]').each(function () {
            storedValues[this.name] = $(this).val();
        })
    }

    var xhr = $.post(url, to_send, function(data) {

        if(!!mw.session){
            mw.session.checkPause = false;
        }
        if(DONOTREPLACE){

            mw.tools.removeClass(document.body, 'loading');
            mw.pauseSave = false;
            mw.on.DOMChangePause = false;
            return false;
        }

        var docdata = mw.tools.parseHtml(data);

        if(storedValues) {
            mw.$('[name]', docdata).each(function(){
                var el = $(this);
                if(!el.val()) {
                    el.val(storedValues[this.name] || undefined);
                    this.setAttribute("value", storedValues[this.name] || '');
                }
            })
        }

        var hasDone = typeof obj.done === 'function';
        var id;
        if (typeof to_send.id  !== 'undefined') {
            id = to_send.id;
        } else{
            id = docdata.body.querySelector(['id']);
        }
        mw.$(selector).replaceWith($(docdata.body).html());
        var count = 0;
        if(hasDone){
            setTimeout(function(){
                count++;
                obj.done.call($(selector)[0], data);
                mw.trigger('moduleLoaded');
            }, 33);
        }

        if(!id){
            mw.pauseSave = false;
            mw.on.DOMChangePause = false;
            return false;
        }


        typeof mw.drag !== 'undefined' ? mw.drag.fix_placeholders(true) : '';
        var m = document.getElementById(id);
        // typeof obj.done === 'function' ? obj.done.call(selector, m) : '';

        if(mw.wysiwyg){
            $(m).hasClass("module") ? mw.wysiwyg.init_editables(m) : '' ;
        }


        if(mw.on && !hasDone){
            mw.on.moduleReload(id, "", true);
            mw.trigger('moduleLoaded');
        }
        if($.fn.selectpicker) {
            $('.selectpicker').selectpicker();
        }
        if (mw.on) {
            mw.on.DOMChangePause = false;
        }
        mw.tools.removeClass(document.body, 'loading');


    })
        .fail(function(){
            mw.pauseSave = false;
            typeof obj.fail === 'function' ? obj.fail.call(selector) : '';
        })
        .always(function(){
            mw.pauseSave = false;
        });
    return xhr;
};
