<?php if (is_post() == true) { ?>
    <div class="edit sidebar" field="liteness_blog_sidebar_2" rel="inherit">
        <h4 class="element sidebar-title">Recent posts</h4>
        <div class="sidebar-box">
            <module type="posts" template="sidebar" limit="5" data-show="thumbnail,title,description,created_at" hide-paging="y" title-length="40" description-length="50"/>
        </div>
    </div>
<?php } ?>

<div class="edit sidebar" field="liteness_blog_sidebar" rel="inherit">
    <h4 class="element sidebar-title">Blog Categories</h4>

    <div class="sidebar-box">
        <module type="categories"/>
    </div>
</div>
