<script type="text/javascript">
//<![CDATA[

var addrpnt ;
var geocoder ;
var address ;
var icon ;
var map ;

function load_google_maps()
{ 
   
 
 
 if (obj = document.getElementById("google_map_holder")) {




//document.onkeypress = stopRKey;


 

 
 <?php  $geodata = $form_values['geodata']; 
 if( $geodata['geodata_lat'] == ''){
	$geodata ['geodata_lat'] = '35.127771';
 }
 
 if( $geodata['geodata_lng'] == ''){
	$geodata ['geodata_lng'] = '-89.967041';
 }
 
 ?>
   <?php if( $geodata['geodata_mapzoom'] == ''){
	$geodata ['geodata_mapzoom'] = '14';
 } 
 
 
 
 if( $geodata['geodata_mapcenter_lat'] == ''){
	$geodata['geodata_mapcenter_lat'] = $geodata ['geodata_lat'] ;
 }
 
  if( $geodata['geodata_mapcenter_lng'] == ''){
	$geodata['geodata_mapcenter_lng'] = $geodata ['geodata_lng'] ;
 }
 
 if( $geodata['geodata_map_height'] == ''){
	$geodata['geodata_map_height'] = 500 ;
 }
 
  if( $geodata['geodata_map_width'] == ''){
	$geodata['geodata_map_width'] = 500 ;
 }
 
 
 $geodata['geodata_map_height'] = 500 ;
 $geodata['geodata_map_width'] = 500 ;
 ?>
 
  map = new GMap2(document.getElementById("google_map_holder"), { size: new GSize(<?php print $geodata['geodata_map_width'] ?>,<?php print $geodata['geodata_map_height'] ?>) } );
 
 
 var start = new GLatLng(<?php print $geodata ['geodata_mapcenter_lat']; ?>,<?php print $geodata ['geodata_mapcenter_lng']; ?>);
  map.setCenter(start, <?php print $geodata ['geodata_mapzoom'] ; ?>);
 
  

 
 


 map.addControl(new GLargeMapControl());
 map.addControl(new GMapTypeControl());
// map.addMapType(G_PHYSICAL_MAP) ;
 map.addMapType(G_NORMAL_MAP ) ;
 map.addMapType(G_SATELLITE_MAP ) ;
  map.addMapType(G_HYBRID_MAP ) ;
   map.addMapType(G_PHYSICAL_MAP ) ;
    map.addMapType(G_MAPMAKER_NORMAL_MAP ) ;
	 map.addMapType(G_MAPMAKER_HYBRID_MAP ) ;
	/*  map.addMapType(G_MOON_ELEVATION_MAP ) ;
	   map.addMapType(G_MOON_VISIBLE_MAP ) ;
	    map.addMapType(G_MARS_ELEVATION_MAP ) ;
		 map.addMapType(G_MARS_VISIBLE_MAP ) ;
		 map.addMapType(G_MARS_INFRARED_MAP ) ;
		  map.addMapType(G_SKY_VISIBLE_MAP ) ;
 */
  

 <?php if (strval($geodata ['geodata_maptype']) != '')  : ?>
	 <?php if ((strval($geodata ['geodata_maptype']) != 'G_NORMAL_MAP') and (strval($geodata ['geodata_maptype']) != 'G_SATELLITE_MAP')
	 and (strval($geodata ['geodata_maptype']) != 'G_HYBRID_MAP')
	 and (strval($geodata ['geodata_maptype']) != 'G_PHYSICAL_MAP')
	 and (strval($geodata ['geodata_maptype']) != 'G_MAPMAKER_NORMAL_MAP')
	 and (strval($geodata ['geodata_maptype']) != 'G_MAPMAKER_HYBRID_MAP')
	 
	  )  : ?>
	
 
 map.addMapType(<?php print $geodata ['geodata_maptype']; ?> ) ;
 <?php else: ?>
 <?php $geodata ['geodata_maptype'] = 'G_PHYSICAL_MAP'; ?>
 <?php endif; ?>
 
 
 <?php endif; ?>

 

 

 

 

 

 

 

 

 



 
 
 map.addControl(new GScaleControl()) ;
 map.addControl(new GOverviewMapControl()) ;
 
// alert(<?php print intval($geodata ['geodata_mapzoom']);     ?>); 
map.setZoom(<?php print intval($geodata ['geodata_mapzoom']);     ?>);


 
 
 geocoder = new GClientGeocoder() ;
 icon = new GIcon();
 icon.image = "http://labs.google.com/ridefinder/images/mm_20_red.png";
 icon.shadow = "http://labs.google.com/ridefinder/images/mm_20_shadow.png";
 icon.iconSize = new GSize(12, 20);
 icon.shadowSize = new GSize(22, 20);
 icon.iconAnchor = new GPoint(6, 20);
 
 }
 
 
 
 GEvent.addListener(map, "zoomend", function(oldZ, newZ){
 $("#geodata_mapzoom").val(map.getZoom()); 
} );
 
 
 
   GEvent.addListener(map, "maptypechanged", function() {
   firecms_geodata_assign();
      
      }); 
 
 
  GEvent.addListener(map, "dragend", function() {
   //firecms_geodata_assign();
    var mycenter = map.getCenter() 
 var mycentercur_lng = mycenter.lng().toFixed(6);
	var mycentercur_lat = mycenter.lat().toFixed(6);
  $("#geodata_mapcenter_lng").val(mycentercur_lng);
  $("#geodata_mapcenter_lat").val(mycentercur_lat);
  

   
   
      
      }); 
 
 
 
 
}

