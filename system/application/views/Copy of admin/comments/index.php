


<script type="text/javascript">
    $(function(){
        var tag_tabs = $('#tab_tag').tabs();
$('#TagTabcontrol a').click(function() {
    var TTindex = $('#TagTabcontrol a').index(this);
    tag_tabs.tabs('select', TTindex);

    $('#TagTabcontrol a').removeClass("active");
    $(this).addClass("active");

    return false;
});

    })
</script>

<?php if($content_pages_count > 1) :   ?>
          <div align="center" id="admin_content_paging">
            <?php for ($i = 1; $i <= $content_pages_count; $i++) : ?>
            <a href="<?php print  $content_pages_links[$i]  ?>" <?php if($content_pages_curent_page == $i) :   ?> class="active" <?php endif; ?> ><?php print $i ?></a>
            <?php endfor; ?>
          </div>
<?php endif; ?>
<form action="<?php print site_url('admin/comments/comments_do_search') ; ?>" method="post"  enctype="multipart/form-data" class="optionsForm">
<div align="center">
	<input type="text" name="search_by_keyword" id="search_by_keyword" value="<?php print $search_by_keyword?>">&nbsp;<input type="submit" value="Search">
</div>
</form>

<div id="subheader">
  <ul id="TagTabcontrol">
    <li><a href="javascript:;">New comments</a></li>
    <li><a href="javascript:;">All comments</a></li>
  </ul>
</div>

<div id="tab_tag">

<ul style="display: none !important">
    <li><a href="#tab1">New comments</a></li>
    <li><a href="#tab2">All comments</a></li>
</ul>
  <div id="tab1" class="ctable">
    <?php $comments = $new_comments ?>
    <?php include ('comments_list.php') ?>
  </div>
  <div id="tab2" class="ctable">
    <?php $comments = $old_comments ?>
    <?php include ('comments_list.php') ?>
  </div>

</div>





<div class="clear"></div>
<br />
