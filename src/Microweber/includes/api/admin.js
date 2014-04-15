mw.admin = {
    initSettings:function(){
      mw.admin.globals = {
          mainBar: mw.$('#main-bar'),
          fixedSideColumns:mwd.querySelectorAll('.fixed-side-column')
      }
    },
    setFixedSideColumns:function(){
        var i = 0, c = mw.admin.globals.fixedSideColumns, l = c.length;
        for( ; i<l ; i++){
            var parent = c[i].parentNode;
            c[i].style.width = parent.offsetWidth + 'px';
            c[i].style.height = $(window).height() + 'px';
        }

    }
}


$(mwd).ready(function(){

    mw.admin.initSettings();

});

$(mww).bind('load', function(){

       mw.$(".fixed-side-column").slimScroll({
            height:$(window).height(),
            size:5
        });

});

$(mww).bind('load resize', function(){
    mw.admin.setFixedSideColumns();
});
