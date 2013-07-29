<?php

namespace Microweber\Utils;

/**
 * 
 * 
 * 
 * 
 * GOT THIS FUNCTION FROM HERE: http://www.php.net/manual/en/function.register-shutdown-function.php#100000
 * ALL CREDITS GO TO THE AUTHOR emanueledelgrande@email.it
 * 
 *
 * A lot of useful services may be delegated to this useful trigger.
 * It is very effective because it is executed at the end of the
 * script but before any object destruction, so all instantiations are still alive.
 * Here's a simple shutdown events manager class which allows to manage either functions or static/dynamic methods, 
 * with an indefinite number of arguments without using any reflection, 
 * availing on a internal handling through func_get_args() and call_user_func_array() specific functions:
 * 
 * 
 * 
 * 
 * 
 * 
 *A simple application:

 
// a generic function
function say($a = 'a generic greeting', $b = '') {
    echo "Saying {$a} {$b}<br />";
}

	$scheduler = new \Microweber\Utils\Events();

 // schedule a global scope static function from namespaced object:
 $scheduler -> registerShutdownEvent("\admin\backup\api::bgworker");

// schedule a global scope function:
$scheduler->registerShutdownEvent('say', 'hello!');

// try to schedule a dynamic method:
$scheduler->registerShutdownEvent(array($scheduler, 'dynamicTest'));
// try with a static call:
$scheduler->registerShutdownEvent('scheduler::staticTest');

 

* It is easy to guess how to extend this example in a more complex context 
* in which user defined functions and methods should be handled according to the priority depending on specific variables.
* Hope it may help somebody.
* Happy coding! 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 

 * @link		Original is from here http://www.php.net/manual/en/function.register-shutdown-function.php#100000

 * @version     1.0.0
 */
class Events {

	private $callbacks;
	// array to store user callbacks

	public function __construct() {
		$this->callbacks = array();
		register_shutdown_function(array($this, 'callRegisteredShutdown'));
	}

	public function registerShutdownEvent() {
		$callback = func_get_args();

		if (empty($callback)) {
			trigger_error('No callback passed to ' . __FUNCTION__ . ' method', E_USER_ERROR);
			return false;
		}
		if (!is_callable($callback[0])) {
			trigger_error('Invalid callback passed to the ' .$callback[0]. __FUNCTION__ . ' method', E_USER_ERROR);
			return false;
		}
		$this->callbacks[] = $callback;
		return true;
	}

	public function callRegisteredShutdown() {
		foreach ($this->callbacks as $arguments) {
			$callback = array_shift($arguments);
			call_user_func_array($callback, $arguments);
		}
	}

	// test methods:
	public function dynamicTest() {
		echo '_REQUEST array is ' . count($_REQUEST) . ' elements long.<br />';
	}

	public static function staticTest() {
		echo '_SERVER array is ' . count($_SERVER) . ' elements long.<br />';
	}

}
