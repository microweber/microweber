<div>
    <div class="card" data-view="">
        <table class="mw-ui-table mw-full-width mw-ui-table-basic">
            <thead>
            <tr>
                <th><?php _e('Username'); ?></th>
                <th><?php _e('E-mail'); ?></th>
                <th><?php _e('IP'); ?></th>
                <th><?php _e('Date'); ?></th>
                <th><?php _e('Status'); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            if (!$loginAttempts->isEmpty()):
            foreach($loginAttempts as $attempt):
            ?>
            <tr>
                <td><?php echo $attempt->username; ?></td>
                <td><?php echo $attempt->email; ?></td>
                <td><?php echo $attempt->ip; ?></td>
                <td><?php echo date("Y-m-d H:i:s", $attempt->time); ?></td>
                <td>
                    <?php if($attempt->success):?>
                    <?php _e('Success login'); ?>
                <?php else: ?>
                    <?php _e('Failed login'); ?>
                <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php else: ?>
            <tr><td rowspan="5"><?php _e('No login attempts found for this user.'); ?></td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
