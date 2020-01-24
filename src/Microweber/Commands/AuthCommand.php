<?php

namespace Microweber\Commands;

use Illuminate\Console\Command;

class AuthCommand extends Command
{
    protected $name = 'microweber:generate-admin-login-token';
    protected $description = 'Temporary login link generator.';

    public function fire()
    {
        // Generate token
        $generateToken = str_random(123);

        // Find first admin
        $firstAdmin = get_users('is_admin=1&single=1&is_active=1');
        if (!$firstAdmin) {
            return false;
        }

        $saveToken = array();
        $saveToken['token'] = $generateToken;
        $saveToken['user_id'] = $firstAdmin['id'];
        $saveToken['created_at'] = date('Y-m-d H:i:s');
        $saveToken['server_ip'] = user_ip();

        // Save temp token
        $save = db_save('users_temp_login_tokens', $saveToken);
        if ($save) {
            echo $generateToken;
        }
    }
}
