 
 
  mw.quickSettings['btn'] =  [
          {
              title: 'Button settings',
              icon: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M17.5,12A1.5,1.5 0 0,1 16,10.5A1.5,1.5 0 0,1 17.5,9A1.5,1.5 0 0,1 19,10.5A1.5,1.5 0 0,1 17.5,12M14.5,8A1.5,1.5 0 0,1 13,6.5A1.5,1.5 0 0,1 14.5,5A1.5,1.5 0 0,1 16,6.5A1.5,1.5 0 0,1 14.5,8M9.5,8A1.5,1.5 0 0,1 8,6.5A1.5,1.5 0 0,1 9.5,5A1.5,1.5 0 0,1 11,6.5A1.5,1.5 0 0,1 9.5,8M6.5,12A1.5,1.5 0 0,1 5,10.5A1.5,1.5 0 0,1 6.5,9A1.5,1.5 0 0,1 8,10.5A1.5,1.5 0 0,1 6.5,12M12,3A9,9 0 0,0 3,12A9,9 0 0,0 12,21A1.5,1.5 0 0,0 13.5,19.5C13.5,19.11 13.35,18.76 13.11,18.5C12.88,18.23 12.73,17.88 12.73,17.5A1.5,1.5 0 0,1 14.23,16H16A5,5 0 0,0 21,11C21,6.58 16.97,3 12,3Z" /></svg>',
              action: (target, button, api) => {
 
                  var moduleId = $(target).attr('id');
 
                  // var settings = api.moduleSettings.dialog()
                  // var settings = api.moduleSettings.sidebar()
                  api.dialog({});
                  console.log(api)
 
                  return
 
 
 
                  mw.dialogIframe({
                      url: route('live_edit.modules.settings.btn')+'?id='+moduleId,
                      width: 300,
                      height: 500,
                      template:'mw_modal_simple',
                      title: 'Button settings',
                      id: 'btn-quick-setting-dialog-'+moduleId
                  });
 
 
              }
          },
          {
              title: 'Set background color',
              icon: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M17.5,12A1.5,1.5 0 0,1 16,10.5A1.5,1.5 0 0,1 17.5,9A1.5,1.5 0 0,1 19,10.5A1.5,1.5 0 0,1 17.5,12M14.5,8A1.5,1.5 0 0,1 13,6.5A1.5,1.5 0 0,1 14.5,5A1.5,1.5 0 0,1 16,6.5A1.5,1.5 0 0,1 14.5,8M9.5,8A1.5,1.5 0 0,1 8,6.5A1.5,1.5 0 0,1 9.5,5A1.5,1.5 0 0,1 11,6.5A1.5,1.5 0 0,1 9.5,8M6.5,12A1.5,1.5 0 0,1 5,10.5A1.5,1.5 0 0,1 6.5,9A1.5,1.5 0 0,1 8,10.5A1.5,1.5 0 0,1 6.5,12M12,3A9,9 0 0,0 3,12A9,9 0 0,0 12,21A1.5,1.5 0 0,0 13.5,19.5C13.5,19.11 13.35,18.76 13.11,18.5C12.88,18.23 12.73,17.88 12.73,17.5A1.5,1.5 0 0,1 14.23,16H16A5,5 0 0,0 21,11C21,6.58 16.97,3 12,3Z" /></svg>',
              action: (target, button, api) => {
                  var node = document.createElement('div');
 
                  console.log(api)
 
                  api.tooltip({
                      content: node,
                  });
                  mw.colorPicker({
                      element: node,
                      position: 'bottom-center',
                      value: 'red',
                      onchange: function (color) {
 
                      }
                  });
              }
          },
          {
              title: 'Set color',
              icon: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M17.5,12A1.5,1.5 0 0,1 16,10.5A1.5,1.5 0 0,1 17.5,9A1.5,1.5 0 0,1 19,10.5A1.5,1.5 0 0,1 17.5,12M14.5,8A1.5,1.5 0 0,1 13,6.5A1.5,1.5 0 0,1 14.5,5A1.5,1.5 0 0,1 16,6.5A1.5,1.5 0 0,1 14.5,8M9.5,8A1.5,1.5 0 0,1 8,6.5A1.5,1.5 0 0,1 9.5,5A1.5,1.5 0 0,1 11,6.5A1.5,1.5 0 0,1 9.5,8M6.5,12A1.5,1.5 0 0,1 5,10.5A1.5,1.5 0 0,1 6.5,9A1.5,1.5 0 0,1 8,10.5A1.5,1.5 0 0,1 6.5,12M12,3A9,9 0 0,0 3,12A9,9 0 0,0 12,21A1.5,1.5 0 0,0 13.5,19.5C13.5,19.11 13.35,18.76 13.11,18.5C12.88,18.23 12.73,17.88 12.73,17.5A1.5,1.5 0 0,1 14.23,16H16A5,5 0 0,0 21,11C21,6.58 16.97,3 12,3Z" /></svg>',
              action: (target, button, api) => {
                  var node = document.createElement('div');
 
 
                  api.dialog({
                      content: node,
                  });
                  mw.colorPicker({
                      element: node,
                      position: 'bottom-center',
                      value: 'red',
                      onchange: function (color) {
 
                      }
                  });
              }
          },
          {
              title: 'Link',
              icon: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M3.9,12C3.9,10.29 5.29,8.9 7,8.9H11V7H7A5,5 0 0,0 2,12A5,5 0 0,0 7,17H11V15.1H7C5.29,15.1 3.9,13.71 3.9,12M8,13H16V11H8V13M17,7H13V8.9H17C18.71,8.9 20.1,10.29 20.1,12C20.1,13.71 18.71,15.1 17,15.1H13V17H17A5,5 0 0,0 22,12A5,5 0 0,0 17,7Z" /></svg>',
              action: (target, button, api) => {
 
 
                  var linkEditor = new mw.LinkEditor({
                      mode: 'dialog',
                  });
                  var val = 'http://google.com'
                  if(val) {
                      linkEditor.setValue(val);
                  }
 
                  linkEditor.promise().then(function (data){
                      var modal = linkEditor.dialog;
                      if(data) {
 
 
                      }
                      modal.remove();
                  });
              }
          }
      ];

 

$(document).ready(function () {
    mw.on('live_edit.modules.settings.btn.changed', function (e,data) {
        if(data.moduleId ){
           mw.reload_module_everywhere('#'+data.moduleId);
        }
    });
});

 











/*
class MyBtnModule {
    name = 'BtnModule';
    constructor() {


    }

    init() {


    }
    ready() {

        var liveEdit = window.container.get('liveEdit');
         if(liveEdit.moduleHandle){

            liveEdit.moduleHandle.on('targetChange', function (target) {
                var handleContent = liveEdit.moduleHandle.getHandleContent();
                handleContent.addMenu(this.getMainMenu());
                liveEdit.floatinButtonsMenu.addMenu(this.getMainMenu());
                liveEdit.styleEditor.addMenu(this.getMainMenu());
              });


         }

        // handle.on('targetChange', function (target) {
        //     alert(444);
        // });
    }

    getMainMenu() {
        return myBtnMenu.mainMenu;
    }


    onElementOver(element) {

    }

}*/


//window.liveEditApp.register(new MyHandle());
//window.liveEditApp.register(new MyBtnModule());


