
<form method="post" action="<? print $params['url'] ;?>" class="search_form">
  <input type="text" name="search_by_keyword" class="search_form_input" value="<? print url_param('keyword') ?>"  />
  <input type="submit" value="Search" class="search_form_button"  />
</form>
