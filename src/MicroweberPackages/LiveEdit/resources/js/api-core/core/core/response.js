mw.response = function(form, data, messages_at_the_bottom){
    messages_at_the_bottom = messages_at_the_bottom || false;
    if(data == null  ||  typeof data == 'undefined' ){
        return false;
    }

    data = mw.tools.toJSON(data);
    if(typeof data === 'undefined'){
        return false;
    }

    if(typeof data.error !== 'undefined'){
        mw._response.error(form, data, messages_at_the_bottom);
        return false;
    }
    else if(typeof data.success !== 'undefined'){
        mw._response.success(form, data, messages_at_the_bottom);
        return true;
    }
    else if(typeof data.warning !== 'undefined'){
        mw._response.warning(form, data, messages_at_the_bottom);
        return false;
    }
    else{
        return false;
    }
};

mw._response = {
    error:function(form, data, _msg){
        form = mw.$(form);
        var err_holder = mw._response.msgHolder(form, 'error');
        var msg = typeof data.message !== 'undefined' ? data.message : data.error;
        mw._response.createHTML(msg, err_holder);
    },
    success:function(form, data, _msg){
        form = mw.$(form);
        var err_holder = mw._response.msgHolder(form, 'success');
        var msg = typeof data.message !== 'undefined' ? data.message : data.success;
        mw._response.createHTML(msg, err_holder);
    },
    warning:function(form, data, _msg){
        form = mw.$(form);
        var err_holder = mw._response.msgHolder(form, 'warning');
        var msg = typeof data.message !== 'undefined' ? data.message : data.warning;
        mw._response.createHTML(msg, err_holder);
    },
    msgHolder : function(form, type, method){
        method = method || 'append';
        var err_holder = form.find(".mw-checkout-response:first");
        var err_holder2 = form.find(".alert:first");
        if(err_holder.length === 0){
            err_holder = err_holder2;
        }
        if(err_holder.length === 0){
            err_holder = document.createElement('div');
            form[method](err_holder);
        }

        var bootrap_error_type = 'default';
        if(type === 'error'){
            bootrap_error_type = 'danger';
        } else if(type === 'done'){
            bootrap_error_type = 'info';
        }


        $(err_holder).empty().attr("class", 'alert alert-' + type + ' alert-' + bootrap_error_type + ' ');
        return err_holder;
    },
    createHTML:function(data, holder){
        var i, html = "";


        if(typeof data === 'string'){
            html+= data.toString();
        }
        else{
            for( i in data){
                if(typeof data[i] === 'string'){
                    html+='<li>'+data[i]+'</li>';
                }
                else if(typeof data[i] === 'object'){
                    $.each(data[i], function(){
                        html+='<li>'+this+'</li>';
                    })
                }
            }
        }
        mw.$(holder).eq(0).append('<ul class="mw-error-list">'+html+'</ul>');
        mw.$(holder).eq(0).show();
    }
}