// Find the address using Geo::Coder::US Perl module instead of Google...

  /* function showAddressGeoCoderUS(addr)
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
*/
// Find the address using the geocoder...

function showAddress(addr, $go)
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
 //  
 if($go == true){
 map.setCenter(point) ;
 }
 
  // document.getElementById("sidebar").innerHTML = "Address:<br>" + address ;
   var zoom = map.getZoom() ;
   var escaddr = address.replace(/\s/g, '+') ;
  // document.getElementById("link").innerHTML = "Link to this page: <a href=showaddress.htm?lat=" + addrpnt.getPoint().lat().toFixed(6) + "&lng=" + addrpnt.getPoint().lng().toFixed(6) + "&zoom=" + zoom + "&address=" + escaddr + ">Link</a>" ;
   
   firecms_geodata_assign();
   
   
   
  }
 }) ;
}

// Record the new point...

function newpoint( )
{
// map.setCenter(addrpnt.getPoint()) ;
// document.getElementById("sidebar").innerHTML = "Address:<br>" + address + "<br>Corrected Point:<br>" + addrpnt.getPoint().toUrlValue()  ;
 var zoom = map.getZoom() ;
 var escaddr = address.replace(/\s/g, '+') ;
 //document.getElementById("link").innerHTML = "Link to this page: <a href=showaddress.htm?lat=" + addrpnt.getPoint().lat().toFixed(6) + "&lng=" + addrpnt.getPoint().lng().toFixed(6) + "&zoom=" + zoom + "&address=" + escaddr + ">Link</a>" ;
 
 
 firecms_geodata_assign();
 
}



function firecms_geodata_assign(){
	
	// map.setCenter(addrpnt.getPoint()) ;
	 var point = addrpnt.getPoint()
	 var zoom = map.getZoom() ;
 	var escaddr = address.replace(/\s/g, '+') ;
	
	var cur_lng = addrpnt.getPoint().lng().toFixed(6);
	var cur_lat = addrpnt.getPoint().lat().toFixed(6);
 //alert(cur_lng + cur_lat);
 
 $("#geodata_lng").val(cur_lng);
  $("#geodata_lat").val(cur_lat);
 //assign them to form fields
 
 
   var myMapType = map.getCurrentMapType(
   {
useShortNames:true
}
   );
   maptype_name = myMapType.getName();
   //$("#geodata_maptype").val(maptype_name); 
       

  
  
  
 	//var check = $("#content_body");
//GClientGeocoder.setBaseCountryCode()
geocoder.getLocations(point, firecms_addAddressToMap);





 
	
}



