mw.module_pictures = {
    after_upload: function (data) {
        $.post(mw.settings.api_url + 'save_media', data,
            function (data) {

            });

        $('#'+data.for_id).trigger('change').find('[data-type="pictures/admin"]').trigger('change') 
    },
    time:null,
    after_change: function (data) {
        clearTimeout(mw.module_pictures.time)
        mw.module_pictures.time = setTimeout(function () {
            mw.reload_module('pictures');
            mw.reload_module_parent('pictures');
            mw.reload_module_parent('posts');
            mw.reload_module_parent('shop/products');
            mw.reload_module_parent("pictures/admin");
        }, 1500)

    },

    save_options: function (id, image_options) {
      image_options = image_options || {};
      if(typeof image_options === 'string'){
        image_options = JSON.parse(image_options);
      }
      var data = {};
      data.id = id;
      data.image_options = image_options;
      $.post(mw.settings.api_url + 'save_media', data,
          function (data) {

              clearTimeout(mw.module_pictures.time)
              mw.module_pictures.time = setTimeout(function () {

            mw.reload_module_parent('pictures');

              }, 1500)


      });
    },
    save_title: function (id, title) {
        var data = {};
        data.id = id;
        data.title = title;
        $.post(mw.settings.api_url + 'save_media', data,
            function (data) {
                mw.reload_module_parent('pictures');
            });
    },
	save_tags: function (id, tags) {
        var data = {};
        data.id = id;
        data.tags = tags;
        $.post(mw.settings.api_url + 'save_media', data,
            function (data) {
                mw.reload_module_parent('pictures');
                mw.reload_module_parent('tags');
            });
    },
    del: function (id) {
        if(typeof id === 'string'){
          if (confirm('Are you sure you want to delete this image?')) {
              $.post(mw.settings.api_url + 'delete_media', { id: id  }, function (data) {
                  $('.admin-thumb-item-' + id).fadeOut();
                  mw.module_pictures.after_change()
              });
          }
        }
        else{
          if (confirm('Are you sure you want to delete selected images?')) {
              $.post(mw.settings.api_url + 'delete_media', { ids: id  }, function (data) {
                $.each(id, function(){
                  $('.admin-thumb-item-' + this).fadeOut(); 
                })

                  mw.module_pictures.after_change()
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
          }, 1500)
        })
        el.sortable({
            items: ".admin-thumb-item",
            placeholder: 'admin-thumb-item-placeholder',
            update: function () {
                var serial = el.sortable('serialize');
                $.post(mw.settings.api_url + 'reorder_media', serial,
                    function (data) {
                        mw.module_pictures.after_change()
                        el.parents('[data-type="pictures/admin"]').trigger('change')
                    });

            }
        });
    }
}
