# App


```js
mw.app.on('init',function(app){

});
mw.app.on('ready',function(app){
    
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
  
```



### Module Handle Events

```js

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

