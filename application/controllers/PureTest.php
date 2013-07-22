<?php
if (defined("MW_NO_BASE") == false) {
    define("MW_NO_BASE", 111111111);
}

//pure test without loading the mw functions
class PureTest  
{
    function __construct()
    {

    }

    function test()
    {

        
    }

    function index()
    {

        //you can use MW as plain ol MVC;

       debug_info();


    }


}


class Test1{
    public static function test(){
         $j = 0;
        for($i=0; $i<=10000; $i++)
            $j += $i;       
    }   
}

class Test2{
    public function test() {
         $j = 0;
        for ($i=0; $i<=10000; $i++){
            $j += $i;
        }
    }

}
function aaaa(){
      $j = 0;
        for ($i=0; $i<=10000; $i++){
            $j += $i;
        }
}

$time_start = microtime();
$test1 = new Test2();
for($i=0; $i<=1000;$i++)
    $test1->test();
$time_end = microtime();

$time = $time_end - $time_start;
var_dump($time);

$time_start = microtime();
for($i=0; $i<=1000;$i++)
    Test1::test();
$time_end = microtime();    

$time = $time_end - $time_start;
 
var_dump($time);



$time_start = microtime();
for($i=0; $i<=1000;$i++)
   aaaa();
$time_end = microtime();    

$time = $time_end - $time_start;
 
var_dump($time);