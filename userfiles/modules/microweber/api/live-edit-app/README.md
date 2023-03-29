# App


```js
mw.app.on('init',function(app){

});
mw.app.on('ready',function(app){
    
});
```

### Selector

```js

mw.app.editor.selector.on('onSelect',function(element){
    mw.app.editor.handle.module.addButton();
}); 
```
 

### Module Handle 

```js

mw.app.editor.handle.module.on('onAppear',function(element,handle){

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
] );
```
