<?php



function get_table_prefix(){
    return  Config::get('database.connections.mysql.prefix');

}