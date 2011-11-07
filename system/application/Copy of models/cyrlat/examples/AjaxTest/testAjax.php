<html>

<body>
<script language="JavaScript" src="lib/Js.js"></script>
<script>
	function doLoad(force) {
		var query = '' + document.getElementById('query').value;
		var req = new Subsys_JsHttpRequest_Js();
		req.onreadystatechange = function() {
			if (req.readyState == 4) {
				if (req.responseJS) {
						document.getElementById('result').innerHTML=
		(req.responseJS.cyr||'');
				}


			}
		}

		req.caching = true;
		req.open('POST', 'lat2cyr.php?input=abc', true);
		req.send({ q: query, test:303 });
	}

	var timeout = null;
	function doLoadUp() {
		if (timeout) clearTimeout(timeout);
		timeout = setTimeout(doLoad, 1000);
	}
</script>

<form onsubmit="return false">
Type phrase using latin symbols<br />
<textarea type="text" id="query" onkeyup="doLoadUp()"></textarea>
</form>

<p>Result:</p>
<pre>
<div id="result"></div>
</pre>



</body>
</html>