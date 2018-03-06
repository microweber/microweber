<?php
only_admin_access();


?>

<?php
$comments_data = array(
    'order_by'=>'created_at desc',
    'limit'=>'30',
);
$comments = get_comments($comments_data);

if(is_array($comments )){


    $ccount = count($comments);

}
else{
    $ccount = 0;
}
?>
<div class="dashboard-recent">
    <div class="dr-head">
        <span class="drh-activity-name"><?php _e("Latest activity") ?></span>
        <a class="mw-ui-btn mw-ui-btn-medium"><span class="mai-post"></span><?php _e("Go to posts"); ?></a>
        <span class="mw-ui-btn mw-ui-btn-medium"><span class="mai-comment"></span><?php print $ccount; ?> <?php _e("new comments") ?></span>

    </div>
    <div class="dr-list">


        <?php


        if(is_array($comments )){




            foreach($comments as $comment){

                $params = array(
                    'id' => $comment['id']
                );

                $post = get_content($params);
                $post = $post[0];

                $comments_data = array(
                    'order_by'=>'created_at desc',
                    'rel_id'=>$post['id']
                );
                $postComments =  get_comments($comments_data);



                ?>

                <?php

                var_dump($postComments); ?>
                <div class="dr-item">
                    <div class="dr-item-table">
                        <table>
                            <tr>
                                <td class="dr-item-image">
                                    <span style="background-image: url(<?php print pixum(100,100); ?>)"></span>
                                </td>

                                <td class="dr-item-title">
                                    <?php print $post['title']; ?>
                                </td>
                                <td class="dr-item-price">
                                    33 <span class="mai-comment"></span> comments
                                </td>
                                <td class="dr-item-date">
                                    10 days ago
                                </td>

                            </tr>
                        </table>
                    </div>
                </div>
            <?php }    }  ?>

    </div>
</div>