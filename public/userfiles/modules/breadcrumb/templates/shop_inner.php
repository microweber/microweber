<?php

/*

type: layout

name: Shop inner

description: Shop inner


*/


?>
<?php if (isset($data) and is_array($data)): ?>
    <div class="mw-breadcrumb">
        <?php foreach ($data as $item): ?>
            <?php if (!($item['is_active'])): ?>
                <a class="text-decoration-none" href="<?php print($item['url']); ?>"><?php print($item['title']); ?></a> /
            <?php else: ?>
                <span class="mw-breadcrumb-current"> <?php print ($item['title']); ?> </span>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
