#!/usr/bin/php -q
<?php

set_time_limit('300');

include("geoip.inc");
include("geoipcity.inc");
define("GEOIP_COUNTRY_DATABASE",0);
define("GEOIP_REGION_DATABASE",1);
define("GEOIP_CITY_DATABASE",2);

class mainappc{
  var $dbfilename = array("/usr/local/share/GeoIP/GeoIP.dat","/usr/local/share/GeoIP/GeoIPRegion.dat","/usr/local/share/GeoIP/GeoIPCity.dat");
  function randomipaddress(){
    $result = "";
    for ($a = 0;$a < 4;$a++){
      if ($a > 0){$result = $result . ".";}
      $a2 = rand(1, 254);
      $result = $result . $a2;
    }
    return $result;
  }
  function ftime(){
    $a = gettimeofday();
    return $a[sec] + ($a[usec]*0.000001);
  }
  function testgeoipdatabase($type,$flags,$msg,$numlookups){
    $gi = geoip_open($this->dbfilename[$type],$flags);
    if ($gi == null){
      print "error: " . $this->dbfilename[$type] . " does not exist\n" ;
      return;
    }
    $t1 = $this->ftime();
    $i4 = 0;
    for ($i2 = 0;$i2 < $numlookups;$i2++){
      switch ($type) {
        case GEOIP_COUNTRY_DATABASE:
        geoip_country_code_by_addr($gi,$this->randomipaddress());  
        break;
        case GEOIP_REGION_DATABASE:
        geoip_region_by_addr($gi,$this->randomipaddress());  
        break;
        case GEOIP_CITY_DATABASE:
        GeoIP_record_by_addr($gi,$this->randomipaddress());
        break;
      }
    }
    $t2 = $this->ftime();
    $t3 = $t2-$t1;
    print $msg . "\n";
    print $numlookups . " lookups made in " . $t3 . " seconds \n"; 
    geoip_close($gi);
  }
}


$mainapp = new mainappc();


$mainapp->testgeoipdatabase(GEOIP_COUNTRY_DATABASE,GEOIP_STANDARD,"Geoip Country ",10000);
$mainapp->testgeoipdatabase(GEOIP_COUNTRY_DATABASE,GEOIP_MEMORY_CACHE,"Geoip Country with memory cache",10000);
$mainapp->testgeoipdatabase(GEOIP_REGION_DATABASE,GEOIP_STANDARD,"Geoip Region ",10000);
$mainapp->testgeoipdatabase(GEOIP_REGION_DATABASE,GEOIP_MEMORY_CACHE,"Geoip Region with memory cache",10000);
$mainapp->testgeoipdatabase(GEOIP_CITY_DATABASE,GEOIP_STANDARD,"Geoip City ",10000);
$mainapp->testgeoipdatabase(GEOIP_CITY_DATABASE,GEOIP_MEMORY_CACHE,"Geoip City with memory cache",10000);

?>
