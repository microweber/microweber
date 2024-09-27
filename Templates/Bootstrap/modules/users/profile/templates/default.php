<?php $user = get_user_by_id(user_id()); ?>
    <script>
        saveuserdata = function () {
            var data = mw.serializeFields('#user-data');
            if (data.password != data.password2) {
                mw.$('#errnotification').html('Passwords do not match').show();
                return false;
            } else {
                mw.$('#errnotification').hide();

                if (data.password == '') {
                    delete data.password;
                    delete data.password2;
                }
            }
            mw.tools.loading('#user-data')
            $.post("<?php print api_url(); ?>save_user", data, function () {
                mw.tools.loading('#user-data', false);
            });
        }
    </script>

    <form class="col-12 mb-5 mx-auto" method="post" id="user-data">
        <div class="my-3">
            <h5 class="mb-2"><?php _lang('Edit profile', "templates/bootstrap5"); ?></h5>
            <small class="text-muted d-block mb-2"><?php _lang('From this page you can edit your profile', "templates/bootstrap5"); ?></small>
        </div>
        <div class="mw-ui-box mw-ui-box-important mw-ui-box-content" id="errnotification" style="display: none;margin-bottom: 12px;"></div>

        <div class="form-group">
            <label class="control-label"><?php _lang("Username", "templates/bootstrap5"); ?></label>
            <input class="form-control input-lg" type="text" name="username" value="<?php print $user['username']; ?>" placeholder="<?php _lang('Username', "templates/bootstrap5"); ?>">
        </div>

        <div class="form-group">
            <label class="control-label"><?php _lang("Email", "templates/bootstrap5"); ?></label>
            <input class="form-control input-lg" type="email" name="email" value="<?php print $user['email']; ?>" placeholder="<?php _lang('Email', "templates/bootstrap5"); ?>">
        </div>

        <div class="form-group">
            <label class="control-label"><?php _lang("First name", "templates/bootstrap5"); ?></label>
            <input class="form-control input-lg" type="text" name="first_name" value="<?php print $user['first_name']; ?>" placeholder="<?php _lang('First name', "templates/bootstrap5"); ?>">
        </div>

        <div class="form-group">
            <label class="control-label"><?php _lang("Last name", "templates/bootstrap5"); ?></label>
            <input class="form-control input-lg" type="text" name="last_name" value="<?php print $user['last_name']; ?>" placeholder="<?php _lang('Last name', "templates/bootstrap5"); ?>">
        </div>

        <div class="form-group">
            <label class="control-label"><?php _lang("New Password", "templates/bootstrap5"); ?></label>
            <input class="form-control input-lg" type="password" name="password" placeholder="<?php _lang('New Password', "templates/bootstrap5"); ?>">
        </div>

        <div class="form-group">
            <label class="control-label"><?php _lang("Confirm Password", "templates/bootstrap5"); ?></label>
            <input class="form-control input-lg" type="password" name="password2" placeholder="<?php _lang('Confirm Password', "templates/bootstrap5"); ?>">
        </div>

        <button type="button" class="btn btn-default btn-lg btn-block m-t-10" onclick="saveuserdata()"><?php _lang('Save', "templates/bootstrap5"); ?></button>
    </form>

