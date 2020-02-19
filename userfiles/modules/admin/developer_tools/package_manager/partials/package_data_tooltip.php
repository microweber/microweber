<table cellspacing="0" cellpadding="0" class="mw-ui-table mw-ui-table-basic" width="100%">
    <tbody>

    <tr>
        <td><?php _e('License'); ?></td>
        <td>
            <?php if ($license): ?>
                <?php print $license; ?>
            <?php else: ?>
                <?php _e(' N/A'); ?>
            <?php endif; ?>
        </td>
    </tr>

    <tr>
        <td><?php _e('Website'); ?></td>
        <td>
            <?php if (isset($item['homepage'])): ?>
                <a href="<?php print $item['homepage']; ?>" target="_blank"
                   class="mw-blue package-ext-link"><?php print $item['homepage']; ?></a>
            <?php else: ?>
                <?php _e(' N/A'); ?>
            <?php endif; ?>
        </td>
    </tr>

    <tr>
        <td><?php _e('Author'); ?></td>
        <td><img src="<?php print $author_icon; ?>" style="max-height: 16px;"/> <?php print $author ?></td>
    </tr>

    <?php if (isset($item['latest_version']['changelog'])): ?>
        <tr>
            <td><?php _e('Changelog'); ?></td>
            <td><a href="<?php print $item['latest_version']['changelog']; ?>" target="_blank">Click to
                    open</a></td>
        </tr>
    <?php endif; ?>

    <tr>
        <td><?php _e('Release date'); ?></td>
        <td><?php print date('d M Y', strtotime($item['latest_version']['release_date'])) ?></td>
    </tr>


    <tr style="display: none">
        <td><?php _e('Keywords'); ?></td>
        <td>
            <?php if (isset($item['keywords'])): ?>
                <?php print implode(", ", $item['keywords']); ?>
            <?php endif; ?>
        </td>
    </tr>

    <tr style="display: none">
        <td><?php _e('Support Source'); ?></td>
        <td>
            <?php if (isset($item['support']) AND isset($item['support']['source'])): ?>
                <?php print $item['support']['source']; ?>
            <?php endif; ?>
        </td>
    </tr>

    <tr style="display: none">
        <td><?php _e('Support Issues'); ?></td>
        <td>
            <?php if (isset($item['support']) AND isset($item['support']['issues'])): ?>
                <?php print $item['support']['issues']; ?>
            <?php endif; ?>
        </td>
    </tr>
    </tbody>
</table>