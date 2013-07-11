<?php

/*

type: layout

name: Autocomplete

description: Autocomplete Search template

*/

  ?>

<div class="mw-search mw-search-autocomplete " id="search_box_holder_<?php  print $params['id'] ?>">
  <div class="input-append">
    <input type="text"
         id="search_field_<?php  print $params['id'] ?>"
         class="input-large mw-search-field"
         placeholder="<?php _e("Search"); ?>"
         onkeyup="mw.autocompleteSearch(mwd.getElementById('search_box_holder_<?php  print $params['id'] ?>'), this, event);"
         onpaste="mw.autocompleteSearch(mwd.getElementById('search_box_holder_<?php  print $params['id'] ?>'), this, event);"
     />
    <span class="add-on"><i class="icon-search"></i></span> </div>
  <div class="mw-search-results" id="search_results_holder_<?php  print $params['id'] ?>"> </div>
</div>


<script>


mw.autocompleteSearch = function(parent, el, e){
    var parent = $(parent);
      if(e.type == 'keyup'){
         if(e.keyCode == 38){
            mw.acnav('up', parent);
          }
          else if(e.keyCode == 40){
             mw.acnav('down', parent);
          }
          else if(e.keyCode == 13){
               mw.acnav('enter', parent);
          }
          else{
              parent.addClass("loading");
              mw.on.stopWriting(el,function(){
                  if(el.value == ''){
                    $(mwd.getElementById('search_results_holder_<?php  print $params['id'] ?>')).hide();
                    parent.removeClass("loading");
                    return false;
                  }
                  $(mwd.getElementById('search_results_holder_<?php  print $params['id'] ?>')).show();
                    mw.search(el.value, mwd.getElementById('search_results_holder_<?php  print $params['id'] ?>'), {
                       template:'search',
                       done:function(){
                         parent.removeClass("loading");
                       }
                    });
              });
          }
      }
}

mw.acnav = function(a, parent){
   var lis = parent.find(".module-posts-template-search li");
   var active = parent.find(".module-posts-template-search li.active");
   if(a == 'up'){
    if(active.length > 0){
       if(active.prev().length > 0){
          active.removeClass("active");
          active.prev().addClass("active");
       }
       else{
          active.removeClass("active");
           lis.eq(lis.length - 1).addClass("active")
       }
    }
    else{
      lis.eq(lis.length - 1).addClass("active")
    }
   }
   else if(a == 'down'){
      if(active.length > 0){
         if(active.next().length > 0){
            active.removeClass("active");
            active.next().addClass("active");
         }
         else{
            active.removeClass("active");
             lis.eq(0).addClass("active")
         }
      }
      else{
        lis.eq(0).addClass("active")
      }
   }
   else if(a=='enter'){
      if(active.length > 0){
        window.location.href = active.find("a").attr("href");
      }
   }
}





</script>