<style>
    .btn.disabled, .btn:disabled {
        opacity: 0.25;
    }
    .js-post-box {
       margin-top:5px;
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
        font-size:14px;
        font-weight: bold;
    }
    .btn-tag {
        margin-top: 5px;
        margin-right: 5px;
    }
</style>

<script>
$(document).ready(function () {

    $('.js-add-tags-to-posts').attr('disabled','disabled');

    searchPostsByKeyowrd();

    $('.js-posts-filter-by option').each(function() {
        if($(this).val() == 'posts') {
          $(this).prop("selected", true);
        }
    });

    $(document).on('change', '.js-posts-filter-by', function() {
        searchPostsByKeyowrd();
        changeFilterText();
    });

    $(document).on('change', '.js-post-checkbox', function() {

        selected_taggable_items = getSelectedTaggableItems();
        if (selected_taggable_items.length > 0) {
            $('.js-add-tags-to-posts').removeAttr('disabled');
        } else {
            $('.js-posts-tags').html('<h5>Select posts to see tags.</h5>');
            $('.js-add-tags-to-posts').attr('disabled','disabled');
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
function getTaggingTagButtonHtml(id,name,slug, posts_count =0) {

    var html = '<div class="btn-group btn-tag btn-tag-id-'+id+'" role="group">' +
        '    <button type="button" class="btn btn-secondary"><i class="fa fa-tag"></i> ' + name + '</button>' +
        '    <button type="button" class="btn btn-secondary" data-slug="'+slug+'" onClick="showPostsWithTags(this)">('+posts_count+')</button>' +
        '    <button type="button" class="btn btn-secondary" onClick="editTaggingTag('+id+')"><i class="fa fa-pen"></i></button>' +
        '    <button type="button" class="btn btn-secondary" onClick="deleteTaggingTag('+id+')"><i class="fa fa-times"></i></button>' +
        '</div>';

    return html;
}

function getTaggingTaggedButtonHtml(taggable_id, tag_name) {

    var html = '<div class="btn-group btn-tag btn-tag-id-'+taggable_id+'" role="group">' +
        '    <button type="button" class="btn btn-secondary"><i class="fa fa-tag"></i> ' + tag_name + '</button>' +
        '    <button type="button" class="btn btn-secondary" onClick="deleteTaggingTagged('+taggable_id+')"><i class="fa fa-times"></i></button>' +
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
                'taggable_id':$(this).find('.js-post-checkbox-id').val(),
                'taggable_title':$(this).find('.js-post-checkbox-title').val()
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
        }, function(data) {

            var tags = '';

            for (i = 0; i < data.tags.length; i++) {
                tags += getTaggingTaggedButtonHtml(data.tags[i].id, data.tags[i].tag_name);
            }

            var postTagBoxHtml = '' +
                '<div class="js-post-tag-box js-post-tag-' + taggable_id + '">' +
                '<p>' + data.title + '</p>' +
                '<div>Tags: ' + tags + '<button class="btn btn-success js-post-tag-add-new" onClick="addTaggingTagged('+taggable_id+')" style="margin-top:5px;margin-right:5px;"><i class="fa fa-plus"></i></button></div>' +
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

    $('.js-all-tags').html('Searching for: ' + keyword);

    $.get(mw.settings.api_url + 'tagging_tag/get', {
        keyword: keyword,
    }, function(data) {
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

    $('.js-select-posts').html('Searching for: ' + keyword);

    var content_type = '[neq]page';
    if (filter == 'products') {
        content_type = 'product';
    }
    if (filter == 'posts') {
        content_type = 'post';
    }

    $.get(mw.settings.api_url + 'get_content_admin', {
            search_in_tags_keyword: keyword,
            search_in_tags: true,
            keyword: keyword,
            order_by: 'updated_at+desc',
            content_type: content_type,
            search_in_fields: 'title'
        }, function(data) {
        for (i = 0; i < data.length; i++) {
            posts += '<div class="mw-ui-box mw-ui-box-content js-post-box">\n' +
                '                            <label class="mw-ui-check">\n' +
                '                                <input type="hidden" class="js-post-checkbox-id" value="'+ data[i].id +'">\n' +
                '                                <input type="hidden" class="js-post-checkbox-title" value="'+ data[i].title +'">\n' +
                '                                <input type="checkbox" class="js-post-checkbox" value="1">\n' +
                '                                <span></span><span>\n'
                                                            + data[i].title +
                '                                        </span>\n' +
                '                            </label>\n' +
                '                        </div>';
        }
        $('.js-select-posts').html(posts);
    });

}
</script>

<div class="mw-flex-row">

    <div class="mw-flex-col-xs-6 last-xs">
        <div style="font-weight: bold;">Search <span class="js-filter-by-text">posts</span></div>
        <div class="input-group">
            <input type="text" class="form-control js-search-posts-keyword" placeholder="Keyword...">
            <div class="input-group-append">
                <button class="btn btn-success js-search-posts-submit" type="button">Search</button>
            </div>
        </div>
        <span>You can search posts by title & tags.</span>
        <br />
        <br />
    </div>

    <div class="mw-flex-col-xs-6 last-xs">
        <!-- tags search -->
    </div>

    <div class="mw-flex-col-xs-6 last-xs">
        <div class="box">
            <div class="card">
                <div class="card-header">
                    <span class="js-filter-by-text">Posts</span> list
                    <button class="btn btn-sm btn-default pull-right">&nbsp;</button>
                </div>
                <div class="card-body">
                    <h5 class="card-title">
                        List of all <span class="js-filter-by-text">Posts</span>
                    </h5>
                    <button class="btn btn-primary pull-right js-add-tags-to-posts" disabled="disabled">Add tags to <span class="js-filter-by-text">Posts</span></button>

                    <p class="card-text">Select the <span class="js-filter-by-text">Posts</span> you want to add or edit tags.</p>

                    Filter:
                    <select class="form-control js-posts-filter-by">
                        <option value="posts">Posts</option>
                        <option value="products">Products</option>
                    </select>
                    <br />
                    <b><span class="js-filter-by-text">Post</span> lists</b>
                    <div class="js-select-posts" style="width:100%;max-height: 350px;overflow-y: scroll;">

                    </div>
                </div>
            </div>


        </div>
    </div>
    <div class="mw-flex-col-xs-6">
        <div class="box">

            <style>
                .badge {
                    font-size: 14px;
                    margin-right: 10px;
                    margin-bottom: 10px;
                    font-weight: normal;
                    padding: 10px;
                }
            </style>

            <div class="card">
                <div class="card-header">
                    Tags for <span class="js-filter-by-text">Post</span>
                </div>
                <div class="card-body" style="height: 525px;overflow-y: scroll;">

                    <h5 class="card-title">
                        List of all tags for selected <span class="js-filter-by-text">Posts</span>
                    </h5>

                    <p class="card-text">Select the <span class="js-filter-by-text">tags</span> you want to add or edit for posts.</p>
                    <hr />
                    <div class="js-posts-tags">

                        <h5>Select posts to see tags.</h5>

                    </div>
                </div>
            </div>


        </div>
    </div>
</div>