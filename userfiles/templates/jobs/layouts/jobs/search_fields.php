


 


<div id="category_dropdown">
<? category_tree('for_page=jobs'); ?>
</div>
<div class="jobsearch_form_fileds">
  <input type="hidden"   name="search" value="1" />
  <input type="text" class="jobsearch_textbox1"  name="keyword"   value="<? print url_param('keyword'); ?>"  />
  <div id="select_box_mask" style="float:left">
    <div class="jobsearch_drop_close"></div>
    <input class="jobsearch_drop"   name="categories" />
  </div>
</div>
<div class="jobsearch_form_fileds">
  <input type="text" class="jobsearch_textbox1" name="location*|state*|zip*" value="<? print url_param('location*|state*|zip*'); ?>"   />
  <div id="select_box_mask" style="float:left">
    <div class="jobsearch_drop_close"></div>
    <select class="jobsearch_drop">
      <option>Posted - in last 7 days</option>
    </select>
  </div>
</div>
