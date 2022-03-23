<?php
/*
type: layout
name: MW
description: MW
*/
?>

<div class="mw-paging d-flex justify-content-center">
    <?php
    foreach ($pagination_links as $pagination_link):
        ?>

        <?php if ($pagination_link['attributes']['current']): ?>
        <a class="active">
            <?php echo $pagination_link['title']; ?>
        </a>
    <?php else: ?>
            <a href="<?php echo $pagination_link['attributes']['href']; ?>">
                <?php echo $pagination_link['title']; ?>
            </a>
    <?php endif; ?>

    <?php
    endforeach;
    ?>
</div>
