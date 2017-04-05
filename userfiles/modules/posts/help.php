<strong><?php _e('Help:'); ?></strong>
<table border="1" cellspacing="1" cellpadding="1">
  <tr>
    <th scope="col"><?php _e("Parameter"); ?></th>
    <th scope="col"><?php _e("Description"); ?></th>
    <th scope="col"><?php _e("Example"); ?></th>
  </tr>
  <tr>
    <td>data-page-number</td>
    <td><?php _e("If set, the posts will show from this paging offset"); ?></td>
    <td> data-page-number="3" </td>
  </tr>
  <tr>
    <td>data-page-id</td>
    <td><?php _e("Only the posts that have this page id as a parent will be shown"); ?></td>
    <td> data-page-id="5" </td>
  </tr>
  
  
  
   <tr>
    <td>data-show</td>
    <td><?php _e("Shows the post data in the defined order"); ?></td>
    <td> data-show="title,description,read_more"
    <br />
<?php _e("Available fields are"); ?>:<br />
<ul>
<li>title</li>
<li>description</li>
<li>read_more</li>
<li>updated_at</li>
<li>created_at</li>
<li>created_by</li>
<li>edited_by</li>
 

</ul>
    
     </td>
  </tr>
  
  
  
 

  
  
  
  <tr>
    <td> - </td>
    <td> - </td>
    <td> - </td>
  </tr>
  <tr>
    <td>data-limit</td>
    <td><?php _e("If set it will limit the number of posts to show"); ?></td>
    <td> data-limit="2" </td>
  </tr>
  <tr>
    <td>data-orderby</td>
    <td><?php _e("If set it will limit the number of posts to show"); ?></td>
    <td> data-orderby="id,desc" </td>
  </tr>
  <tr>
    <td>data-category</td>
    <td><?php _e("If you pass category id or category name, it will show the posts from it. You can split multiple categories with comma(,)"); ?></td>
    <td>data-category="blog,news,141" </td>
  </tr>
  <tr>
    <td>data-keyword</td>
    <td><?php _e("Seach by keyword"); ?></td>
    <td> data-keyword="search keyword" </td>
  </tr>
</table>


<textarea>data-type="posts" 
data-fields="id,thumbnail, title, description, date, categories, author, comments, price, expriration_date" 
data-read-more='aaaa' 
data-thumbnail-size='300x200' 
data-category='10, shop' 
data-posts-per-page=10 
data-order="date,asc"
data-keyword="ivan"

data-callback="mw.posts.fancy('#aadas')"
data-list-tag="ul, table"


data-filter-price="<60"
data-filter-price=">30"
data-filter-title="^ivan*"
data-filter-date="<> 1 hour ago</textarea>