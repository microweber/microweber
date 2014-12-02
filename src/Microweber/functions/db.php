<?php



function get_table_prefix(){
    return  Config::get('database.connections.mysql.prefix');

}


function get($table_name_or_params,$params = null){



    if($params === null){
        $params = $table_name_or_params;
    } else {
        if($params != false){
            $params = parse_params($params);
        } else {
            $params = array();
        }
        $params['table'] = $table_name_or_params;
    }


    return  mw()->db_model->filter($params);

}



/**
 * Saves data to any db table
 *
 * Function parameters:
 *
 *     $table - the name of the db table, it adds table prefix automatically
 *     $data - key=>value array of the data you want to store
 *
 * @since 0.1
 * @link http://microweber.com/docs/functions/save
 *
 * @param $table
 * @param $data
 * @return array The database results
 */
function save($table, $data)
{

   // $DbModel = new DbModel();
    //return $DbModel->save_data($table, $data);
    return  mw()->db_model->save_item($table,$data);



}