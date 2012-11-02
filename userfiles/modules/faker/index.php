Faker<?
if(is_admin() == false){
error('must be admin');	
}

 
require_once $config['path_to_module'].'src'.DS.'autoload.php';
// alternatively, use another PSR-0 compliant autoloader (like the Symfony2 ClassLoader for instance)

// use the factory to create a Faker\Generator instance
$faker = Faker\Factory::create();
for ($i=0; $i < 1000; $i++) { 

$data = array();
$data ['categories'] = '214,217';
$data ['content_type'] = 'post';	
$data ['subtype'] = 'post';	
$data ['id'] = 0;

$data ['parent'] = 3543;
$data ['description'] = $faker->text($maxNbChars = 200);	
$data ['title'] = $faker->sentence($nbWords = 6) ; 
$data ['url'] = $data ['title'].'-'.$i.rand();
//$data = save_content($data);
//d($data ); 
 //echo 
 // echo 
}

?>