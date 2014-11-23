<?php

class Testme extends Model
{


    public static $_table = 'users';


    public function __construct()
    {
        self::$_table = 'my_users';

    }

}