<?php
only_admin_access();
/**
 * Dev: Bozhidar Slaveykov
 * Emai: bobi@microweber.com
 * Date: 11/18/2019
 * Time: 10:26 AM
 */
?>


<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>

<script type="text/javascript">
    $(document).ready(function () {


    });
</script>

<div id="mw-admin-content" class="admin-side-content">
    <div class="mw_edit_page_default" id="mw_edit_page_left">

        <div class="mw-ui-box mw-ui-box-content" data-view="">
            <table class="mw-ui-table mw-full-width mw-ui-table-basic">
                <thead>
                <tr>
                    <th><?php echo _e('Redirect from URL');?></th>
                    <th><?php echo _e('Redirect to URL');?></th>
                    <th><?php echo _e('Redirect Browsers');?></th>
                    <th><?php echo _e('Error code');?></th>
                    <th><?php echo _e('Enabled');?></th>
                    <th style="width:190px;"><?php echo _e('Action');?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                $browserRedirects = get_browser_redirects();
                if (!empty($browserRedirects)):
                foreach($browserRedirects as $browserRedirect):
                ?>
                <tr>
                    <td><?php echo $browserRedirect['redirect_from_url']; ?></td>
                    <td><?php echo $browserRedirect['redirect_to_url']; ?></td>
                    <td><?php echo $browserRedirect['redirect_browsers']; ?></td>
                    <td><?php echo $browserRedirect['error_code']; ?></td>
                    <td>
                        <?php if ($browserRedirect['active']): ?>
                        <?php echo _e('Yes');?>
                        <?php else: ?>
                            <?php echo _e('No');?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="javascript:;" onClick="editBrowserRedirect('<?php echo $browserRedirect['id']; ?>')" class="mw-ui-btn mw-ui-btn-medium"><?php echo _e('Edit');?></a>
                        <a href="javascript:;" onClick="deleteBrowserRedirect('<?php echo $browserRedirect['id']; ?>')" class="mw-ui-btn mw-ui-btn-medium"><?php echo _e('Delete');?></a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td colspan="5">No redirects found.</td>
                </tr>
                <?php endif; ?>

                </tbody>
            </table>
        </div>

    </div>
</div>
