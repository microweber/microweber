PRETTY JSON 
== 

Simple library to render/format a JS obj to an HTML view.

Live Demo 
--
This is an [live demo](http://warfares.github.com/pretty-json/)

Dependecies
--
* Backbone (just code structure) / Underscore 
* JQuery (DOM manipulation)

Usage
--
<pre>

//obj to render.
var obj = {
  name:'John Doe',
  age: 20,
  children:[{name:'Jack', age:5}, {name:'Ann', age:8}],
  wife:{name:'Jane Doe', age:28 }
}

var node = new PrettyJSON.view.Node({
  el:$('#elem'),
  data:obj
});
</pre>


Methods
--
Node
<br/>
<b>expandAll</b>: Recursive open & render all nodes. (lazy render: a node will render only when is expanded)
<br/>
<b>collapseAll</b>: Close (Hide) all nodes.

Events
--
Node
<br/>
<b>collaṕse</b>: trigger when a node is show or hide. (parm. event) 
<br/>
<b>mouseover</b>: trigger when mouse over a node. (parm. node path)
<br/>
<b>mouseout</b>: trigger when mouse out the node

* Note: "node" is an Obj or an Array.
* Note : only tested in Chrome & FireFox.
