<?php

/*

type: layout

name: Skin 1

description: Skin 1


*/


?>
<?php if (isset($data) and is_array($data)): ?>
    <div class="mw-breadcrumb">
        <a href="<?php print  $homepage['url']; ?>"><?php print $homepage['title']; ?></a>

        <?php foreach ($data as $item): ?>
            <?php if (!($item['is_active'])): ?>
                <a href="<?php print($item['url']); ?>"><?php print(($item['title'])); ?></a>
            <?php else: ?>
                <span class="mw-breadcrumb-current"> <?php print($item['title']); ?> </span>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
