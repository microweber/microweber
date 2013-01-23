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
  min-height: 300px;
  overflow: hidden;
}
#<?php print $id; ?> .mw-gallery-item{
  background-repeat: no-repeat;
  background-position: center;
  background-size:100% auto;
  width:100%;
  min-height:300px;
  
}
#<?php print $id; ?> .mw-gallery-holder{
  overflow: hidden;
  position: relative;
    width:100%;
  min-height: 300px;
}

#<?php print $id; ?> .mw-rotator-slide{
  position: absolute;
  width: 100%;
  height: 100%;
  top:0;
  left: 0;
  opacity:0;
  transform: scale(1.15);
  -moz-transform: scale(1.15);
  -webkit-transform: scale(1.15);
  -o-transform: scale(1.15);
  transition: all 0.6s linear;
  -webkit-transition: all 0.6s linear;
  -moz-transition: all 0.6s linear;
  -o-transition: all 0.6s linear;
}
#<?php print $id; ?> .mw-rotator-slide.active{
  opacity:1;
  -transform: scale(1);
  -moz-transform: scale(1);
  -webkit-transform: scale(1);
  -o-transform: scale(1);
}

.rotator-next,
.rotator-prev{
  position: absolute;
  top: 50%;
  right: 45px;
  margin-top: -15px;
  display: block;
  width: 30px;
  height: 40px;
  cursor: pointer;
  background: url(ico.png) no-repeat 0 -38px;
  visibility: hidden;
}

.rotator-index-control{
  position: absolute;
  visibility: hidden;
  bottom:20px;
  right: 20px;
  white-space: nowrap;
}
.rotator-index-control a{
  display: inline-block;
  cursor: pointer;
  color: white;
  text-shadow:0 0 1px #000;
  padding: 1px 4px;
  margin: 0 3px;
  border-radius: 3px;

}
.rotator-index-control a.active{
  background:white;
  color:#353535;
  text-shadow:0 0 0px #000;
  box-shadow:0 0 2px #000;
}


.rotator-prev{
  background-position: -30px -38px;
  left: 45px;
}


#<?php print $id; ?>:hover .rotator-next,
#<?php print $id; ?>:hover .rotator-index-control,
#<?php print $id; ?>:hover .rotator-prev{
  visibility: visible;
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