<? 
 //p($pictures);
 ?>
 <? // print get_media_thumbnail(  $pictures[0]['id'] , '1200')  ?>
 
 
 
 
 
 
 
 	
       
        
 
  
 
 
 
 
 
 
<div class="rounded_box transparent" id="set_gallery_img_z"> 
 <a href='<? print get_media_thumbnail( $pictures[0]['id'] , '1200')  ?>' class='cloud-zoom jqzoom' id='zoom1'
            rel="adjustX: 10, adjustY:-4">
            <img src="<? print get_media_thumbnail( $pictures[0]['id'] , '400')  ?>" alt='' title="<? print addslashes($pictures[0]['media_name']) ?>" />
        </a>
  <div class="lt"></div>
  <div class="rt"></div>
  <div class="lb"></div>
  <div class="rb"></div>
</div>
<br/>
<div id="gallery">
  <? if(!empty($pictures)): ?>
  <?
  $i = 0;
  foreach($pictures as $pic): ?>
 
  <a class="ch_colors"  pic_number="<? print $i; ?>" tn_small="<? print get_media_thumbnail( $pic['id'] , 400)  ?>" tn_title="<? print addslashes($pic['media_name']) ?>"  tn_big="<? print get_media_thumbnail( $pic['id'] , '1200')  ?>" href="javascript:set_gallery_img('<? print get_media_thumbnail( $pic['id'] , 400)  ?>', '<? print get_media_thumbnail( $pic['id'] , '1200')  ?>', '<? print addslashes($pic['media_name']) ?>', <? print $i; ?>);"><img src="<? print get_media_thumbnail( $pic['id'] , 250)  ?>"  height="120" alt="" /></a>
  <? 
$i++;  endforeach ;  ?>
  <? endif; ?>
</div>
