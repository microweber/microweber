mw.getExtradataFormData = function (data, call) {

    if (data.form_data_required) {
        if (!data.form_data_module_params) {
            data.form_data_module_params = {};
        }
        data.form_data_module_params._confirm = 1
    }


    if (data.form_data_required_params) {
        data.form_data_module_params = $.extend({}, data.form_data_required_params,data.form_data_module_params);
    }

    if (data.form_data_module) {
        mw.loadModuleData(data.form_data_module, function (moduledata) {
            call.call(undefined, moduledata);
        }, null, data.form_data_module_params);
    }
    else {
        call.call(undefined, data.form_data_required);
    }
}

mw.extradataForm = function (options, data, func) {
    if (options._success) {
        options.success = options._success;
        delete options._success;
    }
    mw.getExtradataFormData(data, function (extra_html) {
        var form = document.createElement('form');
        mw.$(form).append(extra_html);

        if(data.form_data_required){
            mw.$(form).append('<hr><button type="submit" class="mw-ui-btn pull-right mw-ui-btn-invert">' + mw.lang('Submit') + '</button>');
        }



        form.action = options.url;
        form.method = options.type;
        form.__modal = mw.dialog({
            content: form,
            title: data.error,
            closeButton: false,
            closeOnEscape: false
        });
        mw.$('script', form).each(function() {
            eval($(this).text());
        });

        $(form.__modal).on('closedByUser', function () {
            if(options.onExternalDataDialogClose) {
                options.onExternalDataDialogClose.call();
            }
        });



        if(data.form_data_required) {
            mw.$(form).on('submit', function (e) {
                e.preventDefault();
                var when = form.$beforepost ? form.$beforepost : function () {};
                var exdata = mw.serializeFields(this);
                $.when(when()).then(function() {
                    if(typeof options.data === 'string'){
                        var params = {};
                        options.data.split('&').forEach(function(a){
                            var c = a.split('=');
                            params[c[0]] = decodeURIComponent(c[1]);
                        });
                        options.data = params;
                    }
                    var isFormData = options.data.constructor.name === 'FormData';
                    if(isFormData) {
                        for (var i in exdata) {
                            options.data.set(i, exdata[i]);
                        }

                    } else {
                        for (var i in exdata) {
                            options.data[i] = exdata[i];
                        }
                    }
                    if(func) {
                        func(options);

                    } else {
                        mw.ajax(options);

                    }
                    form.__modal.remove();
                })



            });
        }
    });
};
