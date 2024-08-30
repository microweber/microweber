<?php

/*

type: layout

name: Saas

description: Saas


*/



?>

<?php if (isset($data) and is_array($data)): ?>
    <nav  aria-label="breadcrumb" class="mw-breadcrumb d-flex justify-content-center align-items-center gap-1">
        <ol class="breadcrumb justify-content-center wow fadeInUp delay-0-4s">
            <li class="breadcrumb-item">
                <a href="<?php print  $homepage['url']; ?>">
                    <?php print $homepage['title']; ?>
                </a>
            </li>

            <?php foreach ($data as $item): ?>
                <?php if (!($item['is_active'])): ?>
                    <li class="breadcrumb-item">
                        <a class="text-decoration-none" href="<?php print($item['url']); ?>"><?php print($item['title']); ?></a>
                    </li>
                <?php else: ?>
                    <li class="breadcrumb-item active">
                        <?php print ($item['title']); ?>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ol>
    </nav>
<?php endif; ?>
