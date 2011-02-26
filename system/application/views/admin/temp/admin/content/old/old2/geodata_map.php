  <script type="text/javascript">
 /*   google.load("maps", "2");
    google.load("search", "1");
	var addrpnt ;
var geocoder ;
var address ; 
var icon ;
   */
   
	$(document).ready(function(){
		//initialize_google_map();
		//GSearch.setOnLoadCallback(initialize);
		//google.setOnLoadCallback(initialize_google_map);
	
	
	
	
	/*

		 
		 var map = new GMap2(document.getElementById("google_map_holder"), { size: new GSize(800,600) } );
        map.setCenter(new google.maps.LatLng(37.4419, -122.1419), 13);
		
		
		
		 map.addControl(new GLargeMapControl());
 map.addControl(new GMapTypeControl());
 map.addMapType(G_PHYSICAL_MAP) ;
 map.addControl(new GScaleControl()) ;
 map.addControl(new GOverviewMapControl()) ;
 geocoder = new GClientGeocoder() ;
 icon = new GIcon();
 icon.image = "http://labs.google.com/ridefinder/images/mm_20_red.png";
 icon.shadow = "http://labs.google.com/ridefinder/images/mm_20_shadow.png";
 icon.iconSize = new GSize(12, 20);
 icon.shadowSize = new GSize(22, 20);
 icon.iconAnchor = new GPoint(6, 20);*/


       /* var searchControl = new google.search.SearchControl();
        searchControl.addSearcher(new google.search.WebSearch());
        searchControl.addSearcher(new google.search.NewsSearch());
        searchControl.draw(document.getElementById("searchcontrol"));*/
		 
     /* if (GBrowserIsCompatible()) {
      // var map = new GMap2(document.getElementById("google_map_holder"));
	  var map = new GMap2(document.getElementById("google_map_holder"), { size: new GSize(800,600) } );
       map.setCenter(new GLatLng(37.4419, -122.1419), 13);
	   map.setMapType(G_HYBRID_MAP);
	   map.addControl(new GLargeMapControl());
    map.addControl(new GMapTypeControl());

    // bind a search control to the map, suppress result list
    map.addControl(new google.maps.LocalSearch(), new GControlPosition(G_ANCHOR_BOTTOM_RIGHT, new GSize(10,20)));
	   
      }*/


}); // end onready


//var map ;

// Find the address using Geo::Coder::US Perl module instead of Google...
/*
   function showAddressGeoCoderUS(addr)
   {
    address = addr ;
    var request = GXmlHttp.create();
    var parms = "q=" + address ;
    request.open("POST", "/geocoder/geocodelite.pl", true);
    request.setRequestHeader('Content-Type','application/x-www-form-urlencoded') ;	// Thanks to Darkstar 3D!
    request.onreadystatechange = function() 
    {
     document.getElementById("loading").innerHTML = "Loading, please wait..." ;

     if (request.readyState == 4)
     {
      var xmlDoc = request.responseXML ;
      var info = xmlDoc.documentElement.getElementsByTagName("info") ;
      var error = info[0].getAttribute("error") ;
      if (error)
      {      
       alert(error) ;
      } else
      {
       var lat = parseFloat(info[0].getAttribute("lat")) ;
       var lng = parseFloat(info[0].getAttribute("lng")) ;

       if (addrpnt)
       {
        map.removeOverlay( addrpnt ) ;
       }
       var point = new GLatLng(lat,lng) ;
       addrpnt = new GMarker(point, {icon:icon, draggable: true,  title: address}) ;
       addrpnt.enableDragging() ;
       map.addOverlay(addrpnt) ;
       GEvent.addListener(addrpnt,'dragend',function() {newpoint()}) ;
       map.setCenter(point, 14) ;
       document.getElementById("sidebar").innerHTML = "Address:<br>" + address ;
       var zoom = map.getZoom() ;
       var escaddr = address.replace(/\s/g, '+') ;
       document.getElementById("link").innerHTML = "Link to this page: <a href=showaddress.htm?lat=" + addrpnt.getPoint().lat().toFixed(6) + "&lng=" + addrpnt.getPoint().lng().toFixed(6) + "&zoom=" + zoom + "&address=" + escaddr + ">Link</a>" ;
      }
     }       
     document.getElementById("loading").innerHTML = "" ;
    }
    request.send(parms);
   }

// Find the address using the geocoder...





function showAddress(addr)
{
 address = addr ;
 geocoder.getLatLng( address, function(point)
 {
  if (!point)
  {
   alert(address + " not found")
  } else
  {
   if (addrpnt)
   {
    map.removeOverlay( addrpnt ) ;
   }
   addrpnt = new GMarker(point, {icon:icon, draggable: true,  title: address}) ;
   addrpnt.enableDragging() ;
   map.addOverlay(addrpnt) ;
   GEvent.addListener(addrpnt,'dragend',function() {newpoint()}) ;
   map.setCenter(point, 14) ;
   document.getElementById("sidebar").innerHTML = "Address:<br>" + address ;
   var zoom = map.getZoom() ;
   var escaddr = address.replace(/\s/g, '+') ;
   document.getElementById("link").innerHTML = "Link to this page: <a href=showaddress.htm?lat=" + addrpnt.getPoint().lat().toFixed(6) + "&lng=" + addrpnt.getPoint().lng().toFixed(6) + "&zoom=" + zoom + "&address=" + escaddr + ">Link</a>" ;
  }
 }) ;
}

// Record the new point...

function newpoint( )
{
 map.setCenter(addrpnt.getPoint()) ;
 document.getElementById("sidebar").innerHTML = "Address:<br>" + address + "<br>Corrected Point:<br>" + addrpnt.getPoint().toUrlValue()  ;
 var zoom = map.getZoom() ;
 var escaddr = address.replace(/\s/g, '+') ;
 document.getElementById("link").innerHTML = "Link to this page: <a href=showaddress.htm?lat=" + addrpnt.getPoint().lat().toFixed(6) + "&lng=" + addrpnt.getPoint().lng().toFixed(6) + "&zoom=" + zoom + "&address=" + escaddr + ">Link</a>" ;
}*/














