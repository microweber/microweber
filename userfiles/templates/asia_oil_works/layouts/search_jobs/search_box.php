
<label style="margin-left:12px;">Search jobs</label>
<form method="post" action="<? print site_url('search-jobs');    ?>" id="search_engine">
  <span class="search_field">
  <input type="text" value="<? print url_param('keyword');   ?>" name="search_by_keyword" onfocus="this.value=='Type keyword here'?this.value='':''" onblur="this.value==''?this.value='Type keyword here':''" />
  </span>
  <input type="submit" class="xhidden" value="Search" />
  <a href="#" class="search_submit action-submit">Search</a>
</form>
