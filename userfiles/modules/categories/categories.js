(function (){

    var i18n = {
        categoryDeleted: 'Category deleted',
        deleteConfirm: 'Are you sure you want to delete this?'
    };

    var CategoryManageService = {
        delete: function (id, callback, onError) {

            return $.post(mw.settings.api_url + "category/delete/" + id, {}, function (data) {
                if (callback) {
                    callback.call(this, data);
                }
             }).fail(function(xhr, status, error) {
                if(onError) {
                    onError.call(xhr, status, error)
                }
            });
        }
    };

    var CategoryManageProvider = function (service) {
        this.service = service || this.CategoryManageService;
        var scope = this;
        this.delete = function (id, callback, onError) {
            mw.tools.confirm(i18n.deleteConfirm, function () {
                scope.service.delete(id, function (data) {
                    mw.notification.success(i18n.categoryDeleted);
                    mw.reload_module_everywhere('content/manager');
                    if (callback) {
                        callback.call(data, data);
                    }
                }, function (status, error) {
                    if (onError) {
                        onError.call(status, error);
                    }
                });
            });
        };

        this.addNew = function (options) {
            return this.edit(0, options);
        };

        this.edit = function (id, options) {
          if (typeof id === "undefined") {
              return;
          }
          options = options || {};
          options = mw.object.extend({
              mode: 'dialog'
          }, options);
          var elementContainer = mw.element({
              props: {
                  dataset: {
                      categoryId: id
                  }
              }
          });
          mw.spinner({
              element: elementContainer.get(0),
          });
          mw.load_module('categories/edit_category', elementContainer.get(0), function () {
              mw.spinner({
                  element: elementContainer.get(0),
              }).remove();
          });

          return new Promise(function (resolve) {
              if (options.mode === 'dialog') {
                  var dialog = mw.dialog({
                      content: elementContainer.get(0),
                      height: 'auto',
                      width: 900,
                      onResult: function (data) {
                          resolve(data);
                          dialog.remove()
                      }
                  });
              } else if (options.element) {
                  mw.element(options.element).append(elementContainer)
              }
          });
        };

    };

    var MicroweberManager = function (service, provider, defaultService, defaultProvider) {
        var scope = this;
        var Provider;
        this.setProvider = function (provider, service) {
            this.service = service || defaultService;
            Provider = provider || defaultProvider;
            this.provider = new Provider(service);
        };
        this.initProvider = function () {
            for (var i in this.provider) {
                scope[i] = scope.provider[i];
            }
        };
        this.init = function () {
            this.setProvider();
            this.initProvider();
        };
        this.providerExtend = function (data) {
            for (var i in data) {
                this.provider[i] = data[i];
            }
            this.initProvider();
        };

        this.setService = function (service) {
            this.setProvider(Provider, service);
        };
        this.serviceExtend = function (data) {
            for (var i in data) {
                this.service[i] = data[i];
            }
        };
        this.init();
    };

    var microweberManager = function (data) {
        return new MicroweberManager(data.service, data.provider, data.defaultService, data.defaultProvider);
    };

    var CategoryManager = function (service, provider) {
        return microweberManager({
            service: service,
            provider: provider,
            defaultService: CategoryManageService,
            defaultProvider: CategoryManageProvider,
        });
    };


    mw.CategoryManager = CategoryManager;

})();

/*
*
*   var catManage = new mw.content.category();

    catManage.delete(12);

    catManage.setProvider(someCustomProvider);

    catManage.providerExtend({
        delete: function() { // my custom delete only for catManage }
    })

    catManage.serviceExtend({
        delete: function() { // my custom delete only for catManage }
    })
*
* */
