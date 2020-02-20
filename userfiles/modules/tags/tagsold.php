

<button type="button" onclick="editTag(false);" class="mw-ui-btn mw-ui-btn-info"> <i class="mw-icon-web-promotion"></i> &nbsp; Add New Tag</button>

<br />
<br />

<table class="mw-ui-table table-style-2" width="100%" cellspacing="0" cellpadding="0">
    <thead>
    <tr>
        <th>Name</th>
        <th>Slug</th>
        <th>Count</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>

    <?php
    $paging_param = 'tags_current_page';

    $tagging_tags_pages = db_get('tagging_tags', [
        'page_count'=>1
    ]);

    $cur_page = 1;
    $cur_page_url = url_param($paging_param, true);
    if($cur_page_url){
        $cur_page = intval($cur_page_url);
    }
    $tagging_tags = db_get('tagging_tags', [
        'current_page'=>$cur_page,
        'paging_param'=>$paging_param,
    ]);


    if ($tagging_tags):
        foreach ($tagging_tags as $tag):
            $tag['content_count'] = 0;


            $count =   db_get('tagging_tagged', [
                'tag_slug'=>$tag['slug'],
                'count'=>1
            ]);

            if($count){
                $tag['content_count'] = $count;

            }

            ?>
            <tr>
                <td><?php echo $tag['name']; ?></td>
                <td><?php echo $tag['slug']; ?></td>

                <td><a href="javascript:void();" onclick="showPostsWithTags('<?php echo $tag['slug']; ?>')"><?php echo $tag['content_count']; ?></a></td>
                <td>
                    <button onclick="editTag(<?php echo $tag['id']; ?>);" class="mw-ui-btn"><span class="mw-icon-edit"></span></button>
                    <button onclick="deleteTag(<?php echo $tag['id']; ?>);" class="mw-ui-btn"><span class="mw-icon-bin"></span></button>
                </td>
            </tr>
        <?php endforeach; ?>

    <?php else: ?>

    <?php endif; ?>

    </tbody>
</table>

<br />

<?php if (isset($tagging_tags_pages) and $tagging_tags_pages > 1 and isset($paging_param)): ?>
    <module type="pagination" template="mw" pages_count="<?php echo $tagging_tags_pages; ?>" paging_param="<?php echo $paging_param; ?>" />
<?php endif; ?>
