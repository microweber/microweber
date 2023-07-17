# App


```js
mw.app.on('init',function(app){

});
mw.app.on('ready',function(app){
    
});
```


# Canvas


```js
mw.app.canvas.on('canvasDocumentClick',function(){

});
mw.app.canvas.on('liveEditCanvasLoaded',function(frame){
    
});
 
mw.app.canvas.on('liveEditCanvasBeforeUnload',function(frame){
    
});
```


### Inserting Modules

```js

mw.app.editor.on('insertLayoutRequest',function(element){
 // mw.app.editor.insertModule('btn',options);
});
mw.app.editor.on('insertModuleRequest',function(element){

});
mw.app.editor.insertModule('btn',options);
mw.app.editor.insertModule('layouts',
    {
        skin:'my-skin',
    }
);

mw.app.editor.on('elementSettingsRequest',function(element){});
mw.app.editor.on('editNodeRequest',function(element){ });
  
```

### Module Handle Target

```js

var target = mw.top().app.liveEdit.moduleHandle.getTarget();
```

### Module Handle Events

```js
mw.app.editor.on('onModuleSettingsRequest',function(element){

});

mw.app.editor.on('onModuleHandleAppear',function(element,handle){

});
mw.app.editor.on('onElementHandleAppear',function(element,handle){

});
mw.app.editor.on('onLayoutHandleAppear',function(element,handle){

});
```


## Module Settings

```js
mw.app.moduleSettings.modal([
    {
        title:'My Settings',
        url:'...',
        content:'<div>My Settings</div>',
        onResize:function(){

        },
        onOpen:function(){

        }
    }
]);
```

## Tools
```js

mw.app.tools.modal([
    {
        title:'My Settings',
        url:'...',
        content:'<div>My Settings</div>',
        onResize:function(){

        },
        onOpen:function(){

        }
    }
]);


mw.app.tools.confirm([
    {
        title:'Confirm',
        content:'<div>My Settings</div>',
        onConfirm:function(){
            
        }
    }
]);

mw.app.tools.tooltip(element,[

]);
mw.app.tools.alert( 'Hello World!' );
mw.app.tools.prompt([
    {
        title:'Confirm',
        content:'<div>My Settings</div>',
        onConfirm:function(){

        },
        onCancel:function(){

        }
    }
]);
```


### Keyboard Shortcuts

```js
mw.app.keyboard.on('enter', function () {

});
mw.app.keyboard.on('esc', function () {

});
mw.app.keyboard.on('ctrl+s', function () {
  //  mw.app.editor.save()
});
mw.app.keyboard.on('ctrl+z', function () {
  //  mw.app.editor.undo()
});

mw.app.keyboard.on('ctrl+y', function () {
  //  mw.app.editor.redo()
});

```

### Selector

```js
// mw.app.editor.selector.on('onSelect',function(element){
//     mw.app.editor.handle.module.addButton();
// }); 
```
### Modules and Layouts

```js
// List all modules and layouts
mw.app.layouts.list().then(function (modules) {
    console.log(modules);
});
mw.app.modules.list().then(function (modules) {
    console.log(modules);
});

//Getting skins for layouts
mw.app.layouts.getSkins().then(function (skins) {
    console.log(skins);
});

//Getting skins for module
mw.app.modules.getSkins('btn').then(function (skins) {
    console.log(skins);
});

```


### Saving editor content

```js

```


### Add custom controls to module handle

```js

//Single menu item

const config = {
    icon: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M16,20H20V16H16M16,14H20V10H16M10,8H14V4H10M16,8H20V4H16M10,14H14V10H10M4,14H8V10H4M4,20H8V16H4M10,20H14V16H10M4,8H8V4H4V8Z" /></svg>',
    title: 'Click to config',
    onTarget: function (target) {
        console.log(target)
    },
    action: function (target) {
        console.log(target)
    }
}


mw.app.dynamicTargetMenus.addModuleQuickSetting('btn', config);


//Multiple menu items

mw.app.dynamicTargetMenus.addModuleQuickSetting('btn', [config, config]);

```
