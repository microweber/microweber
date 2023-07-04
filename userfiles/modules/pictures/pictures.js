mw.module_pictures = {
    after_upload: function (data, cb) {
        $.post(mw.settings.api_url + 'save_media', data,
            function (resp) {
                mw.reload_module_everywhere('pictures/admin_backend_sortable_pics_list');
                if(cb) {
                    cb.call()
                }
            }
        );
    },
    time:null,
    after_change: function (data) {
        var thumbs = mw.$('.admin-thumbs-holder .admin-thumb-item');
        if(!thumbs.length && mw._postsImageUploader) {
            mw._postsImageUploader.show();
        }
        clearTimeout(mw.module_pictures.time);
         mw.notification.success('Pictures settings are saved');
         mw.module_pictures.time = setTimeout(function () {
            var thumbs = mw.$('.admin-thumbs-holder .admin-thumb-item');
              if(!thumbs.length) {

                $('.mw-filepicker-root').show();
                if(mw._postsImageUploaderSmall) {
                    mw._postsImageUploaderSmall.$holder.hide();
                }
            } else {
                $('.mw-filepicker-root').hide();
                if(mw._postsImageUploaderSmall) {
                    mw._postsImageUploaderSmall.$holder.show();
                }
            }



            // mw.reload_module_everywhere('pictures', function (){
            //     if(this.mw.module_pictures) {
            //      //   this.mw.module_pictures.after_change();
            //     }
            // });
             mw.reload_module_everywhere('pictures');
             mw.reload_module_everywhere('posts');
            mw.reload_module_everywhere('shop/products');
            mw.reload_module_everywhere("pictures/admin");

            doselect();
        }, 1500)
    },

    save_options: function (id, image_options, cb) {
      image_options = image_options || {};
      if(typeof image_options === 'string'){
        image_options = JSON.parse(image_options);
      }
      var data = {};
      data.id = id;
      data.image_options = image_options;
      $.post(mw.settings.api_url + 'save_media', data, function (data) {
          mw.module_pictures.after_change();

          clearTimeout(mw.module_pictures.time);
          mw.module_pictures.time = setTimeout(function () {
            mw.reload_module_parent('pictures');
          }, 1500);
          if(cb) {
              cb.call(undefined, data);
          }
      });
    },
    save_title: function (id, title) {
        var data = {};
        data.id = id;
        data.title = title;
        $.post(mw.settings.api_url + 'save_media', data,
            function (data) {
                mw.module_pictures.after_change();

                mw.reload_module_everywhere('pictures');
            });
    },
    save_alt: function (id, alt) {
        var data = {};
        data.id = id;
        data.alt = alt;
        $.post(mw.settings.api_url + 'save_media', data,
            function (data) {
                mw.module_pictures.after_change();

                mw.reload_module_everywhere('pictures');
            });
    },
	save_tags: function (id, tags) {
        var data = {};
        data.id = id;
        data.tags = tags;
        $.post(mw.settings.api_url + 'save_media', data,
            function (data) {
                mw.module_pictures.after_change();

                mw.reload_module_everywhere('pictures');
                mw.reload_module_everywhere('tags');
            });
    },
    del: function (id) {
        if(typeof id === 'string'){
          if (confirm('Are you sure you want to delete this image?')) {

              $.post(mw.settings.api_url + 'delete_media', { id: id , csrf: $('meta[name="csrf-token"]').attr('content') }, function (data) {
                  $('.admin-thumb-item-' + id).fadeOut(function () {
                        $(this).remove();
                  });
                  setTimeout(function(){ $('[data-type="pictures/admin"]').trigger('change') }, 2000);

                  mw.module_pictures.after_change();
              });
          }
        }
        else{
          if (confirm('Are you sure you want to delete selected images?')) {
              $.post(mw.settings.api_url + 'delete_media', { ids: id, csrf: $('meta[name="csrf-token"]').attr('content')  }, function (data) {
                $.each(id, function(){
                  $('.admin-thumb-item-' + this).fadeOut(function () {
                      $(this).remove();
                  });
                })
                  setTimeout(function(){ $('[data-type="pictures/admin"]').trigger('change') }, 2000);

                  mw.module_pictures.after_change();
              });
          }
        }








    },
    time:null,
    init: function (selector) {
        var el = $(selector);
        $(".mw-post-media-img-edit input", el).not(':checkbox').on('input', function(){
          clearTimeout(mw.module_pictures.time)
          mw.module_pictures.time = setTimeout(function(){
            el.parents('[data-type="pictures/admin"]').trigger('change')
          }, 1500);
        });
        el.sortable({
            items: ".admin-thumb-item",
            placeholder:  'admin-thumb-item-placeholder' ,

            sort: function (e, ui) {
                $('.admin-thumb-item, .admin-thumb-item-placeholder, .admin-thumb-item-uploader-holder').each(function(){
                    $(this).height( $(this).width())
                })
                var plIndex = ui.placeholder.index();
                if (plIndex === 0 || (plIndex === 1 && ui.helper[0].id === mw.$('.admin-thumb-item:first', el)[0].id)) {
                    el.find('.admin-thumb-item-placeholder').addClass('admin-thumb-item-placeholder-first');
                } else {
                    el.find('.admin-thumb-item-placeholder').removeClass('admin-thumb-item-placeholder-first');
                }

            },
            update: function () {

                var serial = el.sortable('serialize');
                $.post(mw.settings.api_url + 'reorder_media', serial,
                    function (data) {
                        mw.module_pictures.after_change();
                        el.parents('[data-type="pictures/admin"]').trigger('change')
                    });

            }
        });
    },


    open_image_upload_settings_modal: function() {
        image_upload_settings__modal_opened = mw.dialog({
            content: '<div id="image_upload_settings__modal_module"></div>',
            title: 'Image upload settings',
            id: 'image_upload_settings__modal'
        });

        var params = {}
        params.show_description_text = 1;
        mw.load_module('settings/group/image_upload', '#image_upload_settings__modal_module', null, params);
    }




}
