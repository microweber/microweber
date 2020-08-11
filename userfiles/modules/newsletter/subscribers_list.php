<?php only_admin_access(); ?>
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
                <th scope="col" width="40px"><?php _e('#'); ?></th>
                <th scope="col"><?php _e('Name'); ?></th>
                <th scope="col"><?php _e('E-mail'); ?></th>
                <th scope="col"><?php _e('Subscribed at'); ?></th>
                <th scope="col"><?php _e('Subscribed'); ?></th>
                <th scope="col" width="200px">&nbsp;</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($subscribers as $key => $subscriber): ?>
                <tr>
                    <td data-label="<?php _e('#'); ?>"><?php print $key + 1; ?></td>
                    <td data-label="<?php _e('Name'); ?>"><?php print $subscriber['name']; ?></td>
                    <td data-label="<?php _e('E-mail'); ?>"><?php print $subscriber['email']; ?></td>
                    <td data-label="<?php _e('Subscribed at'); ?>"><?php print $subscriber['created_at']; ?></td>
                    <td data-label="<?php _e('Subscribed'); ?>">
                        <?php
                        if ($subscriber['is_subscribed']) {
                            _e('Yes');
                        } else {
                            _e('No');
                        }
                        ?>
                    </td>
                    <td>
                        <button class="mw-ui-btn mw-ui-btn-info mw-ui-btn-medium" onclick="edit_subscriber('<?php print $subscriber['id']; ?>')"><?php _e('Edit'); ?></button>
                        <a class="mw-ui-btn mw-ui-btn-icon mw-ui-btn-important mw-ui-btn-outline mw-ui-btn-medium" href="javascript:;" onclick="delete_subscriber('<?php print $subscriber['id']; ?>')"> <span class="mw-icon-bin"></span> </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <b>No Subscribers found.</b>
<?php endif; ?>