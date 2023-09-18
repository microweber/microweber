
<ul class="pagination">
    <?php foreach ($pagination_links as $pagination_link): ?>
        <?php if ($pagination_link['attributes']['current']): ?>
            <li class="page-item active">
            <span class="page-link">
                <?php echo $pagination_link['title']; ?><span class="sr-only"><?php _e('Current'); ?></span>
            </span>
            </li>
        <?php else: ?>
            <li class="page-item">
                <a class="page-link" data-page-number-dont-copy="<?php echo $pagination_link['attributes']['data-page-number']; ?>" href="<?php echo $pagination_link['attributes']['href']; ?>">
                    <?php echo $pagination_link['title']; ?>
                </a>
            </li>
        <?php endif; ?>
    <?php endforeach; ?>
</ul>
