<?php

/*

type: layout

name: Default

description: Default


*/


?>
<?php if (isset($data) and is_array($data)): ?>
    <div class="mw-breadcrumb d-flex align-items-center gap-1">
        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" height="16" viewBox="0 -960 960 960" width="16"><path d="M240-200h120v-240h240v240h120v-360L480-740 240-560v360Zm-80 80v-480l320-240 320 240v480H520v-240h-80v240H160Zm320-350Z"/></svg>        <a class="text-decoration-none" href="<?php print  $homepage['url']; ?>">

            <?php print $homepage['title']; ?>

        </a> /

        <?php foreach ($data as $item): ?>
            <?php if (!($item['is_active'])): ?>
                <a class="text-decoration-none" href="<?php print($item['url']); ?>"><?php print($item['title']); ?></a> /
            <?php else: ?>
                <span class="mw-breadcrumb-current"> <?php print ($item['title']); ?> </span>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
