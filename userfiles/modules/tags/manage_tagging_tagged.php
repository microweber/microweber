<style>
    .btn.disabled, .btn:disabled {
        opacity: 0.25;
    }

    .js-post-box {
        margin-top: 5px;
        transition: 0.3s;
    }

    .js-post-box:hover {
        border: 1px solid #7fb4ff;
        background: #e1f1fd;
    }

    .js-post-box-active {
        border: 1px solid #7fb4ff;
        background: #e1f1fd;
    }

    .js-post-tag-box {
        border-bottom: 1px solid #d9d9d9;
        padding-top: 5px;
        padding-bottom: 5px;
    }

    .js-post-tag-box p {
        font-size: 14px;
        font-weight: bold;
    }

    .btn-tag {
        margin-top: 5px;
        margin-right: 5px;
    }
</style>

<script>
    mw.lib.require('xss');
</script>

<script>
    $(document).ready(function () {

        $('.js-add-tags-to-posts').attr('disabled', 'disabled');

        searchPostsByKeyowrd();

        $('.js-posts-filter-by option').each(function () {
            if ($(this).val() == 'posts') {
                $(this).prop("selected", true);
            }
        });

        $(document).on('change', '.js-posts-filter-by', function () {
            searchPostsByKeyowrd();
            changeFilterText();
        });

        $(document).on('change', '.js-post-checkbox', function () {

            selected_taggable_items = getSelectedTaggableItems();
            if (selected_taggable_items.length > 0) {
                $('.js-add-tags-to-posts').removeAttr('disabled');
            } else {
                $('.js-posts-tags').html('<h5>Select posts to see tags.</h5>');
                $('.js-add-tags-to-posts').attr('disabled', 'disabled');
            }

            if ($(this).is(':checked')) {
                $(this).parent().parent().addClass('js-post-box-active');
                getPostTags($(this).parent().find('.js-post-checkbox-id').val());
            } else {
                removePostTags($(this).parent().find('.js-post-checkbox-id').val());
                $(this).parent().parent().removeClass('js-post-box-active');
            }
        });
        // $('.js-add-tags-to-posts').attr('disabled','disabled');
        /*
         $(document).on('click', '.js-post-box', function(e) {
         $(this).find('.js-post-checkbox').click();
         });
         */

        $('.js-add-tags-to-posts').click(function () {
            var selected_taggable_items = getSelectedTaggableItems();
            addTaggingTagged(false, selected_taggable_items);
        });

        $('.js-search-posts-submit').click(function () {
            searchPostsByKeyowrd();
        });

        $('.js-search-posts-keyword').keyup(function () {
            searchPostsByKeyowrd();
        });

        $('.js-search-tags-keyword').keyup(function () {
            searchTagsByKeyowrd();
        });

    });

    function changeFilterText() {
        if ($('.js-posts-filter-by').val() == 'products') {
            $('.js-filter-by-text').html('Products');
        } else {
            $('.js-filter-by-text').html('Posts');
        }
    }
    function getTaggingTagButtonHtml(id, name, slug, posts_count =0) {

        var html = '<div class="btn-group tag mb-3 mr-3 btn-tag-id-' + id + '" role="group">' +
            '    <button type="button" class="btn btn-outline-secondary btn-sm icon-left" data-slug="' + slug + '" onClick="showPostsWithTags(this)"><strong>' + name + '</strong> <small class="ml-1 text-muted">(' + posts_count + ')</small></button>' +
            '    <button type="button" class="btn btn-outline-secondary btn-sm btn-icon" onClick="editTaggingTag(' + id + ')"><i class="mdi mdi-pencil text-primary"></i></button>' +
            '    <button type="button" class="btn btn-outline-secondary btn-sm btn-icon" onClick="deleteTaggingTag(' + id + ')"><i class="mdi mdi-close text-danger"></i></button>' +
            '</div>';

        return html;
    }

    function getTaggingTaggedButtonHtml(taggable_id, tag_name) {
        var html = '<div class="btn-group tag mb-2 mr-1 btn-tag-id-' + taggable_id + '" role="group">' +
            '    <button type="button" class="btn-sm icon-left no-hover btn btn-primary"><i class="mdi mdi-tag"></i> ' + tag_name + '</button>' +
            '    <button type="button" class="btn btn-primary btn-sm btn-icon" onClick="deleteTaggingTagged(' + taggable_id + ')"><i class="mdi mdi-close"></i></button>' +
            '</div>';

        return html;
    }

    function removePostTags(taggable_id) {
        $('.js-post-tag-' + taggable_id).remove();
    }

    function getSelectedTaggableItems() {

        selected_taggable_items = [];
        $('.js-post-box').each(function (e) {
            if ($(this).find('.js-post-checkbox').is(':checked')) {
                selected_taggable_items.push({
                    'taggable_id': $(this).find('.js-post-checkbox-id').val(),
                    'taggable_title': $(this).find('.js-post-checkbox-title').val()
                });
            }
        });

        return selected_taggable_items;
    }

    function getPostTags(taggable_id) {

        selected_taggable_items = getSelectedTaggableItems();
        if (selected_taggable_items.length == 1) {
            $('.js-posts-tags').html('');
        }

        $.get(mw.settings.api_url + 'tagging_tagged/get_by_taggable_id', {
            taggable_id: taggable_id
        }, function (data) {

            var tags = '';

            for (i = 0; i < data.tags.length; i++) {
                tags += getTaggingTaggedButtonHtml(data.tags[i].id, data.tags[i].tag_name);
            }

            var postTagBoxHtml = '' +
                '<div class="js-post-tag-box bg-white px-2 mb-3 js-post-tag-' + taggable_id + '">' +
                '<p class="mb-1 mt-2">' + data.title + '</p>' +
                '<div class="d-flex align-items-center flex-wrap"><div class="mb-2 mr-2">Tags:</div> ' + tags + '<button class="btn btn-success btn-sm btn-icon js-post-tag-add-new mb-2" onClick="addTaggingTagged(' + taggable_id + ')"><i class="fa fa-plus"></i></button></div>' +
                '</div>';

            var postTagBox = $('.js-post-tag-' + taggable_id);
            if (postTagBox.length > 0) {
                postTagBox.replaceWith(postTagBoxHtml);
            } else {
                $('.js-posts-tags').append(postTagBoxHtml);
            }
        });
    }

    function searchTagsByKeyowrd() {

        var tags = '';
        var keyword = $('.js-search-tags-keyword').val();

      if(typeof keyword === 'undefined'){
          keyword = '';
      }

        keyword = filterXSS(keyword);

        keyword = String(keyword).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');

        $('.js-all-tags').html('Searching for: ' + keyword);

        $.get(mw.settings.api_url + 'tagging_tag/get', {
            keyword: keyword,
        }, function (data) {
            if (data.length > 0) {
                for (i = 0; i < data.length; i++) {
                    if (data[i].id) {
                        tags += getTaggingTagButtonHtml(data[i].id, data[i].name, data[i].slug, data[i].posts_count);
                    }
                }
            } else {
                if (typeof(keyword) == 'undefined') {
                    tags = 'No tags found.';
                } else if (keyword == '') {
                    tags = 'No tags found.';
                } else {
                    tags = 'No tags found for <b>' + keyword + '</b>.';
                }
            }
            $('.js-all-tags').html(tags);
        });

    }
    searchTagsByKeyowrd();

    function searchPostsByKeyowrd() {

        var posts = '';
        var keyword = $('.js-search-posts-keyword').val();
        var filter = $('.js-posts-filter-by').val();

        keyword = String(keyword).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');

        $('.js-select-posts').html('Searching for: ' + keyword);

        var content_type = '[neq]page';
        if (filter == 'products') {
            content_type = 'product';
        }
        if (filter == 'posts') {
            content_type = 'post';
        }

        // Search in tags
        $.get(mw.settings.api_url + 'get_content_admin', {
            keyword: keyword,
            order_by: 'updated_at+desc',
            content_type: content_type,
            search_in_fields: 'title'
        }, function (data) {
            renderContent(data);
        });

        function renderContent(data) {
            for (i = 0; i < data.length; i++) {
                posts += '<div class="mw-ui-box mw-ui-box-content js-post-box">\n' +
                    '                            <label class="mw-ui-check">\n' +
                    '                                <input type="hidden" class="js-post-checkbox-id" value="' + data[i].id + '">\n' +
                    '                                <input type="hidden" class="js-post-checkbox-title" value="' + data[i].title + '">\n' +
                    '                                <input type="checkbox" class="js-post-checkbox" value="1">\n' +
                    '                                <span></span><span>\n'
                    + data[i].title +
                    '                                        </span>\n' +
                    '                            </label>\n' +
                    '                        </div>';
            }
            $('.js-select-posts').html(posts);
        }

    }