function firecms_addAddressToMap(response) {
//map.clearOverlays();
if (!response || response.Status.code != 200) {
   alert("Sorry, we were unable to geocode that address");
} else {
   var place = response.Placemark[0];
  var temp = place;
 var maptype = map.getCurrentMapType(); 
 //maptype_name = maptype.getName(false);
//  maptype_getProjection = maptype.getProjection();
 
 
 
// alert(maptype);
 //alert(maptype_name);
//  alert(maptype_getProjection);
 
 
 
 
  $("#geodata_mapzoom").val(map.getZoom()); 
  // $("#geodata_maptype").val(maptype); 
  //geodata_mapzoom
  
  
 
   $("#geodata_country_code").val(place.AddressDetails.Country.CountryNameCode); 
   $("#geodata_country").val(place.AddressDetails.Country.CountryName); 
     
   if (obj = place.AddressDetails.Country.AdministrativeArea) {
		 var AdministrativeArea = place.AddressDetails.Country.AdministrativeArea.AdministrativeAreaName;
		 $("#geodata_area").val(AdministrativeArea); 
	} else {
		 var SubAdministrativeArea = place.AddressDetails.Country.SubAdministrativeArea.SubAdministrativeAreaName;
   $("#geodata_area").val(SubAdministrativeArea); 
	}
  
  
  
   if (obj = place.AddressDetails.Country.AdministrativeArea) {
		$("#geodata_city").val(place.AddressDetails.Country.AdministrativeArea.Locality.LocalityName); 
	} else {
		//$("#geodata_city").val('not yet'); 
		$("#geodata_city").val(place.AddressDetails.Country.SubAdministrativeArea.Locality.LocalityName); 
	}
  
   

   
    $("#geodata_address").val(place.address); 
	
	
	 var mycenter = map.getCenter() 
 var mycentercur_lng = mycenter.lng().toFixed(6);
	var mycentercur_lat = mycenter.lat().toFixed(6);
  $("#geodata_mapcenter_lng").val(mycentercur_lng);
  $("#geodata_mapcenter_lat").val(mycentercur_lat);
  

  
}
}



//]]>

google.setOnLoadCallback(load_google_maps);

</script>

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










function processBeforeShowAddressAndGo(){
//showAddress(this.address.value);
$val = $("#googlemap_address").val();
//alert($val);
if($val != ''){
showAddress($val, true);
}
 

}



