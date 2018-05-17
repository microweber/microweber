<div class="container" >
    <div class="row prev-next">
dsa
        <div class="col-xs-6">
            <?php if (prev_content()): ?>
                <a href="<?php print prev_content()['full_url']; ?>" class="arrow">
                    <p><i class="mw-icon  material-icons mw-wysiwyg-custom-icon" style="font-size: 35px; color: rgb(190, 156, 88); vertical-align:bottom;">arrow_back</i> Previous Article</p>
                </a>
            <?php endif; ?>
        </div>
        <div class="col-xs-6 ">
            <?php if (next_content()): ?>
                <a href="<?php print next_content()['full_url']; ?>" class="arrow">
                    <p class="text-right"> Next Article <i class="mw-icon  material-icons mw-wysiwyg-custom-icon" style="font-size: 35px; color: rgb(190, 156, 88); vertical-align:bottom;">arrow_forward</i></p>
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>