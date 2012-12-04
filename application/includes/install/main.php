<!DOCTYPE html>
<!--[if lt IE 7]><html class="ie9 ie8 ie7 ie6" lang="en"><![endif]-->
<!--[if IE 7]><html class="ie9 ie8 ie7" lang="en"><![endif]-->
<!--[if IE 8]><html class="ie9 ie8" lang="en"><![endif]-->
<!--[if IE 9]><html class="ie9" lang="en"><![endif]-->
<!--[if gt IE 9]><!--><html lang="en"><!--<![endif]-->

<head>
<title>Microweber Configuration</title>
<meta charset="utf-8">
<link type="text/css" rel="stylesheet" media="all" href="<? print INCLUDES_URL; ?>api/api.css"/>
<script type="text/javascript" src="<? print INCLUDES_URL; ?>js/jquery-latest.js"></script>
<script src="<? print INCLUDES_URL; ?>install/bootstrap.js"></script>
<? $rand = uniqid(); ?>
<script  type="text/javascript">

 
 

$(document).ready(function(){
	
	 
	 
	 $('#form_<? print $rand ?>').submit(function() { 

  $data = $('#form_<? print $rand ?>').serialize();
//  alert($data);
  //alert('<? print url_string() ?>');
  
  $.post("<? print url_string() ?>", $data,
   function(data) {
	    $('.mw_log').html('');
	   if(data != undefined){
		 if(data == 'done'){
			 window.location.href= '<? print site_url('admin') ?>'
		 } else {
		  $('.mw_log').html(data);	
		 }
		   
	   }
	 
    
   });
   
   
   return false;

	 });

 
 
 });
   
 
</script>
</head>
<body>
<div class="wrapper">
  <div class="page"> 
    <!--<nav class="nav">
                  <ul>
                    <li><a href="{{author-url}}">About the Author</a></li>
                    <li><a href="{{project-url}}">Documentation</a></li>
                    <li class="download"><a href="{{project-download-url}}">Download <span class="version">v1.0</span></a></li>
                  </ul>
                </nav>-->
    <header class="header">
      <h1>Microweber Setup <span class="version">v1.0</span></h1>
      <p>Welcome to the Microweber configuration panel, here you can setup your website quickly.
      <p>
      <div class="custom-nav"></div>
    </header>
    <div class="sep"><span class="left-arrow arrow"></span><span class="right-arrow arrow"></span></div>
    <div class="demo" id="demo-one">
      <div class="description">
        <div class="mw_log"> </div>
        <? if ($done == false): ?>
        <h2>Database setup</h2>
        <form method="GET" id="form_<? print $rand ?>">
          <table  cellspacing="5" cellpadding="5">
            <tr>
              <td>Database host</td>
              <td><input name="DB_HOST" <? if(isset($data['db'])== true and isset($data['db']['host'])== true): ?> value="<? print $data['db']['host'] ?>" <? endif; ?> /></td>
            </tr>
            <tr>
            <tr>
              <td>Database username</td>
              <td><input name="DB_USER" <? if(isset($data['db'])== true and isset($data['db']['user'])== true): ?> value="<? print $data['db']['user'] ?>" <? endif; ?> /></td>
            </tr>
            <tr>
            <tr>
              <td>Database password</td>
              <td><input name="DB_PASS" <? if(isset($data['db'])== true and isset($data['db']['pass'])== true): ?> value="<? print $data['db']['pass'] ?>" <? endif; ?> /></td>
            </tr>
            <tr>
              <td>Database name</td>
              <td><input name="dbname" <? if(isset($data['db'])== true and isset($data['db']['dbname'])== true): ?> value="<? print $data['db']['dbname'] ?>" <? endif; ?> /></td>
            </tr>
             <tr>
              <td>Table prefix</td>
              <td><input name="table_prefix" <? if(isset($data['table_prefix'])== true and isset($data['table_prefix'])!= ''): ?> value="<? print $data['table_prefix'] ?>" <? endif; ?> /></td>
            </tr>
            <tr>
              <td>Time zone</td>
              <td><? static $regions = array(
    
	
	'Universal time' => DateTimeZone::UTC,
	'America' => DateTimeZone::AMERICA,
	'Europe' => DateTimeZone::EUROPE,
	'Asia' => DateTimeZone::ASIA,
	'Pacific' => DateTimeZone::PACIFIC,
	'Africa' => DateTimeZone::AFRICA,
    'Atlantic' => DateTimeZone::ATLANTIC,
	'Indian' => DateTimeZone::INDIAN,
  	'Antarctica' => DateTimeZone::ANTARCTICA
   
    
);

foreach ($regions as $name => $mask) {
    $tzlist[$name] = DateTimeZone::listIdentifiers($mask);
}
 //print_r($tzlist);
 ?>
                <? if(isarr($tzlist )): ?>
                <select name="default_timezone">
                  <? foreach($tzlist  as $key=> $tzlist1): ?>
                  <optgroup label="<? print $key ?>">
                  <? foreach($tzlist1  as $key1=> $item1): ?>
                  <option value="<? print $item1 ?>" <? if(isset($data['default_timezone'])== true and isset($data['default_timezone'])== $item1): ?> selected <? endif; ?>><? print $item1 ?></option>
                  <? endforeach ; ?>
                  </optgroup>
                  <? endforeach ; ?>
                </select>
                <? endif; ?></td>
            </tr>
            <tr>
              <td>Save</td>
              <td><input type="submit" name="submit"  value="install">
                <input name="IS_INSTALLED" type="text" value="no" id="is_installed_<? print $rand ?>">
                
                <!--       <input type="submit" name="submit"  value="install" >--></td>
            </tr>
          </table>
        </form>
        <? else: ?>
        <h2>Done, </h2>
        <a href="<? print site_url('admin') ?>">click here to to to admin</a> <a href="<? print site_url() ?>">click here to to to site</a>
        <? endif; ?>
      </div>
      <!-- .description --> 
      
    </div>
    <!-- .demo --> 
    
  </div>
  <!-- .page --> 
  
</div>
<!-- .wrapper -->

</body>
</html>