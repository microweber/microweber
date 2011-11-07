<?php if(!empty($dbdata['pages'])) : ?>
<script type="text/javascript">
  $(document).ready(function(){
    
	
	$('#the_content_items_container').sortable({
					opacity: '0.5',
					update: function(e, ui){
						serial = $(this).sortable("serialize");
						$.ajax({
							url: "<?php print site_url('admin/content/posts_sort_by_date')  ?>",
							type: "POST",
							data: serial,
							// complete: function(){},
							success: function(feedback){
							//alert(feedback);
								//$('#data').html(feedback);
							}
							// error: function(){}
						});
					}
				});

	
	
  });
  </script>

<div id="message_area">
  <div id="message_wrapper">
    <div class="help_message" id="message"> Pages are the main structure units of your website. They offer huge flexibility options and you can connect them to categories, modules, PHP files or just use them as a static pages. </div>
    <!-- /message -->
  </div>
  <!-- /message_wrapper -->
</div>
<div id="main-content">
  <div style="border: 0pt none ;" id="breadcrumb_wrap">
    <div class="title_left_bar"> Pages tree </div>
    <div class="title_content">
      <div class="show_on_page"> <span>Show on page</span>
        <ul>
          <li class="n10"><a href="javascript:;"></a></li>
          <li class="n15"><a href="javascript:;"></a></li>
          <li class="n25"><a href="javascript:;"></a></li>
        </ul>
      </div>
      Content </div>
  </div>
  <div style="clear: both;height: 1px;overflow: hidden">
    <!--  -->
  </div>
  <table border="0" width="100%" style="padding-left: 74px">
    <tr valign="top" style="vertical-align:top">
      <td width="300" style="vertical-align:top"><a onclick="tree.showall('#c311')" class="btn" href="javascript:;">Open All</a> <a onclick="tree.hideall('#c311')" class="btn" href="javascript:;">Hide All</a>
        <?php // var_dump($active_categories); ?>
        <?php $link = site_url('admin/content/pages_edit/id:'). '{id}';
	 $link = "<a {active_code} href='$link' name='{id}'>{content_title}</a>";
	 $tree_params = array();
	 $tree_params['link'] = $link;
	 $tree_params_string = $this->core_model->securityEncryptArray($tree_params);
	 ?>
        <div class="ooyes_ul_pages_tree_container" id="c311" style="float: left;clear: both" treeparams='<?php print $tree_params_string;  ?>'></div>
        <?php $tree_params_string = false;  ?>
        <br />
        <br />
        <div class="c"></div>
        <br />
        <img src="<?php print_the_static_files_url() ; ?>icons/mouse.png"  border="0" alt="Right click for actions" align="left" />&nbsp;Right click on the pages tree to edit or create subpages. <br />
        <br />
        <div style="overflow: hidden; clear: both; height: 35px;">
          <!--  -->
        </div>
        <a class="bgb" href="<?php print site_url('admin/content/pages_edit/id:0') ?>">Create new page</a></td>
      <td><table border="0" class="tables" cellspacing="0" cellpadding="0">
          <thead>
            <tr>
              <th scope="col">ID</th>
              <!-- <th scope="col">Visit</th>-->
              <th scope="col">Title</th>
              <th scope="col">URL</th>
              <th scope="col">Filename</th>
              <!--<th scope="col">is_active</th>-->
              <th scope="col">Last update</th>
              <th scope="col"></th>
              <th scope="col">Edit</th>
              <th scope="col">Delete</th>
            </tr>
          </thead>
          <tbody id="the_content_items_container">
            <?php foreach($dbdata['pages'] as $item): ?>
            <tr id="content_row_id_<?php print $item['id']; ?>">
              <td><?php print($item['id']) ; ?></td>
              <!--<td><?php print($item['id']) ; ?></td>-->
              <td><?php print($item['content_title']) ; ?>
                <?php if($item['is_home'] == 'y') : ?>
                <img src="<?php print_the_static_files_url() ; ?>icons/home.png"  border="0" alt=" " />
                <?php endif;  ?>
                <?php if($item['content_subtype'] == 'module') : ?>
                <img src="<?php print_the_static_files_url() ; ?>icons/puzzle.png"  border="0" alt="<?php print addslashes($item['content_subtype_value']); ?>" />
                <?php endif;  ?>
                <?php if($item['content_subtype'] == 'dynamic') : ?>
                <img src="<?php print_the_static_files_url() ; ?>icons/blog.png"  border="0" alt="<?php print addslashes($item['content_subtype_value']); ?>" />
                <?php endif;  ?></td>
              <td><a href="<?php print site_url($item['content_url']) ; ?>" target="_blank"><?php print($item['content_url']) ; ?></a></td>
              <td><?php print($item['content_filename']) ; ?></td>
             <!-- <td><?php print($item['is_active']) ; ?></td>-->
              <td><?php print($item['updated_on']) ; ?></td>
              <td><?php if($item['content_subtype'] == 'dynamic') : ?>
          
                    <a class="btn" href="<?php print site_url('admin/content/posts_manage/categories:'.$item['content_subtype_value'])  ?>">Go to category</a>
                <?php endif;  ?> </td>
              <td>
              
              
                
                
              <a class="btn" href="<?php print site_url('admin/content/pages_edit/id:'.$item['id'])  ?>">Edit</a>
              
              
              </td>
              <td><a class="btn red" href="javascript:deleteContentItem(<?php print $item['id']; ?>, 'content_row_id_<?php print $item['id']; ?>')">Delete</a></td>
            </tr>
            <?php endforeach ?>
          </tbody>
        </table></td>
    </tr>
  </table>
</div>
<?php endif; ?>
