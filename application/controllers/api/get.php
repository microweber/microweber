<?php

class Get extends CI_Controller {

    function __construct() {

        parent :: __construct();

        require_once (APPPATH . 'controllers/default_constructor.php');
    //  require_once (APPPATH . 'controllers/api/default_constructor.php');

    

    }

    function index() {
       phpinfo();
    }
     

}