function processBeforeShowAddress(){
//showAddress(this.address.value);
$val = $("#googlemap_address").val();
//alert($val);
if($val != ''){
showAddress($val);
}
 

}



$(document).ready(function(){
	//	processBeforeShowAddress()
	setTimeout('processBeforeShowAddress()', 5000)
}); // end onready

 function geodataClear(){
		$("#geodata_country_code").val(''); 
		$("#geodata_country").val(''); 
		$("#geodata_area").val(''); 
		$("#geodata_city").val(''); 
		$("#geodata_address").val('');  
		$("#geodata_lng").val(''); 
		$("#geodata_lat").val('');  
		$("#geodata_mapzoom").val('');  
 }

</script>
<?php //print $form_values['content_meta_other_code']; ?>
<?php  

$geodata = $form_values['geodata'];


//var_dump($geodata);

?>
      <table border="0" cellspacing="5" cellpadding="5">
        <tr valign="top">
          <td><div id="google_map_holder" style="width: 800px; height: 600px; display:block"></div></td>
              </tr>
      </table>
          <div id="ismaprwrap">
          
          <div style="width: 250px"> <label><span>Address:</span></label>
          <div style="clear:both"><!----></div>
              <textarea style="clear:both;" name="googlemap_address" id="googlemap_address" rows="1" cols="10" style="width: 200px; height: 60px; font-size:14px; display:block"><?php print $geodata ['geodata_address']; ?></textarea>
              <br />
              <br />
              <!--<input type="text" size=30 name="googlemap_address" id="googlemap_address">-->
              <a href="javascript:processBeforeShowAddress();">Process</a><br />
<br />
<br />

              <a href="javascript:geodataClear();">Clear!</a>
              <!--<input type="button" onclick="processBeforeShowAddress();" value="Process">-->
            </div>
           <br /><br />
<label><span>geodata country code: </span><input name="geodata_country_code" type="text" id="geodata_country_code" value="<?php print $geodata ['geodata_country_code']; ?>" /></label>
<label><span>geodata country: </span><input name="geodata_country" type="text" id="geodata_country" value="<?php print $geodata ['geodata_country']; ?>" /></label>
<label><span>geodata mapzoom: </span><input name="geodata_mapzoom" type="text" id="geodata_mapzoom" value="<?php print $geodata ['geodata_mapzoom']; ?>" /></label>
<label><span>geodata city:</span> <input name="geodata_city" type="text" id="geodata_city" value="<?php print $geodata ['geodata_city']; ?>" /></label>
<label><span>geodata area: </span><input name="geodata_area" type="text" id="geodata_area" value="<?php print $geodata ['geodata_area']; ?>" /></label>
<label><span>geodata address: </span><input name="geodata_address" type="text" id="geodata_address"  value="<?php print $geodata ['geodata_address']; ?>" /></label>
<label><span>geodata lat: </span><input name="geodata_lat" type="text" id="geodata_lat" value="<?php print $geodata ['geodata_lat']; ?>" /></label>
<label><span>geodata lng: </span><input name="geodata_lng" type="text" id="geodata_lng" value="<?php print $geodata ['geodata_lng']; ?>" /></label>
<label><span>geodata title: </span><input name="geodata_title" type="text" id="geodata_title" value="<?php print $geodata ['geodata_title']; ?>" /></label> 
<label><span>geodata note: </span><textarea name="geodata_note" cols="5" rows="10"><?php print $geodata ['geodata_note']; ?></textarea></label> 
            
        </div>    
          
    