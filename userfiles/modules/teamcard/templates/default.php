<?php

 d($data);

?>
<style>
.team-card-item {
height:100px;
width:100px;	
display:block;
background-size:cover;
}
</style>
<div class="team-card-holder">
  <?php
$count = 0;
foreach($data as $slide){
    $count++;
    ?>
  <div class="team-card-item" style="background-image: url('<?php print thumbnail($slide['file'],1000);?>');">
    <div class="team-card-item-name"> <span class="tctitle" ><?php print $slide['name'];?></span> <br>
      <span class="team-card-item-position"> <?php print $slide['role'];?></span> </div>
  </div>
     <?php } ?>
</div>
 
