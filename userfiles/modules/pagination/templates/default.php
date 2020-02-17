<?php
/*
type: layout
name: Default
description: Default
*/
?>

<ul>
    <?php
    foreach ($pagination_links as $pagination_link):
        ?>

        <?php if ($pagination_link['attributes']['current']): ?>
        <li class="active">
            <span>
                 <?php echo $pagination_link['title']; ?>
            </span>
        </li>
    <?php else: ?>

        <li class="page-item">
            <a href="<?php echo $pagination_link['attributes']['href']; ?>">
                <?php echo $pagination_link['title']; ?>
            </a>
        </li>
    <?php endif; ?>

    <?php
    endforeach;
    ?>
</ul>