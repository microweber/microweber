<?php

/*

type: layout

name: Default

description: Pictures List

*/

  ?>
 
 <? if(isarr($data )): ?>

<ul class="mw-pictures-list">
  <? foreach($data  as $item): ?>
  <li class="mw-pictures-list-item mw-pictures-list-item-<? print $item['id']; ?>"><img src="<? print $item['filename']; ?>" /></li>
  <? endforeach ; ?>
</ul>
 

<? else : ?>
Please click on settings to upload your pictures.
<? endif; ?>