function processBeforeShowAddress(){
//showAddress(this.address.value);
$val = $("#googlemap_address").val();
//alert($val);
if($val != ''){
showAddress($val, false);
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
		//$("#geodata_maptype").val('');  
		
		
		
		
		
		
		
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
              <a href="javascript:processBeforeShowAddressAndGo();">Process and GO</a>
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
<label><span>map type: </span>
<select name="geodata_maptype" id="geodata_maptype">
<option value=""   >Choose</option>
<option value="G_NORMAL_MAP" <?php if($geodata ['geodata_maptype'] == 'G_NORMAL_MAP') : ?>  selected="selected"  <?php endif ?>>G_NORMAL_MAP</option>
<option value="G_SATELLITE_MAP" <?php if($geodata ['geodata_maptype'] == 'G_SATELLITE_MAP') : ?>  selected="selected"  <?php endif ?>>G_SATELLITE_MAP</option>
<option value="G_HYBRID_MAP" <?php if($geodata ['geodata_maptype'] == 'G_HYBRID_MAP') : ?>  selected="selected"  <?php endif ?>>G_HYBRID_MAP</option>
<option value="G_PHYSICAL_MAP" <?php if($geodata ['geodata_maptype'] == 'G_PHYSICAL_MAP') : ?>  selected="selected"  <?php endif ?>>G_PHYSICAL_MAP</option>
<option value="G_MAPMAKER_NORMAL_MAP" <?php if($geodata ['geodata_maptype'] == 'G_MAPMAKER_NORMAL_MAP') : ?>  selected="selected"  <?php endif ?>>G_MAPMAKER_NORMAL_MAP</option>
<option value="G_MAPMAKER_HYBRID_MAP" <?php if($geodata ['geodata_maptype'] == 'G_MAPMAKER_HYBRID_MAP') : ?>  selected="selected"  <?php endif ?>>G_MAPMAKER_HYBRID_MAP</option>
<option value="G_MOON_ELEVATION_MAP" <?php if($geodata ['geodata_maptype'] == 'G_MOON_ELEVATION_MAP') : ?>  selected="selected"  <?php endif ?>>G_MOON_ELEVATION_MAP</option>
 <option value="G_MOON_VISIBLE_MAP" <?php if($geodata ['geodata_maptype'] == 'G_MOON_VISIBLE_MAP') : ?>  selected="selected"  <?php endif ?>>G_MOON_VISIBLE_MAP</option>
<option value="G_MARS_ELEVATION_MAP" <?php if($geodata ['geodata_maptype'] == 'G_MARS_ELEVATION_MAP') : ?>  selected="selected"  <?php endif ?>>G_MARS_ELEVATION_MAP</option>
<option value="G_MARS_VISIBLE_MAP" <?php if($geodata ['geodata_maptype'] == 'G_MARS_VISIBLE_MAP') : ?>  selected="selected"  <?php endif ?>>G_MARS_VISIBLE_MAP</option>
<option value="G_MARS_INFRARED_MAP" <?php if($geodata ['geodata_maptype'] == 'G_MARS_INFRARED_MAP') : ?>  selected="selected"  <?php endif ?>>G_MARS_INFRARED_MAP</option>
 <option value="G_SKY_VISIBLE_MAP" <?php if($geodata ['geodata_maptype'] == 'G_SKY_VISIBLE_MAP') : ?>  selected="selected"  <?php endif ?>>G_SKY_VISIBLE_MAP</option>

 <option value="G_SATELLITE_3D_MAP" <?php if($geodata ['geodata_maptype'] == 'G_SATELLITE_3D_MAP') : ?>  selected="selected"  <?php endif ?>>G_SATELLITE_3D_MAP</option>

 <option value="G_DEFAULT_MAP_TYPES" <?php if($geodata ['geodata_maptype'] == 'G_DEFAULT_MAP_TYPES') : ?>  selected="selected"  <?php endif ?>>G_DEFAULT_MAP_TYPES</option>

 <option value="G_MAPMAKER_MAP_TYPES" <?php if($geodata ['geodata_maptype'] == 'G_MAPMAKER_MAP_TYPES') : ?>  selected="selected"  <?php endif ?>>G_MAPMAKER_MAP_TYPES</option>
<option value="G_MOON_MAP_TYPES" <?php if($geodata ['geodata_maptype'] == 'G_MOON_MAP_TYPES') : ?>  selected="selected"  <?php endif ?>>G_MOON_MAP_TYPES</option>
<option value="G_MARS_MAP_TYPES" <?php if($geodata ['geodata_maptype'] == 'G_MARS_MAP_TYPES') : ?>  selected="selected"  <?php endif ?>>G_MARS_MAP_TYPES</option>
<option value="G_SKY_MAP_TYPES" <?php if($geodata ['geodata_maptype'] == 'G_SKY_MAP_TYPES') : ?>  selected="selected"  <?php endif ?>>G_SKY_MAP_TYPES</option>

 

</select><a href="#TB_inline?height=400&width=400&inlineId=googlemaptypeshelpwindow&modal=false"     class="thickbox">?</a>
<div id="googlemaptypeshelpwindow" style="display:none">
<table cellspacing="0" border="1" cellpadding="0" width="98%">
  <tr id="GMapType.G_NORMAL_MAP">
    <td>G_NORMAL_MAP </td>
    <td>This map type (which is the default) displays a normal street map.</td>
  </tr>
  <tr id="GMapType.G_SATELLITE_MAP">
    <td> G_SATELLITE_MAP </td>
    <td>This map type displays satellite images.</td>
  </tr>
  <tr id="GMapType.G_HYBRID_MAP">
    <td> G_HYBRID_MAP </td>
    <td>This map type displays a transparent layer of major streets        on satellite images.</td>
  </tr>
  <tr id="GMapType.G_PHYSICAL_MAP">
    <td> G_PHYSICAL_MAP </td>
    <td>This map type displays maps with physical features such        as terrain and vegetation. This map type is not displayed        within map type controls by default.
        <p>(Since 2.94)</p></td>
  </tr>
  <tr id="GMapType.G_MAPMAKER_NORMAL_MAP">
    <td> G_MAPMAKER_NORMAL_MAP </td>
    <td>This map type displays a street map with tiles created using <a href="http://www.google.com/mapmaker" target="_blank"> Google Mapmaker</a>. <br />
        <br />
      Note: When you use a Mapmaker map type, users will only see maps in <a href="http://www.google.com/mapmaker/mapfiles/s/launched.html" target="_blank">countries</a> where Google Map Maker is launched.
      <p>(Since 2.145)</p></td>
  </tr>
  <tr id="GMapType.G_MAPMAKER_HYBRID_MAP">
    <td> G_MAPMAKER_HYBRID_MAP </td>
    <td>This map type displays a transparent layer of major streets        created using <a href="http://www.google.com/mapmaker" target="_blank"> Google Mapmaker</a> on satellite images. <br />
        <br />
      Note: When you use the Mapmaker maptype, users will only see maps in <a href="http://www.google.com/mapmaker/mapfiles/s/launched.html" target="_blank">countries</a> where Google Map Maker is launched.
      <p>(Since 2.145)</p></td>
  </tr>
  <tr id="GMapType.G_MOON_ELEVATION_MAP">
    <td> G_MOON_ELEVATION_MAP </td>
    <td>This map type displays a shaded terrain map of the surface of the Moon, color-coded by altitude.        This map type is not displayed within map type controls by default.
        <p>(Since 2.95)</p></td>
  </tr>
  <tr id="GMapType.G_MOON_VISIBLE_MAP">
    <td> G_MOON_VISIBLE_MAP </td>
    <td>This map type displays photographs taken from orbit around the moon. This map type is not        displayed within map type controls by default.
        <p>(Since 2.95)</p></td>
  </tr>
  <tr id="GMapType.G_MARS_ELEVATION_MAP">
    <td> G_MARS_ELEVATION_MAP </td>
    <td>This map type displays a shaded relief map of the surface of Mars, color-coded by altitude.        This map type is not displayed within map type controls by default.
        <p>(Since 2.95)</p></td>
  </tr>
  <tr id="GMapType.G_MARS_VISIBLE_MAP">
    <td> G_MARS_VISIBLE_MAP </td>
    <td>This map type displays photographs taken from orbit around Mars.         This map type is not displayed within map type controls by default.
        <p>(Since 2.95)</p></td>
  </tr>
  <tr id="GMapType.G_MARS_INFRARED_MAP">
    <td> G_MARS_INFRARED_MAP </td>
    <td>This map type displays a shaded infrared map of the surface of Mars, where        warmer areas appear brighter and colder areas appear darker.
      <p>(Since 2.95)</p></td>
  </tr>
  <tr id="GMapType.G_SKY_VISIBLE_MAP">
    <td> G_SKY_VISIBLE_MAP </td>
    <td>This map type shows a mosaic of the sky, covering the full celestial sphere.
      <p>(Since 2.95)</p></td>
  </tr>
  <tr id="GMapType.G_SATELLITE_3D_MAP">
    <td> G_SATELLITE_3D_MAP </td>
    <td>This map type, in conjunction with the <a href="http://code.google.com/apis/earth/"> Google Earth Browser Plug-in</a>, displays a fully interactive 3D model of the Earth with        satellite imagery. This map type is not displayed within map type controls by default. <br />
        <br />
      Adding this map type to your map not only adds a control for the Google Earth map type,        but also handles initialization of the map type once a user clicks on the control. If        a user currently does not have the <a href="http://code.google.com/apis/earth/">Google Earth Plug-in</a> installed in their browser, the first time a user selects this map type, the user will        be prompted to download and install the Plug-in and restart their browser. <br />
      <br />
      For users that have already installed the plugin, selecting this map type will create        an Earth instance for the map and display a 3D view of the Earth. You may use <a href="http://code.google.com/apis/maps/documentation/reference.html#GMap2.getEarthInstance" title="GMap2.getEarthInstance">GMap2.getEarthInstance()</a> to retrieve this Earth instance and manipulate it using the <a href="http://code.google.com/apis/earth">Google Earth API</a>. <br />
      <br />
      Currently, markers, infowindows, and polylines work with this 3D map type,        but other features are not yet supported. We plan to add support for more        features in future releases.
      <p>(Since 2.113)</p></td>
  </tr>
  <tr id="GMapType.G_DEFAULT_MAP_TYPES">
    <td> G_DEFAULT_MAP_TYPES </td>
    <td>An array of the first three predefined map types described        above (G_NORMAL_MAP, G_SATELLITE_MAP, and G_HYBRID_MAP).</td>
  </tr>
  <tr id="GMapType.G_MAPMAKER_MAP_TYPES">
    <td> G_MAPMAKER_MAP_TYPES </td>
    <td>An array of the Mapmaker map types described above        (G_MAPMAKER_NORMAL_MAP, G_SATELLITE_MAP, and        G_MAPMAKER_HYBRID_MAP).</td>
  </tr>
  <tr id="GMapType.G_MOON_MAP_TYPES">
    <td> G_MOON_MAP_TYPES </td>
    <td>An array of the two Moon types defined above (G_MOON_ELEVATION_MAP and G_MOON_VISIBLE_MAP).</td>
  </tr>
  <tr id="GMapType.G_MARS_MAP_TYPES">
    <td> G_MARS_MAP_TYPES </td>
    <td>An array of the three Mars map types defined above (G_MARS_ELEVATION_MAP, G_MARS_VISIBLE_MAP, and G_MARS_INFRARED_MAP).</td>
  </tr>
  <tr id="GMapType.G_SKY_MAP_TYPES">
    <td> G_SKY_MAP_TYPES </td>
    <td>An array of the one sky map type defined above (G_SKY_VISIBLE_MAP).</td>
  </tr>
</table>
</div>


</label>
<label><span>geodata title: </span><input name="geodata_title" type="text" id="geodata_title" value="<?php print $geodata ['geodata_title']; ?>" /></label> 
<label><span>geodata note: </span><textarea name="geodata_note" cols="5" rows="10"><?php print $geodata ['geodata_note']; ?></textarea></label> 
  <label><span>geodata_mapcenter_lat: </span><input name="geodata_mapcenter_lat" type="text" id="geodata_mapcenter_lat" value="<?php print $geodata ['geodata_mapcenter_lat']; ?>" /></label>     <label><span>geodata_mapcenter_lng: </span><input name="geodata_mapcenter_lng" type="text" id="geodata_mapcenter_lng" value="<?php print $geodata ['geodata_mapcenter_lng']; ?>" /></label>  <?php if( $geodata['geodata_map_height'] == ''){
	$geodata['geodata_map_height'] = 600 ;
 }
 
  if( $geodata['geodata_map_width'] == ''){
	$geodata['geodata_map_width'] = 800 ;
 }
   ?>    
  
  
  <label><span>geodata_map_width: </span><input name="geodata_map_width" type="text" id="geodata_map_width" value="<?php print $geodata ['geodata_map_width']; ?>" /></label>     <label><span>geodata_map_height: </span><input name="geodata_map_height" type="text" id="geodata_map_height" value="<?php print $geodata ['geodata_map_height']; ?>" /></label>    
          
            
            
        </div>    
          
    