</script>

<div class="row">

    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label mb-0"><?php _e('Search'); ?> <span class="js-filter-by-text"><?php _e('posts'); ?></span></label>
            <small class="d-block text-muted mb-2"><?php _e('You can search posts by title & tags.'); ?></small>
            <div class="input-group">
                <input type="text" class="form-control js-search-posts-keyword" placeholder="<?php _e('Keyword...'); ?>">
                <div class="input-group-append">
                    <button class="btn btn-success js-search-posts-submit" type="button"><?php _e('Search'); ?></button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <!-- tags search -->
    </div>

    <div class="col-md-6">
        <div class="card style-1 bg-light">
            <div class="card-header">
                <h6 class="font-weight-bold"><span class="js-filter-by-text"><?php _e('Posts'); ?></span> <?php _e('list'); ?></h6>
            </div>

            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6><?php _e('List of all'); ?> <span class="js-filter-by-text"><?php _e('Posts'); ?></span></h6>
                    <button class="btn btn-success btn-sm js-add-tags-to-posts" disabled="disabled"><?php _e('Add tags to'); ?>&nbsp;<span class="js-filter-by-text"><?php _e('Posts'); ?></span></button>
                </div>

                <small class="text-muted d-block mb-3"><?php _e('Select the'); ?> <span class="js-filter-by-text"><?php _e('Posts'); ?></span> <?php _e('Actions'); ?></small>

                <div class="form-group">
                    <label class="control-label d-block"><?php _e('Filter:'); ?></label>
                    <select class="js-posts-filter-by form-control" data-width="100%">
                        <option value="posts"><?php _e('Posts'); ?></option>
                        <option value="products"><?php _e('Products'); ?></option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="control-label d-block"><span class="js-filter-by-text"><?php _e('Post'); ?></span> <?php _e('lists'); ?></label>
                    <div class="js-select-posts" style="width:100%;max-height: 350px;overflow-y: scroll;"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card style-1 bg-light">
            <div class="card-header">
                <h6 class="font-weight-bold"><?php _e('Tags for'); ?> <span class="js-filter-by-text"><?php _e('Post'); ?></span></h6>
            </div>
            <div class="card-body" style="height: 525px;overflow-y: scroll;">
                <h6 class="font-weigh-bold"><?php _e('List of all tags for selected'); ?> <span class="js-filter-by-text"><?php _e('Posts'); ?></span></h6>

                <small class="d-block text-muted mb-2"><?php _e('Select the'); ?> <span class="js-filter-by-text"><?php _e('tags'); ?></span> <?php _e('you want to add or edit for posts.'); ?></small>
                <hr class="thin"/>

                <div class="js-posts-tags">
                    <h6 class="font-weight-bold"><?php _e('Select posts to see tags.'); ?></h6>
                </div>
            </div>
        </div>
    </div>
</div>
