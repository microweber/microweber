<?php

$user_id = $params['user_id'];
$loginAttempts = \Microweber\App\LoginAttempt::where('user_id', $user_id)
    ->orderBy('time', 'desc')
    ->take(40)
    ->get();
?>

<div class="mw-ui-box mw-ui-box-content" data-view="">
    <table class="mw-ui-table mw-full-width mw-ui-table-basic">
        <thead>
        <tr>
            <th><?php echo _e('Username'); ?></th>
            <th><?php echo _e('E-mail'); ?></th>
            <th><?php echo _e('IP'); ?></th>
            <th><?php echo _e('Date'); ?></th>
            <th><?php echo _e('Status'); ?></th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <td><?php echo _e('Username'); ?></td>
            <td><?php echo _e('E-mail'); ?></td>
            <td><?php echo _e('IP'); ?></td>
            <td><?php echo _e('Date'); ?></td>
            <td><?php echo _e('Status'); ?></td>
        </tr>
        </tfoot>
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
                    <?php echo _e('Success login'); ?>
                <?php else: ?>
                    <?php echo _e('Failed login'); ?>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php else: ?>
        <tr><td rowspan="5"><?php echo _e('No login attempts found for this user.'); ?></td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>