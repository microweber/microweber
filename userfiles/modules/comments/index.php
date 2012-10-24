<?
$data = $params;
if (!isset($params['to_table'])) {

    $data['to_table'] = 'table_content';
}



if (!isset($data['to_table_id'])) {



    if (defined('POST_ID') == true and intval(POST_ID) != 0) {
        $data['to_table_id'] = POST_ID;
    }
    if (!isset($data['to_table_id'])) {
        if (defined('PAGE_ID') == true) {
            $data['to_table_id'] = PAGE_ID;
        }
    }
}
?>

<?
$comments_data = array();
$comments_data['to_table_id'] = $data['to_table_id'];
$comments_data['to_table'] = $data['to_table'];
$comments = get_comments($comments_data);
//d($comments);


$template = option_get('data-template', $params['id']);
$template_file = false;
if ($template != false and strtolower($template) != 'none') {
//
    $template_file = module_templates($params['type'], $template);

//d();
}
?>
<script  type="text/javascript">
            mw.require("forms.js");
 </script>
 <script  type="text/javascript">
 $(document).ready(function(){
                mw.$('form#comments-form-<? print $data['id'] ?>').submit(function() {
                    mw.form.post('form#comments-form-<? print $data['id'] ?>', '<? print site_url('api/post_comment'); ?>');

                    mw.reload_module('#<? print $params['id'] ?>');

                    return false;
                });
            });
   </script>
        <?php
switch ($template_file):
    case true:
        ?>
        <? include($template_file); ?>
        <?
       // d();

        if($template_file != false){
            break;
        }


    ?>
    <?php
    case false:
        ?>




        <? if (isarr($comments)): ?>
            <div class="comments" id="comments-list-<? print $data['id'] ?>">
                <? foreach ($comments as $comment) : ?>
                    <div class="comment" id="comment-<? print $comment['id'] ?>">

                        <div class="comment-author">
                            <? print $comment['comment_name'] ?>

                        </div>

                        <div class="comment-body">
                            <? print $comment['comment_body'] ?>

                        </div>


                    </div>
                <? endforeach; ?>

            </div>
        <? endif; ?>

        <div class="comments_form" id="comments-<? print $data['id'] ?>">
            <form id="comments-form-<? print $data['id'] ?>">
                <input type="hidden" name="to_table_id" value="<? print $data['to_table_id'] ?>">
                <input type="hidden" name="to_table" value="<? print $data['to_table'] ?>">
                Your name: <br>
                <input type="text" name="comment_name">
                <br>
                <br>
                Website: <br>
                <input type="text" name="comment_website">
                <br>
                <br>
                Your email: <br>
                <input type="text" name="comment_email">
                <br>
                <br>
                Your comments: <br>
                <textarea name="comment_body" rows="15" cols="50"></textarea>
                <br>
                <br>
                <img src="<? print site_url('api/captcha') ?>" onclick="this.src='<? print site_url('api/captcha') ?>'" />
                <input type="captcha"   name="captcha" placeholder="?">
                <input type="submit" value="Submit">
            </form>
        </div>
        <?php break; ?>
<?php endswitch; ?>




