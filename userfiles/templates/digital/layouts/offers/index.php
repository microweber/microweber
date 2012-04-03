<?php

/*

type: layout

name: Home layout

description: Home site layout









*/



?>
<? include TEMPLATE_DIR. "header.php"; ?>
<? 

$cities =  TEMPLATE_DIR. "cities.txt";

$data = file_get_contents($cities ); //read the file
$convert = explode("\n", $data ); //create array separate by new line

 $url_city = url_param('city');
 
if($url_city != false){
	
	$convert  = array();
	$convert[] = urldecode($url_city);
}
?>

<div id="container">
  <div id="content">
    <div class="wrapper"></div>
    <div class="c">&nbsp;</div>
    <div id="greyC">
      <div class="wrapper">
        <div class="c" style="height:24px;">&nbsp;</div>
        <? foreach($convert as $city): ?>
        
      
        
        <div class="b">
          <div class="bt"> </div>
          <div class="bm">
            <div id="ctrl"></div>
            <div class="c" style="height:20px; padding-left:15px;">
              <h3 class="bold">Offers in <? print $city ?></h3>
            </div>
            <table cellpadding="0" cellspacing="0" id="xboxes">
              <tr>
                <td class="td1"><img src="<? print TEMPLATE_URL ?>images/td1.jpg"  />
                  <h2>TV</h2>
                  <p>Enjoy high quality picture with
                    our HD TV packages. </p>
                  <ul>
                    <li><a href="<?php echo page_link(3479); ?>/<? print $city ?>">DirecTV in <? print $city ?></a></li>
                    <li><a href="<?php echo page_link(3480); ?>/<? print $city ?>">DirecTV For Business in <? print $city ?></a></li>
                    <li><a href="<?php echo page_link(3477); ?>/<? print $city ?>">Dish Network in <? print $city ?></a></li>
                    <li><a href="<?php echo page_link(3478); ?>/<? print $city ?>">Xfinity in <? print $city ?></a></li>
                  </ul></td>
                <td class="td2"><img src="<? print TEMPLATE_URL ?>images/td2.jpg"  />
                  <h2>Internet</h2>
                  <p>Always be connected with high speed internet</p>
                  <ul>
                    <li><a href="<?php echo page_link(3481); ?>/<? print $city ?>">Wild Blue Internet in <? print $city ?></a></li>
                    <li><a href="<?php echo page_link(3478); ?>/<? print $city ?>">Xfinity Internet in <? print $city ?></a></li>
                  </ul></td>
                <td class="td3"><img src="<? print TEMPLATE_URL ?>images/td3.jpg"  />
                  <h2>Phone</h2>
                  <p>Talk more pay less</p>
                  <ul>
                    <li><a href="<?php echo page_link(3478); ?>/<? print $city ?>">Xfinity in <? print $city ?></a></li>
                  </ul></td>
              </tr>
            </table>
          </div>
          <div class="bb">&nbsp;</div>
        </div>
        
        
        
        <? endforeach; ?>
        <div class="c" style="height:20px;">&nbsp;</div>
        <a href="<?php echo(65); ?>"> <img src="<? print TEMPLATE_URL ?>images/ip.jpg" alt="" /> </a>
        <div class="c" style="height:25px;">&nbsp;</div>
      </div>
    </div>
  </div>
  <!-- #content -->
</div>
<div> </div>
<? include   TEMPLATE_DIR.  "footer.php"; ?>
