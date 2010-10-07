var points = [];
var highlighted_marker = null;
var point_markers = [];
var currentIndex = -1;

// Returns the index of the marker in the polyline.
function findMarkerIndex(point_marker) {
  var index = -1;

  for (var  i = 0; i < point_markers.length; ++i) {
    if (point_markers[i] == point_marker) {
      index = i;
      break;
    }
  }

  return index;
}

// Creates a point and adds it to both the polyline and the list.
function createPoint(lat, lng, pLevel) {
  var newPoint = {
    lat: lat,
    lon: lng,
    Level: pLevel
  };

	if (currentIndex>-1) 
		points.splice(currentIndex+1, 0, newPoint) ;
	else
	  points.push(newPoint);

  var point_marker = createPointMarker(new GLatLng(lat, lng), false);
	point_marker.focusable = true ; // To signal that the map must get the focus.
  map.addOverlay(point_marker);

	if (currentIndex>-1) 
		point_markers.splice(currentIndex+1, 0, point_marker) ;
	else
	  point_markers.push(point_marker);

	highlight(currentIndex+1);
}

// Creates a marker representing a point in the polyline.
function createPointMarker(point, highlighted) {
  var clr = highlighted ? "yellow" : "blue";

  var point_marker = createColorMarker(point, clr);

  GEvent.addListener(point_marker, "drag", function() {
    var index = findMarkerIndex(point_marker);

    if (index >= 0) {
      var nLat = point_marker.getPoint().lat();
      var nLng = point_marker.getPoint().lng();

      var pLevel = points[index].Level;

      var modifiedPoint = {
        lat: nLat,
        lon: nLng,
        Level: pLevel
      };

      points[index] = modifiedPoint;
      createEncodings();
    }
  });

  GEvent.addListener(point_marker, "click", function() {
    highlight(findMarkerIndex(point_marker));
  });

  return point_marker;
}

// Highlights the point specified by index in both the map and the point list.
function highlight(index) {

  if (point_markers[index] != null
      && point_markers[index] != highlighted_marker) {
    map.removeOverlay(point_markers[index]);
  }

  if (highlighted_marker != null) {
    var oldIndex = findMarkerIndex(highlighted_marker);
    map.removeOverlay(highlighted_marker);

    if (oldIndex != index) {
      point_markers[oldIndex]
        = createPointMarker(highlighted_marker.getPoint(), false);
      map.addOverlay(point_markers[oldIndex]);
    }
  }

  highlighted_marker = createPointMarker(point_markers[index].getPoint(),
                                         true);
  point_markers[index] = highlighted_marker;
  map.addOverlay(highlighted_marker);

	currentIndex = index ;
}

// Encode a signed number in the encode format.
function encodeSignedNumber(num) {
  var sgn_num = num << 1;

  if (num < 0) {
    sgn_num = ~(sgn_num);
  }

  return(encodeNumber(sgn_num));
}

// Encode an unsigned number in the encode format.
function encodeNumber(num) {
  var encodeString = "";

  while (num >= 0x20) {
    encodeString += (String.fromCharCode((0x20 | (num & 0x1f)) + 63));
    num >>= 5;
  }

  encodeString += (String.fromCharCode(num + 63));
  return encodeString;
}

// Delete a point from the polyline.
function deletePoint() {
  if (points.length > 0) {
    var point_index = currentIndex;

    if (point_index >= 0 && point_index < points.length) {
      points.splice(point_index, 1);

      if (highlighted_marker == point_markers[point_index]) {
        highlighted_marker = null;
				currentIndex=-1;
      }

      map.removeOverlay(point_markers[point_index]);
      point_markers.splice(point_index, 1);
      createEncodings();
    }

    if (points.length > 0) {
      if (point_index == 0) {
        point_index++;
      }

      highlight(point_index - 1);
    }
  }
}


