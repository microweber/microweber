
<div class="card mb-2 not-collapsed-border collapsed card-bubble <?php if ($is_read): ?>bg-silver<?php else: ?>card-success<?php endif; ?>" data-bs-toggle="collapse" data-bs-target="#notif-item-<?php print $id ?>" aria-expanded="false" aria-controls="collapseExample">
    <div class="card-body">
        <div class="row align-items-center mb-3">
            <div class="col text-start text-left">
                <span class="text-primary text-break-line-2">New comment</span>
            </div>
        </div>

        <div class="row align-items-center">
            <div class="col" style="max-width: 100px;">
                <div class="img-circle-holder img-absolute border-radius-0 border-0">
                    <img src="<?php echo $picture; ?>"/>
                </div>
            </div>
            <div class="col text-start text-left">
                <?php if (isset($article['title'])): ?>
                <h5 class="text-primary text-break-line-2"><?php echo $article['title']; ?></h5>
                <?php endif; ?>
            </div>

            <div class="col-12 col-sm text-end text-right"><?php _e(mw('format')->ago($notification['created_at'])); ?></div>
        </div>
        <div class="collapse" id="notif-item-<?php print $id ?>">
            <?php if (isset($article['full_url'])): ?>
            <div class="row mt-3">
                <div class="col-12">
                    <a href="<?php echo $article['full_url']; ?>" class="btn btn-primary btn-sm btn-rounded">View article</a>
                </div>
            </div>
            <?php endif; ?>

            <hr class="thin"/>

            <div class="row">
                <div class="col-md-12">
                    <div class="row mb-3">
                        <div class="col" style="max-width: 80px;">
                            <div class="img-circle-holder w-60 border-0 border-radius-10">
                                <img src="<?php echo $user_picture; ?>"/>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-1">
                                {{--<small class="text-muted">Status</small>--}}
                            </div>
                            <div>
                               {{-- <select class="selectpicker js-change-color d-inline-block" data-style="btn-success btn-sm" data-width="fit">
                                    <option data-change-color="btn-success">Publish</option>
                                    <option data-change-color="btn-warning">Unpublish</option>
                                    <option data-change-color="btn-secondary">Mark as Spam</option>
                                    <option data-change-color="btn-danger">Delete</option>
                                </select>--}}

                               {{-- <a href="dashboard.html" class="btn btn-outline-secondary btn-sm">Edit</a>--}}
                            </div>
                        </div>
                    </div>


                    <h6><strong>{{$created_by_username}}</strong>
                        <small class="text-muted">says:</small>
                    </h6>
                    <div>
                        <p>{{ $notification['comment_body'] }}</p>
                    </div>


                    {{--<a href="#reply-message-<?php print $id ?>" class="btn btn-outline-secondary btn-sm icon-left js-show-more"><i class="mdi mdi-comment-account text-primary"></i> Reply</a>

                    <div class="collapse" id="reply-message-<?php print $id ?>">
                        <hr class="thin"/>

                        <div class="row mb-3">
                            <div class="col-12 mb-3">
                                <h5><strong>Add a new comment</strong></h5>
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
                                    <a href="dashboard.html" class="btn btn-outline-secondary btn-sm">Post Comment</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    --}}
                </div>
            </div>
        </div>
    </div>
</div>
