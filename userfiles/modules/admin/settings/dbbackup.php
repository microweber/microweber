<?
$tables = array('a_table','another_table_etc');
foreach($tables as $table){
            
            //set the preferences and choose the table
            $prefs = array(
                            'tables'      => array($table),
                            'format'      => 'txt',
                            'add_drop'    => TRUE,
                            'add_insert'  => TRUE,
                            'newline'     => "\n"
                          );
            $backup = $this->dbutil->backup($prefs);
            
            //write to file
            write_file("$directory$table.sql", $backup);
        } 

?>