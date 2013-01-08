<?php

/*

type: layout

name: Slider

description: Pictures slider

*/

  ?>


 <? if(isarr($data )): ?>

 <?php $id = "slider-".uniqid(); ?>
<div id="<?php print $id; ?>">
  <div class="mw-gallery-holder">
    <? foreach($data  as $item): ?>
    <div class="mw-gallery-item mw-gallery-item-<? print $item['id']; ?>" style="background-image:url(<? print $item['filename']; ?>);"></div>
    <? endforeach ; ?>
  </div>
</div>

<style type="text/css">
#<?php print $id; ?>{
  width:100%;
  height: 300px;
  overflow: hidden;
}
#<?php print $id; ?> .mw-gallery-item{
  background-repeat: no-repeat;
  background-position: center;
  background-size:100% auto;
}

</style>


<script>

if(!mw.rotator){
    mw.rotator = function(selector){

        /*

            Simple JS API for CSS3 Transitions

            The Transition styles are for the slider-item with class 'active'

        */
        var rotator = $(selector)[0];
        var holder = rotator.getElementsByTagName('div')[0];
        if(holder === undefined) return false;
        var controlls = mwd.createElement('div');
        controlls.className = "mw-rotator-ctrls unselectable";
        rotator.appendChild(controlls);


        var items = holder.getElementsByTagName('div');
        $(items).addClass('mw-rotator-slide');
        $(rotator).hover(function(){
            $(this).addClass('rotator-hover');
        }, function(){
            $(this).removeClass('rotator-hover');
        });
        rotator.next = function(){
            var active = $(holder).children('.active');
            var next = active.next().length>0 ? active.next() : $(holder).children().eq(0);
            active.removeClass('active');
            next.addClass('active');
            rotator.setactive($(holder).children().index(next));
        },
        rotator.prev = function(){
            var active = $(holder).children('.active');
            var prev = active.prev().length>0 ? active.prev() : $(holder).children(':last');
            active.removeClass('active');
            prev.addClass('active');
            rotator.setactive($(holder).children().index(prev));
        },
        rotator.setactive = function(i){ //for paging
           var paging = $(rotator).find('.rotator-index-control').eq(0);
           if(paging.length === 0) return false;
           paging.find("a").removeClass('active');
           paging.find("a").eq(i).addClass('active');
        }
        rotator.goto = function(index){
          var _index = $(holder).children().eq(index);
          if(!_index.hasClass('active')){
           $(holder).children('.active').removeClass('active');
           _index.addClass('active');
           rotator.setactive(index);
          }
        }
        rotator.paging = function(selector){
          var l = items.length, i = 0, paging_holder = $(selector);
          paging_holder.empty();
          for( ; i<l; i++){
            var a = mwd.createElement('a');
            a.onclick = function(){rotator.goto(parseFloat(this.innerHTML)-1)}
            a.innerHTML = i + 1;
            paging_holder[0].appendChild(a);
          }
        }
        rotator.controlls = function(obj){
            var obj = obj || {};
            var paging = obj.paging;
            var next = obj.next;
            var prev = obj.prev;
            if(paging){
              var paging_holder = mwd.createElement('span');
              paging_holder.className = 'rotator-index-control';
              controlls.appendChild(paging_holder);
              rotator.paging(paging_holder)
            }
            if(next){
                var next = mwd.createElement('span');
                next.className = 'rotator-next';
                controlls.appendChild(next);
                next.onclick = function(){
                    rotator.next()
                }
            }
            if(prev){
               var prev = mwd.createElement('span');
               prev.className = 'rotator-prev';
               controlls.appendChild(prev);
               prev.onclick = function(){
                    rotator.prev()
                }
            }
            return rotator;
        }
        rotator.autoRotate = function(int){
          setTimeout(function(){
            !$(rotator).hasClass('rotator-hover') ? rotator.next() : '';
             rotator.autoRotate(int);
          }, int);
          return rotator;
        }
        if($(holder).children('.active').size()===0){
           rotator.goto(0);
        }
        return rotator;
    }
}


</script>

<script type="text/javascript">

  Rotator = null;

  $(document).ready(function(){

      Rotator = mw.rotator('#<?php print $id; ?>');

      if (!Rotator) return false;
      Rotator.controlls({
          paging:true,
          next:true,
          prev:true
      });
      Rotator.autoRotate(3000);
  });
</script>
 

<? else : ?>
Please click on settings to upload your pictures.
<? endif; ?>