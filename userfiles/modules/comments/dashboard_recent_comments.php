<?php
$commentsCount = \MicroweberPackages\Modules\Comments\Models\Comment::forAdminPreview()->count();

?>

<div class="card mb-4 dashboard-admin-cards">
    <a class="dashboard-admin-cards-a" href="<?php print route('admin.comments.index') ?>">
        <div class="card-body px-xxl-4 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <div class="dashboard-icons-wrapper wrapper-icon-comments">
                    <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="40" viewBox="0 96 960 960"
                         width="40">
                        <path
                            d="M80 976V242.666q0-26.333 19.833-46.499Q119.666 176 146.666 176h666.668q26.333 0 46.499 20.167Q880 216.333 880 242.666v506.668q0 26.333-20.167 46.499Q839.667 816 813.334 816H240L80 976Zm66.666-160.999 65.667-65.667h601.001V242.666H146.666v572.335Zm0-572.335v572.335-572.335Z"/>
                    </svg>
                </div>

                <div class="row">
                    <p><?php _e('Last comments') ?></p>

                    <h5 class="dashboard-numbers">
                        <?php print $commentsCount ?>
                    </h5>
                </div>
            </div>


            <div>
                <a href="<?php print route('admin.comments.index') ?>" class="btn btn-link"><?php _e('View') ?></a>
            </div>

        </div>
    </a>
</div>