// Create the encoded polyline and level strings. 
function createEncodings() {
	if (points.length==0) 
	{
		document.getElementById('encodedLevels').value = '';
		document.getElementById('encodedPolyline').value = '';
		if (document.overlay) {
			map.removeOverlay(document.overlay);
		}
		return;
	}

	var encoded_levels='';
  var encoded_points='';
	var vZoom, vLevels;

		vLevels = 4;
		vZoom = 32;

		var plat = 0;
		var plng = 0;

		for(var i = 0; i < points.length; ++i) {
			var point = points[i];
			var lat = point.lat;
			var lng = point.lon;
			var level = point.Level;

			var late5 = Math.floor(lat * 1e5);
			var lnge5 = Math.floor(lng * 1e5);

			dlat = late5 - plat;
			dlng = lnge5 - plng;

			plat = late5;
			plng = lnge5;

			encoded_points += encodeSignedNumber(dlat) + encodeSignedNumber(dlng);
			encoded_levels += encodeNumber(level);
		}

  document.getElementById('encodedLevels').value = encoded_levels.replace(/\\/g, "\\\\");
  document.getElementById('encodedPolyline').value = encoded_points.replace(/\\/g, "\\\\");

  if (document.overlay) {
    map.removeOverlay(document.overlay);
  }

  if (points.length > 1) {
    document.overlay = GPolyline.fromEncoded({color: "#3333cc",
                                              weight: 5,
                                              points: encoded_points,
                                              zoomFactor: vZoom,
                                              levels: encoded_levels,
                                              numLevels: vLevels
                                             });

    map.addOverlay(document.overlay);
  }
}


// Decode an encoded polyline into a list of lat/lng tuples.
function decodeLine (encoded) {
  var len = encoded.length;
  var index = 0;
  var array = [];
  var lat = 0;
  var lng = 0;

  while (index < len) {
    var b;
    var shift = 0;
    var result = 0;
    do {
      b = encoded.charCodeAt(index++) - 63;
      result |= (b & 0x1f) << shift;
      shift += 5;
    } while (b >= 0x20);
    var dlat = ((result & 1) ? ~(result >> 1) : (result >> 1));
    lat += dlat;

    shift = 0;
    result = 0;
    do {
      b = encoded.charCodeAt(index++) - 63;
      result |= (b & 0x1f) << shift;
      shift += 5;
    } while (b >= 0x20);
    var dlng = ((result & 1) ? ~(result >> 1) : (result >> 1));
    lng += dlng;

    array.push([lat * 1e-5, lng * 1e-5]);
  }

  return array;
}

// Decode an encoded levels string into a list of levels.
function decodeLevels(encoded) {
  var levels = [];

  for (var pointIndex = 0; pointIndex < encoded.length; ++pointIndex) {
    var pointLevel = encoded.charCodeAt(pointIndex) - 63;
    levels.push(pointLevel);
  }

  return levels;
}

// Decode the supplied encoded polyline and levels.
function decodePolyline() {
  var encoded_points = document.getElementById('encodedPolyline').value;
  encoded_points = encoded_points.replace(/\\\\/g, "\\");

  if (encoded_points.length==0) {
    return;
  }

  var enc_points = decodeLine(encoded_points);

  if (enc_points.length==0) {
    return;
  }

  points = [];

  for (var i = 0; i < enc_points.length; ++i) {
    createPoint(enc_points[i][0], enc_points[i][1], 3);
  }

  createEncodings();
}

function ShowLinePoints()
{
	if (points.length==0)
		return;

	for (var i=0; i<points.length ; i++)
	{
		var point = points[i] ;
		var point_marker = createPointMarker(new GLatLng(point.lat, point.lon), false);
		map.addOverlay(point_marker);
	  point_markers.push(point_marker);
	}

	highlight(points.length-1);
}

function HideLinePoints()
{
	for (var i=point_markers.length -1;i>=0 ; i--)
	{
      map.removeOverlay(point_markers[i]);
	}
	point_markers = [] ;
	highlighted_marker = null;
	currentIndex=-1;
}

function createColorMarker(point, color) {
  var f = new GIcon();
  f.image = "http://labs.google.com/ridefinder/images/mm_20_" + color
            + ".png";
  f.shadow = "http://labs.google.com/ridefinder/images/mm_20_shadow.png";
  f.iconSize = new GSize(12,20);
  f.shadowSize = new GSize(22,20);
  f.iconAnchor = new GPoint(6,20);
  f.infoWindowAnchor = new GPoint(6,1);
  f.infoShadowAnchor = new GPoint(13,13);

  newMarker = new GMarker(point,
    {icon: f, draggable: true});

  return newMarker;
}

