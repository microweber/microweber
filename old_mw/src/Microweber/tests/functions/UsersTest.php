<?php

namespace FunctionsTest;


class UsersTest extends \PHPUnit_Framework_TestCase
{
    public function testLogged()
    {



        $verify_admin = is_admin();
        $verify_logged = is_logged();

        //PHPUnit
        $this->assertEquals(false, $verify_admin);
        $this->assertEquals(false, $verify_logged);

    }
    public function testAddUser()
    {

        $rand = uniqid();
        $expected_username = "unit_test_user" ;
        $expected_email = "me@domain-" . $rand . '.com';
        $expected_pass = uniqid() . rand() . uniqid();

        $data = array();
        $data['username'] = $expected_username;
        $data['email'] = $expected_email;
        $data['password'] = $expected_pass;
        $data['is_active'] = 'y';
        $new_user = save_user($data);

        $verify_user = get_user_by_id($new_user);

        //PHPUnit
        $this->assertEquals(true, intval($new_user) > 0);
        $this->assertEquals($expected_username, $verify_user['username']);
        $this->assertEquals($expected_email, $verify_user['email']);
        $this->assertEquals(md5($expected_pass), $verify_user['password']);
    }

    public function testDeleteUser()
    {
        $data = array();
        $data['username'] = 'unit_test_user';

        $users = get_users($data);

        if (is_array($users)) {
            foreach ($users as $user) {
                $delete_user = delete_user($user['id']);
                $verify_user = get_user_by_id($user['id']);

                //PHPUnit
                $this->assertEquals(false, $verify_user);
            }
        }

        //PHPUnit
        $this->assertEquals(true, is_array($users));
    }


}