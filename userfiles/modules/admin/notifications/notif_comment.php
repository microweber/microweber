<?php
$article = false;
$comments = false;
$picture = false;
if (isset($item['rel_id'])) {
    $article = get_content_by_id($item['rel_id']);
    $comments = get_comments('rel_type=content&rel_id=' . $item['rel_id']);
    $picture = get_picture_by_id($item['rel_id']);
    if (isset($picture['filename'])) {
        $picture = $picture['filename'];
    } else {
        $picture = '';
    }
}

$created_by = false;
if (isset($item['created_by'])) {
    $created_by = get_user_by_id($item['created_by']);
    $created_by_username = $created_by['username'];
}
?>

<div class="card mb-2 not-collapsed-border collapsed card-bubble <?php if (isset($item['is_read']) AND $item['is_read'] == 0): ?>active<?php endif; ?> bg-silver" data-bs-toggle="collapse" data-bs-target="#notif-item-<?php print $item['id'] ?>" aria-expanded="false" aria-controls="collapseExample">
    <div class="card-body">
        <div class="row align-items-center mb-3">
            <div class="col text-start text-left">
                <span class="text-primary text-break-line-2"><?php _e('New comment'); ?></span>
            </div>
        </div>

        <div class="row align-items-center">
            <div class="col" style="max-width: 100px;">
                <div class="img-circle-holder img-absolute border-radius-0 border-0">
                    <?php if ($picture): ?>
                        <img src="<?php echo thumbnail($picture); ?>"/>
                    <?php else: ?>
                        <img src="<?php echo thumbnail(''); ?>"/>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col text-start text-left">
                <?php if (isset($article['title'])): ?>
                    <h5 class="text-primary text-break-line-2"><?php echo $article['title']; ?></h5>
                <?php endif; ?>
            </div>

            <div class="col-12 col-sm text-end text-right"><?php print mw('format')->ago($item['created_at']); ?></div>
        </div>
        <div class="collapse" id="notif-item-<?php print $item['id'] ?>">
            <?php if (isset($article['full_url'])): ?>
                <div class="row mt-3">
                    <div class="col-12">
                        <a href="<?php echo $article['full_url']; ?>" class="btn btn-primary btn-sm btn-rounded"><?php _e('View article'); ?></a>
                    </div>
                </div>
            <?php endif; ?>

            <hr class="thin"/>

            <div class="row">
                <div class="col-md-12">
                    <div class="row mb-3">
                        <div class="col" style="max-width: 80px;">
                            <div class="img-circle-holder w-60 border-0 border-radius-10">
                                <img src="https://d1qb2nb5cznatu.cloudfront.net/users/40837-medium_jpg?1405468137"/>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-1">
                                <small class="text-muted">Status</small>
                            </div>
                            <div>
                                <select class="selectpicker js-change-color d-inline-block" data-style="btn-success btn-sm" data-width="fit">
                                    <option data-change-color="btn-success"><?php _e('Publish'); ?></option>
                                    <option data-change-color="btn-warning"><?php _e('Unpublish'); ?></option>
                                    <option data-change-color="btn-secondary"><?php _e('Mark as Spam'); ?></option>
                                    <option data-change-color="btn-danger"><?php _e('Delete'); ?></option>
                                </select>

                                <a href="dashboard.html" class="btn btn-outline-secondary btn-sm"><?php _e('Edit'); ?></a>
                            </div>
                        </div>
                    </div>


                    <h6><strong>John Doe</strong>
                        <small class="text-muted"><?php _e('says:'); ?></small>
                    </h6>
                    <div>
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book</p>
                    </div>
                    <a href="#reply-message-1" class="btn btn-outline-secondary btn-sm icon-left js-show-more"><i class="mdi mdi-comment-account text-primary"></i> <?php _e('Reply'); ?></a>

                    <div class="collapse" id="reply-message-1">
                        <hr class="thin"/>

                        <div class="row mb-3">
                            <div class="col-12 mb-3">
                                <h5><strong><?php _e('Add a new comment'); ?></strong></h5>
                            </div>
                            <div class="col" style="max-width: 80px;">
                                <div class="img-circle-holder w-60 border-0 border-radius-10">
                                    <img src="https://d1qb2nb5cznatu.cloudfront.net/users/40837-medium_jpg?1405468137"/>
                                </div>
                            </div>
                            <div class="col-12 col-sm mt-3 mt-sm-0">
                                <div class="form-group">
                                    <textarea></textarea>
                                </div>
                                <div class="text-end text-right">
                                    <a href="dashboard.html" class="btn btn-outline-secondary btn-sm"><?php _e('Post Comment'); ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
