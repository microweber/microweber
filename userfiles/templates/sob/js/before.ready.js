function getElementsByClassName(classname, node)  {
    if(!node) node = document.getElementsByTagName("body")[0];
    var a = [];
    var re = new RegExp('\\b' + classname + '\\b');
    var els = node.getElementsByTagName("*");
    for(var i=0,j=els.length; i<j; i++)
        if(re.test(els[i].className))a.push(els[i]);
    return a;
}

var divs = document.getElementsByTagName('div');
for(var i=0; i<divs.length; i++){
  if(divs[i].className=='tab' || divs[i].className=='mw-tabs' || divs[i].className=='fragment-content'){
    divs[i].id = divs[i].id+'-tab';
  }
}

var hash = window.location.hash;

var links = document.getElementsByTagName('a');
for(var i=0; i<links.length; i++){
    if(links[i].href.indexOf(hash)!=-1){
        if(links[i].className=='toggle-fragment'){
          getElementsByClassName('toggle-fragment-active')[0].className='toggle-fragment';

           links[i].className += ' toggle-fragment-active';
           var hashID = hash.replace('#','');
           document.getElementById(hashID+'-tab').style.display='block';
        }
    }
}

