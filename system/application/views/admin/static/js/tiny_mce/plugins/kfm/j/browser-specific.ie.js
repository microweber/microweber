
function clearSelections(){document.selection.empty();}
function getWindowScrollAt(){var d=document.body;if(d.scrollLeft||d.scrollTop){return{x:d.scrollLeft,y:d.scrollTop};}
d=document.documentElement;return{x:d.scrollLeft,y:d.scrollTop};}