<?php


namespace Microweber\Adapters\Orm;

class NotOrm
{



    public function __construct($app=null)
    {
        $connection = new PDO("mysql:dbname=software", "ODBC");
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        $connection->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
        $software = new NotORM($connection);
    }


}
