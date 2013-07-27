<?php
 


api_expose('/mw/Notifications/delete');
api_expose('/mw/Notifications/save');
api_expose('/mw/Notifications/reset');





 
class Notifications  extends \Mw\Notifications{
    private $object;
 
    public function __construct($object = false) {
		if(!is_object($object)){
			  $this->object = $this;
		} else {
			  $this->object = $object;
		}
      
    }
 
    public function get() {
       
        echo 'bar';
    }
}