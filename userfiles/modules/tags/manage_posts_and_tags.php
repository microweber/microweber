<style>
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
</style>

<script>
$(document).ready(function () {

    searchPostsByKeyowrd();

    $(document).on('change', '.js-post-checkbox', function() {
        if ($(this).is(':checked')) {
            $(this).parent().parent().addClass('js-post-box-active');
            getPostTags($(this).parent().find('.js-post-checkbox-id').val());
        } else {
             removePostTags($(this).parent().find('.js-post-checkbox-id').val());
            $(this).parent().parent().removeClass('js-post-box-active');
        }
    });
/*
    $(document).on('click', '.js-post-box', function(e) {
        $(this).find('.js-post-checkbox').click();
    });
*/

    $('.js-search-posts-submit').click(function () {
        searchPostsByKeyowrd();
    });

    $('.js-search-posts-keyword').keyup(function () {
        searchPostsByKeyowrd();
    });

});



function removePostTags(post_id) {
    $('.js-post-tag-' + post_id).remove();
}

function getPostTags(post_id) {

    selected_posts = [];
    $('.js-post-box').each(function (e) {
        if ($(this).find('.js-post-checkbox').is(':checked')) {
            selected_posts.push({
                'post_title':$(this).find('.js-post-checkbox-title').val()
            });
        }
    });

    if (selected_posts.length == 1) {
        $('.js-posts-tags').html('');
    }

    $.get(mw.settings.api_url + 'get_post_tags', {
            post_id: post_id
        }, function(data) {
            $('.js-posts-tags').append('<div class="js-post-tag-' + post_id + '">' + data.title + '</div>');
    });
}


function searchPostsByKeyowrd() {

    var posts = '';
    var keyword = $('.js-search-posts-keyword').val();

    $('.js-select-posts').html('Searching for: ' + keyword);

    $.get(mw.settings.api_url + 'get_content_admin', {
            keyword: keyword,
            order_by: 'updated_at+desc',
            content_type: '[neq]page',
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
        <div style="font-weight: bold;">Search posts</div>
        <div class="input-group mb-3">
            <input type="text" class="form-control js-search-posts-keyword" placeholder="Keyword...">
            <div class="input-group-append">
                <button class="btn btn-success js-search-posts-submit" type="button">Search</button>
            </div>
        </div>
    </div>

    <div class="mw-flex-col-xs-6 last-xs">
        <!-- tags search -->
        <div style="font-weight: bold;">Search tags</div>
        <div class="input-group mb-3">
            <input type="text" class="form-control js-search-tags-keyword" placeholder="Keyword...">
            <div class="input-group-append">
                <button class="btn btn-success js-search-posts-submit" type="button">Search</button>
            </div>
        </div>
    </div>

    <div class="mw-flex-col-xs-6 last-xs">
        <div class="box">
            <div class="card">
                <div class="card-header">
                    Posts list
                    <button class="btn btn-sm btn-default pull-right">&nbsp;</button>
                </div>
                <div class="card-body">
                    <h5 class="card-title">
                        Listd of all posts
                    </h5>

                    <p class="card-text">Select the posts you want to add or edit tags.</p>

                    <b>Post lists</b>
                    <div class="js-select-posts" style="max-height: 350px;overflow-y: scroll;">

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
                    Tags
                    <button class="btn btn-sm btn-success pull-right" onclick="editTag(false);"><i class="fa fa-plus"></i> Add Tag</button>
                </div>
                <div class="card-body">
                    <div class="js-posts-tags">
                        <?php
                        $tagging_tags = db_get('tagging_tags', []);
                        if ($tagging_tags):
                            foreach ($tagging_tags as $tag):
                                ?>
                                <a href="#" class="badge badge-dark">
                                    <i class="mw-icon-web-promotion"></i> <?php echo $tag['name']; ?>  <span class="mw-icon-close"></span>
                                </a>
                            <?php
                            endforeach;
                            ?>
                        <?php
                        endif;
                        ?>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>