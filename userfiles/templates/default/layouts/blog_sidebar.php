<div class="edit"  field="blog_sidebar" rel="inherit">
<form action="#">
    <input type="text" class="input-large search-query" placeholder="<?php _e("Search"); ?>">
</form>

<h4 class="sidebar-title">Recent posts</h4>
<div class="sidebar-box">
    <?php

    /*



     Available parameters(attributes) for "Posts" Module  are:


         * template - Name of the template: Templates provided from Microweber are:

         - default
         - 3 columns
         - 4 columns
         - sidebar

         * limit

         * description-length
            - number: Number of symbols you want to show from description. Default: 400

         * title-length
            - number: Number of symbols you want to show from title. Default: 120

         * curent_page - usage is for paging
            - number: The number of the page where the posts will appear. Default: 1

         * hide-paging
            - y/n - Default: n

         * data-show:
             Possible values:
                - thumbnail,
                - title,
                - read_more,
                - description,
                - created_on

     */

    ?>
    <module type="posts" template="sidebar" limit="5" data-show="thumbnail,title,read_more" hide-paging="y" />
</div>
<h4 class="sidebar-title">Blog Categories</h4>

<div class="sidebar-box">
	<module type="categories" />
</div>
<h4 class="sidebar-title">Recent Posts</h4>
<div class="sidebar-box">
	<module type="pages" template="pills" />
</div>
</div>
<div class="edit"  field="blog_sidebar_2" rel="inherit"></div>