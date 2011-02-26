var expanded = 0;
function init() {
	$("expand").click(function() {
		if(expanded) {
			$("sidebar_1").style.display="block";
			$("sidebar_2").style.display="block";
			$("contents").style.width="65%";
			$("expand").innerHTML = t("&lt;&lt; Expand &gt;&gt;")
			expanded = 0;
		} else {
			$("sidebar_1").style.display="none";
			$("sidebar_2").style.display="none";
			$("contents").style.width="97%";
			$("expand").innerHTML = t("&gt;&gt; Contract &lt;&lt;")
			expanded = 1;
		}
	});
}
