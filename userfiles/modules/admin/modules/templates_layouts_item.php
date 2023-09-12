<?php if (($item['layout_file'] != $cur_template)): ?>
    <?php if ((strtolower($item['name']) != 'default')): ?>
        <a href="javascript:;" class="js-apply-template card m-1"
           data-file="<?php print $item['layout_file'] ?>">
            <?php if ($item['layout_file'] == $cur_template): ?>
                <div class="default-layout">DEFAULT</div>
            <?php endif; ?>

            <div class="screenshot <?php if (($item['layout_file'] == $cur_template)): ?>active<?php endif; ?>">
                <?php
                $item_screenshot = thumbnail('');
                if (isset($item['screenshot'])) {
                    $item_screenshot = $item['screenshot'];
                }
                ?>

                <div class="holder">
                    <img data-url="<?php echo thumbnail($item_screenshot, 800, 400); ?>"
                         alt="<?php print $item['name']; ?> - <?php print addslashes($item['layout_file']) ?>"
                         style="max-width:100%;"
                         title="<?php print $item['name']; ?> - <?php print addslashes($item['layout_file']) ?>"/>
                    <div class="live-edit-label text-decoration-none"><?php print $item['name']; ?></div>
                </div>
            </div>
        </a>
    <?php endif; ?>
<?php endif; ?>
