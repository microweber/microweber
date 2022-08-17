(function(){
    var systemDialogs = {
        moduleFrame: function(type, params, autoHeight){
            if(typeof autoHeight === 'undefined') {
                autoHeight = true;
            }
            params = params || {};
            if(!type) return;

            var frame = document.createElement('iframe');
            frame.className = 'mw-editor-frame';
            frame.allow = 'accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture';
            frame.allowFullscreen = true;
            frame.scrolling = "auto";
            frame.width = "100%";
            frame.frameBorder = "0";
            frame.src = mw.external_tool('module') + '?type=' + type + '&params=' + $.param(params).split('&').join(',');
            if(autoHeight) {
                mw.tools.iframeAutoHeight(frame)
            }
            return frame;
        },
          confirm_reset_module_by_id: function (module_id, cb) {
              mw.tools.confirm(mw.lang('Are you sure you want to reset this module') + '?', function () {

                      var is_a_preset = mw.$('#'+module_id).attr('data-module-original-id');
                      var is_a_preset_attrs = mw.$('#'+module_id).attr('data-module-original-attrs');
                      if(is_a_preset){
                          var orig_attrs_decoded = JSON.parse(window.atob(is_a_preset_attrs));
                          if (orig_attrs_decoded) {
                              mw.$('#'+module_id).removeAttr('data-module-original-id');
                              mw.$('#'+module_id).removeAttr('data-module-original-attrs');
                              mw.$('#'+module_id).attr(orig_attrs_decoded).reload_module();

                              if(  mw.top().win.module_settings_modal_reference_preset_editor_thismodal ){
                                  mw.top().win.module_settings_modal_reference_preset_editor_thismodal.remove();
                              }
                          }
                          return;
                      }

                      var data = {};
                      data.modules_ids = [module_id];

                      var childs_arr = [];
                      mw.$('#'+module_id).andSelf().find('.edit').each(function (i) {
                          var some_child = {};
                          mw.tools.removeClass(this, 'changed')
                          some_child.rel = mw.$(this).attr('rel');
                          some_child.field = mw.$(this).attr('field');
                          childs_arr.push(some_child);
                      });


                      mw.$('#'+module_id).andSelf().find('.module').each(function (i) {

                          var some_child = mw.$(this).attr('id');

                          data.modules_ids.push(some_child);

                      });

                      window.mw.on.DOMChangePause = true;
                      var done = 0, alldone = 1;

                      if (childs_arr.length) {
                          alldone++;
                          $.ajax({
                              type: "POST",
                              // dataType: "json",
                              //processData: false,
                              url: mw.settings.api_url + "content/reset_edit",
                              data: {reset:childs_arr}
                              //  success: success,
                              //  dataType: dataType
                          }).always(function (){
                              done++;
                              if(done === alldone) {
                                  if(cb){
                                      cb.call()
                                  }
                              }
                          });
                      }


                      //data-module-original-attrs

                      $.ajax({
                          type: "POST",
                          // dataType: "json",
                          //processData: false,
                          url: mw.settings.api_url + "content/reset_modules_settings",
                          data: data,
                          success: function(){

                              setTimeout(function () {


                                  mw.$('#'+module_id).removeAttr('data-module-original-id');
                                  mw.reload_module('#'+module_id);
                                  window.mw.on.DOMChangePause = false;

                              }, 1000);
                              done++;
                              if(done === alldone) {
                                  if(cb){
                                      cb.call()
                                  }
                              }

                          },
                      });


              });


    },
    open_reset_content_editor: function (root_element_id) {

        var src = mw.settings.site_url + 'api/module?id=mw_global_reset_content_editor&live_edit=true&module_settings=true&type=editor/reset_content&autosize=true';

        if(typeof(root_element_id) != 'undefined') {
            var src = src + '&root_element_id='+root_element_id;
        }

        // mw.dialogIframe({
        var modal = mw.dialogIframe({
            url: src,
            // width: 500,
            // height: mw.$(window).height() - (2.5 * mw.tools.TemplateSettingsModalDefaults.top),
            name: 'mw-reset-content-editor-front',
            title: 'Reset content',
            template: 'default',
            center: false,
            resize: true,
            autosize: true,
            autoHeight: true,
            draggable: true
        });
    },
    open_global_module_settings_modal: function (module_type, module_id, modalOptions, additional_params) {


        var params = {};
        params.id = module_id;
        params.live_edit = true;
        params.module_settings = true;
        params.type = module_type;
        params.autosize = false;

        var params_url = $.extend({}, params, additional_params);

        var src = mw.settings.site_url + "api/module?" + json2url(params_url);


        modalOptions = modalOptions || {};

        var defaultOpts = {
            url: src,
            // width: 500,
            height: 'auto',
            autoHeight: true,
            name: 'mw-module-settings-editor-front',
            title: 'Settings',
            template: 'default',
            center: false,
            resize: true,
            draggable: true
        };

        var settings = $.extend({}, defaultOpts, modalOptions);

        // return mw.dialogIframe(settings);
        return mw.top().dialogIframe(settings);
    },
    open_module_modal: function (module_type, params, modalOptions) {

        var id = mw.id('module-modal-');
        var id_content = id + '-content';
        modalOptions = modalOptions || {};

        var settings = $.extend({}, {
            content: '<div class="module-modal-content" id="' + id_content + '"></div>',
            id: id
        }, modalOptions, {skin: 'default'});

        var xhr = false;
        var openiframe = false;
        if (typeof (settings.iframe) != 'undefined' && settings.iframe) {
            openiframe = true;
        }
        if (openiframe) {

            var additional_params = {};
            additional_params.type = module_type;
            var params_url = $.extend({}, params, additional_params);
            var src = mw.settings.site_url + "api/module?" + json2url(params_url);


            var settings = {
                url: src,
                name: 'mw-module-settings-editor-front',
                title: 'Settings',
                center: false,
                resize: true,
                draggable: true,
                height:'auto',
                autoHeight: true
            };
            return mw.top().dialogIframe(settings);

        } else {
            delete settings.skin;
            delete settings.template;
            settings.height = 'auto';
            settings.autoHeight = true;
            settings.encapsulate = false;
            var modal = mw.dialog(settings);
            xhr = mw.load_module(module_type, '#' + id_content, function(){
                setTimeout(function(){
                    modal.center();
                },333)
            }, params);
        }


        return {
            xhr: xhr,
            modal: modal,
        }
    }
    };

    for (var i in systemDialogs) {
        mw.tools[i] = systemDialogs[i];
    }
})()
