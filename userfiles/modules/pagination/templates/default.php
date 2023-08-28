<?php
/*
type: layout
name: Default
description: Default
*/
?>

<ul class="pagination d-flex justify-content-center align-items-center">


    <?php
    foreach ($pagination_links as $pagination_link):
        ?>

        <?php if ($pagination_link['attributes']['current']): ?>
        <li class="page-item active">
            <a class="page-link">
                <?php echo $pagination_link['title']; ?>
            </a>
        </li>
    <?php else: ?>

        <li class="page-item">
            <a class="page-link" href="<?php echo $pagination_link['attributes']['href']; ?>">
                <?php echo $pagination_link['title']; ?>
            </a>
        </li>
    <?php endif; ?>

    <?php
    endforeach;
    ?>
  
</ul>
