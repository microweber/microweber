<h2>My content</h2>

<?php print $content; ?>





<?php if (isset($pages) and !empty($pages)): ?>
    <h2>My pages</h2>

    <?php foreach ($pages as $item): ?>
        <?php print $item['title'] ?> <br>

    <?php endforeach; ?>


<?php endif; ?>