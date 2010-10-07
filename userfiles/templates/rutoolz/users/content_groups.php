<div id="RU-subnav">
  <div class="pad3"></div>
  <div id="RU-nav">
    <h1>Your campaigns</h1>
      </div>


  <div id="RU-help"> <a class="help" title="Help" href="javascript:void(0)"></a>


  </div>
  <div class="clr"></div>

</div>
<div class="pad2"></div>
<div class="box-holder">
  <div class="box-top"></div>
  <div class="box-inside"> <a href="<? print site_url ( 'users/user_action:content-groups/id:0/add:yes' ) ?>" class="btn right">Add New Campaign</a>
    <h2>Campaign Manager </h2>
    <div class="c">&nbsp;</div>
   	 
    
    <? if(empty($groups)): ?>
    You havent created any campaigns yet. Please add some.
    <? else :  ?>
   
    <div id="campaign_searchbar">
      <div class="campaign_sort">
        <label>By date:</label>
        <select style="width: 100px;">
          <option>Newest</option>
          <option>Oldest</option>
        </select>
      </div>
      <form id="campaign_searchform" action="#" method="post">
        <input type="text" onblur="this.value==''?this.value='Search':''" onfocus="this.value=='Search'?this.value='':''" class="type-text" value="Search" name="search_by_keyword">
        <input type="submit" class="type-submit" value="">
      </form>
    </div>
    <div class="c" style="padding-bottom: 15px">&nbsp;</div>
    <? foreach($groups as $grp): ?>
    <table cellspacing="0" cellpadding="0" class="campaign-table">
      <colgroup>
      <col width="240" />
      <col width="120" />
      <col width="100" />
      <col width="75" />
      <col width="75" />
      <col width="75" />
      <col width="75" />
      </colgroup>
      <thead>
        <tr class="campaign-head">
          <th style="text-align: left;padding-left: 7px;"><span><?   print $grp['taxonomy_value'] ?></span></th>
          <th></th>
          <th></th>
          <th colspan="2" align="center"><a href="<? print site_url ( 'users/user_action:content-groups/id:' ) .$grp['id']?>" class="nextprev center">Edit</a></th>
          <th><a href="#" class="campaign-edit" style="background-position: -58px -17px">&nbsp;</a></th>
          <th><a href="#" class="remove" style="background-position:-77px -17px "></a></th>
        </tr>
      </thead>
      <tbody>
      <? $items = $this->content_model->taxonomyGetChildrenItems($grp['id'], $taxonomy_type = 'group_item', $orderby = false); ?>
        <? if(!empty($items)): ?>
	   <? foreach($items as $content_item):	 
	      $content_item_full  = array();
		  
		  if(!empty($content_item)){
	   $content_item_full = $this->content_model->contentGetByIdAndCache($content_item['to_table_id']) ;   } ?>
        <? if(!empty($content_item_full)): ?>
        <tr>
          <td style="padding-left: 7px;"><h3 class="mwboxtitle"> 
          <? // p( $content_item_full); ?>
          
          
          <a href="<? print $this->content_model->contentGetHrefForPostId($content_item_full['id']); ?>" target="_blank"><? print $content_item_full['content_title'] ?></a> </h3></td>
          <td><? ?></td>
          <td><? print $content_item_full['created_on'] ?></td>
          <td><!--<span class="statusN">&nbsp;</span>--></td>
          <td><a class="magnifier" target="_blank" href="<? print $this->content_model->contentGetHrefForPostId($content_item_full['id']); ?>">&nbsp;</a></td>
          <td><a class="campaign-edit" href="#">&nbsp;</a></td>
          <td><a class="revove-texted" href="javascript:usersPostDelete('#');">Remove</a></td>
        </tr>
        <? endif; ?>
        
     
        
       <? endforeach; ?>
       
       
       
       <? else: ?>
       
          <tr>
          <td style="padding-left: 7px;">This campaing is empty. </td>
          <td><a href="<? print site_url ( 'users/user_action:content-groups/id:' ) .$grp['id']?>">Edit it from here.</a></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        
        <? endif; ?>
      </tbody>
    </table>
    <div class="c" style="padding-bottom: 15px">&nbsp;</div>
     <? endforeach; ?>
    
    
   <!-- <table cellspacing="0" cellpadding="0" class="campaign-table">
      <colgroup>
      <col width="240" />
      <col width="120" />
      <col width="100" />
      <col width="75" />
      <col width="75" />
      <col width="75" />
      <col width="75" />
      </colgroup>
      <thead>
        <tr class="campaign-head">
          <th style="text-align: left;padding-left: 7px;"><span>Campain Name Here </span></th>
          <th>Statistic</th>
          <th>12.12.2023</th>
          <th colspan="2" align="center"><a href="#" class="nextprev center">Add pages</a></th>
          <th><a href="#" class="campaign-edit" style="background-position: -58px -17px">&nbsp;</a></th>
          <th><a href="#" class="remove" style="background-position:-77px -17px "></a></th>
        </tr>
      </thead>
      <tbody>
        <tr id="post-id-513">
          <td style="padding-left: 7px;"><h3 class="mwboxtitle"> <a href="http://192.168.0.197/ru_dev/page/my-cool-page">My cool page</a> </h3></td>
          <td>Capture</td>
          <td>2010-07-10</td>
          <td><span class="statusN">&nbsp;</span></td>
          <td><a class="magnifier" target="_blank" href="#">&nbsp;</a></td>
          <td><a class="campaign-edit" href="#">&nbsp;</a></td>
          <td><a class="revove-texted" href="javascript:usersPostDelete('#');">Remove</a></td>
        </tr>
      </tbody>
    </table>-->
    <div class="campaign-bot">
      <div class="c" style="padding-bottom: 5px">&nbsp;</div>
      <ul class="paging">
        <li class="isQuo"><a class="nextprev" title="Previous" href="#">Prev</a></li>
        <li>
          <ul class="paging-content">
            <li><a href="#" class="active">1</a></li>
          </ul>
        </li>
        <li class="isQuo"><a class="nextprev" title="Next" href="#">Next</a></li>
      </ul>
    </div>
    
    <?   endif;  ?>
    
    
    
    <div class="c" style="padding-bottom: 15px">&nbsp;</div>
  </div>
  <div class="box-bottom"></div>
</div>

<div class="pad2"></div>
