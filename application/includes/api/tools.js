mw.simpletabs = function(context){
    var context = context || document.body;
    $(".mw_simple_tabs_nav", context).each(function(){
      var parent = $(this).parents(".mw_simple_tabs").eq(0);
      parent.find(".tab").addClass("semi_hidden");
      parent.find(".tab").eq(0).removeClass("semi_hidden");
      $(this).find("a").eq(0).addClass("active");
      $(this).find("a").click(function(){
          var parent = $(this).parents(".mw_simple_tabs_nav").eq(0);
          var parent_master =  $(this).parents(".mw_simple_tabs").eq(0);
          parent.find("a").removeClass("active");
          $(this).addClass("active");
          parent_master.find(".tab").addClass("semi_hidden");
          var index = parent.find("a").index(this);
          parent_master.find(".tab").eq(index).removeClass("semi_hidden");
          return false;
      });
    });
}