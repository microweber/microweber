<?php



function get_table_prefix(){
    return  Config::get('database.connections.mysql.prefix');

}


function get($params){
    $DbModel = new DbModel();
    return  $DbModel->dsd($params);

}