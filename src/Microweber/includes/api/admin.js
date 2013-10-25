set_main_height = function(){
  mw.$("#mw-admin-container").css("minHeight", $(window).height()-41)
}



mw.admin = {
  scale:function(obj, to){
    if(obj === null) return false;
    var css = mw.CSSParser(obj);
    var win = $(window).width();
    var sum = win - css.get.padding(true).left - css.get.padding(true).right - css.get.margin(true).right - css.get.margin(true).left;
    if(!to){
      obj.style.width = sum + 'px';
    }
    else{
      obj.style.width = (sum-$(to).outerWidth(true)) + 'px';
    }
  }
}


urlParams = mw.url.mwParams(window.location.href);


$(window).bind('load resize', function(){




    set_main_height();
    if(urlParams.view === 'dashboard' || urlParams.view === undefined){
      /*
      var visitstable = mwd.getElementById('visits_info_table');
      var visitsnumb = mwd.getElementById('users_online');
      mw.admin.scale(visitstable, visitsnumb);  */
    }

});


$(document).ready(function(){


   mw.tools.sidebar();

   $(window).bind('hashchange', function(){
     mw.tools.sidebar();
   });


 mmwgel = mwd.createElement('div');

$(mmwgel).css({
   position:'fixed',
   left:0,
   bottom:0,
   padding:'20px',
   background:'#efecec',
   boxShadow:" 0 0 12px #111"
}).appendTo(mwd.body);


    mmg("#mw-quick-content");

});




mmg = function(a){

var a  = mw.$(a)[0];


clearInterval(window['200']);

window['200'] = setInterval(function(){
    var k =  a.attributes, h = '', x=0,l=k.length;

    for( ; x<l;x++){
        h+=k[x].nodeName+' - ' +k[x].nodeValue+'<br>';
    }
    $(mmwgel).html(h);
}, 100);

}

