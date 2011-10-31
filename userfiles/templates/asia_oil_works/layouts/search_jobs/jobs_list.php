
        
        <table cellpadding="0" cellspacing="0" id="results">
          <colgroup>
          <col width="178" />
          <col width="305" />
          <col width="" />
          </colgroup>
          <thead>
            <tr>
              <th>Job title</th>
              <th>Company</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody>
            <? foreach($posts as $post): ?>
            <tr>
              <td><a href="<? print post_link($post['id']); ?>">
                <?  print $post['content_title_nohtml'] ?>
                </a> <br />
                <? $cf =  get_custom_fields($post['id']) ?>
                <small>
                Location: <?  print $cf['location']['custom_field_value'] ?>
                </small>
                <br />  

 
 <a class="asitebtn" href="<? print post_link($post['id']); ?>">
<span>Apply to offer</span>
</a>
                
                
                </td>
              <? // p($cf )  ?>
              <td><?  $cfs = get_custom_fields_for_user($post['created_by']); ?>
                <a href="<? print site_url('recruiters');    ?>/id:<? print $post['created_by'] ?>">
                <? if(!$cfs["picture"]["custom_field_value"]) {
		  
		 $cfs["picture"]["custom_field_value"] =   TEMPLATE_URL.'no_logo.gif';
	  }?>
                <span style="background-image: url('<? print site_url('phpthumb/phpThumb.php') ?>?src=<? print $cfs["picture"]["custom_field_value"]  ?>&h=100&w=100'); height:100px; width:100px; background-repeat:no-repeat; display:block;" ></span> <b><? print user_name($post['created_by']) ?></b> </a></td>
              <td><?  print $post['created_on'] ?></td>
              
            </tr>
            <? endforeach; ?>
   
          </tbody>
        </table>