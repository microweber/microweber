<?php



class MwSession {


    public $savePath;

    function __construnct(){
        if( $this->savePath == false){
       $this->savePath = DBPATH_FULL.'sessions'.DS;
       }
         if (!is_dir($this->savePath)) {
            mkdir_recursive($this->savePath);
        }
    }

    function open($savePath, $sessionName)
    {


        $this->savePath = $savePath;
        if (!is_dir($this->savePath)) {
            mkdir($this->savePath);
        }

        return true;
    }

    function close()
    {
        return true;
    }

    function read($id)
    {
        return (string)@file_get_contents($this->savePath."sess_$id");
    }

    function write($id, $data)
    {

        if(!is_dir($this->savePath)){
            mkdir_recursive($this->savePath);

        }
         if(!is_file($this->savePath.'index.php')){
            @touch($this->savePath.'index.php');
         }



        return file_put_contents($this->savePath."sess_$id", $data) === false ? false : true;
    }

    function destroy($id)
    {
        $file = $this->savePath."sess_$id";
        if (file_exists($file)) {
            unlink($file);
        }

        return true;
    }

    function gc($maxlifetime)
    {
        foreach (glob($this->savePath."sess_*") as $file) {
            if (filemtime($file) + $maxlifetime < time() && file_exists($file)) {
                unlink($file);
            }
        }

        return true;
    }
}
