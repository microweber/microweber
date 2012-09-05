<?php
class controller_home Extends Controller
{
	// Welcome page!
	public function index()
	{
		$this->content = new View('home/index');
	}
	
	// Show how to pass parameters over the URI
	public function param($value1 = NULL, $value2 = NULL)
	{
		$view = new View('home/param');
		
		$view->value1 = $value1;
		$view->value2 = $value2;
		// OR
		/*
		$view->set(array(
			'value1' => $value1, 
			'value2' => $value2
		));
		*/
		
		$this->content = $view;
	}
	
	// Example exception handling
	public function exception()
	{
		$this->bad_method('Some arguments here...');
	}
	
	protected function bad_method($value)
	{
		throw new Exception('Not a flying toy!');
	}
	
	// Example using cookies for session storage
	public function session()
	{
		// Start session
		$_SESSION = Cookie::get('session');
		
		$this->content = dump($_SESSION);
		
		// Create a number counter
		if(isset($_SESSION['number']))
		{
			$_SESSION['number'] += 1;
		}
		else
		{
			$_SESSION['number']=1;
		}
		
		// Create token (for use in forms)
		$_SESSION['token'] = Cookie::token();
		
		// Save session
		Cookie::set('session',$_SESSION);
	}
}
