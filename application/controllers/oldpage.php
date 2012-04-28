<?php

class Oldpage extends CI_Controller {

	function __construct() {
		parent :: __construct();
		require_once (APPPATH . 'controllers/default_constructor.php');
	}

	function index() {
//var_dump($_REQUEST);

		
		
		$n = $_REQUEST['n'];
		$n = intval($n);
	//var_dump($n);
		$gogogo = false;
		switch ($n){
			case '1':
				
				break;
				
				
				
				
				case 26311:
					case '26311':
					$gogo =site_url('имоти-българия');
					break;
				case 26314:
					$gogo =site_url('имоти-в-света');
					break;
					
					case 26315:
					$gogo =site_url('имоти-в-света/categories:Луксозни имоти');
					break;
					
						case 26316:
					$gogo =site_url('цени-на-имоти');
					break;
							case 26317:
					$gogo =site_url('имоти-българия/categories:Агенции за имоти');
					break;
								case 26736:
					$gogo =site_url('строителство/category:Строители');
					break;
					
					
					
					
					
					
					
					
				case 26370:
					case '26370':
					$gogo =site_url('имоти-българия/categories:Национални търсачки');
					break;
				
				
				case 26579:
					case '26579':
					$gogo =site_url('новини');
					break;
				
				case 27192:
					case '27192':
					$gogo =site_url('имоти-българия/categories:Имоти в София');
					break;
				
				
				
				case 28760:
					case '28760':
					//
					$gogo =site_url('новини/category:Новини свързани с цените на недвижимите имоти');
					break;
					
					case 26316:
					case '26316':
					//http://test.look-estates.com/цени-на-имоти/category:Цени - статистически данни от търсачките на недвижими имоти
					$gogo =site_url('цени-на-имоти/category:Цени - статистически данни от търсачките на недвижими имоти');
					break;
				
				
				
				case 30639: 
					case '30639':
					
					// http://test.look-estates.com/медии/category:Национални медии
					$gogo =site_url('медии/category:Национални медии');
					break;
				
					case 26737: 
				
					// http://test.look-estates.com/медии/category:Национални медии
					$gogo =site_url('интериор');
					break;
				
					case 26891: 
					$gogo =site_url('contact-us');
					break;
					case 26899: 
					$gogo =site_url('имоти-българия/агенции-имоти-от-софия');
					break;
					case 26900: 
					$gogo =site_url('имоти-българия/агенции-имоти-от-варна');
					break;
					
					case 26901: 
					$gogo =site_url('имоти-българия/агенции-имоти-от-пловдив');
					break;
					
					case 26902: 
					$gogo =site_url('имоти-българия/агенции-имоти-от-бургас');
					break;
					
					case 26903: 
					$gogo =site_url('имоти-българия/агенции-имоти-от-русе');
					break;
					
					case 26904: 
					$gogo =site_url('имоти-българия/агенции-имоти-от-в.-търново');
					break;
					
					
					case 26905: 
					$gogo =site_url('имоти-българия/агенции-имоти-от-в.-търново');
					break;
					
					
					
				default:
					$data = FALSE;
					$data['the_old_id'] = $n;
					$data = $this->
content_model->getContent($data);
					$url = $this->content_model->getContentURLById($data[0]['id']);
					$gogo =$url;
					//var_dump($url);
					
					
				break;
			
			
			
		}
		
		header ( 'Location: ' . $gogo );
		
exit;
//$ulr = $this->uri->uri_to_assoc();
//var_dump($ulr);


//var_dump($this->uri->segment_array());

	}
}




/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */