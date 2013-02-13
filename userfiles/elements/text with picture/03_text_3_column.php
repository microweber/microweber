<?php $rand = uniqid();  ?>

<div class="mw-row" id='<?php print $rand; ?>'>
  <div class="mw-col" style="width:33.33%" >
      <div style="padding:12px 12px 12px 0" >
           <div class="thumbnail">
               <div>
                  <img width="100%" src="<?php print pixum(290, 150); ?>" alt="" />
                  <div class="caption">
                    <h2 class="element layout-title lipsum">Three Coloms with Pictures</h2>
                    <p class="element lipsum layout-paragraph"><?php print lipsum(); ?></p>
                  </div>
               </div>
           </div>
      </div>
  </div>

   <div class="mw-col" style="width:33.33%" >
      <div style="padding:12px;" >
           <div class="thumbnail">
               <div>
                  <img width="100%" src="<?php print pixum(290, 150); ?>" alt="" />
                  <div class="caption">
                    <h2 class="element layout-title lipsum">Three Coloms with Pictures</h2>
                    <p class="element lipsum layout-paragraph"><?php print lipsum(); ?></p>
                  </div>
               </div>
           </div>
      </div>
  </div>


   <div class="mw-col" style="width:33.33%" >
      <div style="padding:12px 0 12px 12px;" >
           <div class="thumbnail">
             <div>
                <img width="100%" src="<?php print pixum(290, 150); ?>" alt="" />
                <div class="caption">
                  <h2 class="element layout-title lipsum">Three Coloms with Pictures</h2>
                  <p class="element lipsum layout-paragraph"><?php print lipsum(); ?></p>
                </div>
             </div>
           </div>
      </div>
  </div>
</div>


<script>
    mw.disable_selection("#<?php print $rand; ?> img");
</script>



