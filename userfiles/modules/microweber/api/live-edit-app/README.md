# App


```js
mw.app.on('init',function(app){

});
mw.app.on('ready',function(app){
    
});
```



# Editor

```js
mw.app.editor.on('ready',function(app){

});
```



## Editor Handles

### Module Handle 

```js
mw.app.editor.handle.module.on('ready',function(element,handle){

});
mw.app.editor.handle.module.on('onHandleAppear',function(element,handle){

});
mw.app.editor.handle.module.on('onClick',function(element,handle){

});
mw.app.editor.handle.module.on('onDoubleClick',function(element,handle){

});
mw.app.editor.handle.module.on('onRightClick',function(element,handle){

});
mw.app.editor.handle.module.on('onBlur',function(element,handle){

});
```


### Element Handles

```js
mw.app.editor.handle.element.on('ready',function(app){

});
mw.app.editor.handle.element.on('onAppear',function(element,handle){

});
mw.app.editor.handle.element.on('onClick',function(element,handle){

});
mw.app.editor.element.on('onBlur',function(element,handle){

});
mw.app.editor.handle.element.on('onSelect',function(element,handle){

});
mw.app.editor.handle.element.on('onDeselect',function(element,handle){

});
mw.app.editor.handle.element.on('onMouseout',function(element,handle){

});
mw.app.editor.handle.element.on('onMouseover',function(element,handle){

});
mw.app.editor.handle.element.on('onMouseenter',function(element,handle){

});
```



## Richtext Editor Commands

```js
mw.app.editor.richtext.on('ready',function(app){

});
mw.app.editor.richtext.getSelection();
mw.app.editor.richtext.saveSelection();
mw.app.editor.richtext.restoreSelection();
mw.app.editor.richtext.getElement();
mw.app.editor.richtext.getBlockElement();

mw.app.editor.richtext.removeFormat();
mw.app.editor.richtext.cleanHTML();

mw.app.editor.richtext.setFontFamily('Tahoma');
mw.app.editor.richtext.setBold(true);
mw.app.editor.richtext.setBold(false);
mw.app.editor.richtext.setItalic(true);
mw.app.editor.richtext.setItalic(false);
mw.app.editor.richtext.setFontSize();
mw.app.editor.richtext.setAlign();
mw.app.editor.richtext.setFormat('h1');
mw.app.editor.richtext.setFormat('h2');
mw.app.editor.richtext.addClass('my-class');
mw.app.editor.richtext.removeClass('my-class');
mw.app.editor.richtext.toggleClass('my-class');
mw.app.editor.richtext.setLink('http://google.com');

```


## CSS Editor Commands

```js
mw.app.editor.cssEditor.on('ready',function(app){

});

mw.app.editor.cssEditor.setStyle('background','red');
mw.app.editor.cssEditor.setStyle('fontFamily','Arial');
mw.app.editor.cssEditor.getStyle('fontFamily');
mw.app.editor.cssEditor.getStyles();
mw.app.editor.cssEditor.clearStyle('background');
mw.app.editor.cssEditor.clearStyles();
mw.app.editor.cssEditor.saveCss();
mw.app.editor.cssEditor.reloadStyles();

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
