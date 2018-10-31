<?php only_admin_access(); ?>
<script>
    mw.lib.require('font_awesome5');
</script>
<?php
$subscribers_params = array();
$subscribers_params['no_limit'] = true;
$subscribers_params['order_by'] = "created_at desc";
$subscribers = newsletter_get_subscribers($subscribers_params);
?>
<?php if ($subscribers): ?>
    <style>

        .table-responsive-per-row table {
            border: 1px solid #ccc;
            border-collapse: collapse;
            margin: 0;
            padding: 0;
            width: 100%;
            table-layout: fixed;
        }

        .table-responsive-per-row table caption {
            font-size: 1.5em;
            margin: .5em 0 .75em;
        }

        .table-responsive-per-row table tr {
            background-color: #f8f8f8;
            border: 1px solid #ddd;
            padding: .35em;
        }

        .table-responsive-per-row table th,
        .table-responsive-per-row table td {
            padding: .625em;
            text-align: center;
        }

        .table-responsive-per-row table th {
            font-size: .85em;
            letter-spacing: .1em;
            text-transform: uppercase;
        }

        @media screen and (max-width: 600px) {
            .table-responsive-per-row table {
                border: 0;
            }

            .table-responsive-per-row table caption {
                font-size: 1.3em;
            }

            .table-responsive-per-row table thead {
                border: none;
                clip: rect(0 0 0 0);
                height: 1px;
                margin: -1px;
                overflow: hidden;
                padding: 0;
                position: absolute;
                width: 1px;
            }

            .table-responsive-per-row table tr {
                border-bottom: 3px solid #ddd;
                display: block;
                margin-bottom: .625em;
            }

            .table-responsive-per-row table td {
                border-bottom: 1px solid #ddd;
                display: block;
                font-size: .8em;
                text-align: right;
                border: 0;
                height: 45px;
            }

            .table-responsive-per-row table td::before {
                /*
                * aria-label has no advantage, it won't be read inside a table
                content: attr(aria-label);
                */
                content: attr(data-label);
                float: left;
                font-weight: bold;
                text-transform: uppercase;
                padding-top: 12px;
            }

            .table-responsive-per-row table td:last-child {
                border-bottom: 0;
            }

            .date-holder {
                padding-top: 10px;
            }

            .actions-holder {
                padding-top: 7px;
            }
        }


    </style>
    <div class="table-responsive-per-row">
        <table width="100%" border="0" class="mw-ui-table layout-fixed">
            <thead>
            <tr>
                <th scope="col"><?php _e('Date'); ?></th>
                <th scope="col"><?php _e('Name'); ?></th>
                <th scope="col"><?php _e('Email'); ?></th>
                <th scope="col"><?php _e('Subscribed'); ?></th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($subscribers as $subscriber): ?>
                <tr id="newsletter-subscriber-<?php print $subscriber['id']; ?>">
                    <td data-label="Created at">
                        <div class="date-holder"><?php print $subscriber['created_at']; ?></div>
                    </td>
                    <td data-label="Name"><input type="text" class="mw-ui-field" name="name" value="<?php print $subscriber['name']; ?>"/></td>
                    <td data-label="E-mail"><input type="email" class="mw-ui-field" name="email" value="<?php print $subscriber['email']; ?>"/></td>
                    <td data-label="Is subscribed">
                        <select class="mw-ui-field" name="is_subscribed">
                            <option value="1" <?php if ($subscriber['is_subscribed']): ?>  selected <?php endif; ?> ><?php _e('Yes'); ?></option>
                            <option value="0" <?php if (!$subscriber['is_subscribed']): ?>  selected <?php endif; ?> ><?php _e('No'); ?></option>
                        </select>
                    </td>
                    <td data-label="Actions" style="min-width: 90px;">
                        <div class="actions-holder">
                            <input type="hidden" name="id" value="<?php print $subscriber['id']; ?>"/>
                            <button class="mw-ui-btn mw-ui-btn-notification mw-ui-btn-small" onclick="edit_subscriber('#newsletter-subscriber-<?php print $subscriber['id']; ?>')"><span class="fas fa-save"></span></button>
                            <a class="mw-ui-btn mw-ui-btn-icon mw-ui-btn-important mw-ui-btn-small" href="javascript:;" onclick="delete_subscriber('<?php print $subscriber['id']; ?>')"><span class="fas fa-trash"></span></a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
