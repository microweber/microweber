msie6 = !window.XMLHttpRequest; 

function css_browser_selector(u){var ua = u.toLowerCase(),is=function(t){return ua.indexOf(t)>-1;},g='gecko',w='webkit',s='safari',o='opera',h=document.documentElement,b=[(!(/opera|webtv/i.test(ua))&&/msie\s(\d)/.test(ua))?('ie ie'+RegExp.$1):is('firefox/2')?g+' ff2':is('firefox/3.5')?g+' ff3 ff3_5':is('firefox/3')?g+' ff3':is('gecko/')?g:is('opera')?o+(/version\/(\d+)/.test(ua)?' '+o+RegExp.$1:(/opera(\s|\/)(\d+)/.test(ua)?' '+o+RegExp.$2:'')):is('konqueror')?'konqueror':is('chrome')?w+' chrome':is('iron')?w+' iron':is('applewebkit/')?w+' '+s+(/version\/(\d+)/.test(ua)?' '+s+RegExp.$1:''):is('mozilla/')?g:'',is('j2me')?'mobile':is('iphone')?'iphone':is('ipod')?'ipod':is('mac')?'mac':is('darwin')?'mac':is('webtv')?'webtv':is('win')?'win':is('freebsd')?'freebsd':(is('x11')||is('linux'))?'linux':'','js']; c = b.join(' '); h.className += ' '+c; return c;}; css_browser_selector(navigator.userAgent);


var navLinks = document.getElementById('nav').getElementsByTagName('li');
for(var i=0; i<navLinks.length; i++){
   navLinks[i].id = 'nav-'+(i+1);
   var img = new Image();
   navLinks[i].getElementsByTagName('a')[0].innerHTML = '';
   if(msie6){
    img.src = imgurl + 'blank.gif';
    navLinks[i].getElementsByTagName('a')[0].appendChild(img);
   }
   else{
    img.src = imgurl + 'nav.png';
    navLinks[i].getElementsByTagName('a')[0].appendChild(img);
   }


}