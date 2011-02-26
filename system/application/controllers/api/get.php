<?php

class Get extends Controller {

    function __construct() {

        parent::Controller ();

        require_once (APPPATH . 'controllers/default_constructor.php');
    //  require_once (APPPATH . 'controllers/api/default_constructor.php');

    

    }

    function index() {
       phpinfo();
    }
     

}



