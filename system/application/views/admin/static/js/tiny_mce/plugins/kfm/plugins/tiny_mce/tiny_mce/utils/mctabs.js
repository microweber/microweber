
function MCTabs(){this.settings=[];};MCTabs.prototype.init=function(settings){this.settings=settings;};MCTabs.prototype.getParam=function(name,default_value){var value=null;value=(typeof(this.settings[name])=="undefined")?default_value:this.settings[name];if(value=="true"||value=="false")
return(value=="true");return value;};MCTabs.prototype.displayTab=function(tab_id,panel_id){var panelElm,panelContainerElm,tabElm,tabContainerElm,selectionClass,nodes,i;panelElm=document.getElementById(panel_id);panelContainerElm=panelElm?panelElm.parentNode:null;tabElm=document.getElementById(tab_id);tabContainerElm=tabElm?tabElm.parentNode:null;selectionClass=this.getParam('selection_class','current');if(tabElm&&tabContainerElm){nodes=tabContainerElm.childNodes;for(i=0;i<nodes.length;i++){if(nodes[i].nodeName=="LI")
nodes[i].className='';}
tabElm.className='current';}
if(panelElm&&panelContainerElm){nodes=panelContainerElm.childNodes;for(i=0;i<nodes.length;i++){if(nodes[i].nodeName=="DIV")
nodes[i].className='panel';}
panelElm.className='current';}};MCTabs.prototype.getAnchor=function(){var pos,url=document.location.href;if((pos=url.lastIndexOf('#'))!=-1)
return url.substring(pos+1);return"";};var mcTabs=new MCTabs();