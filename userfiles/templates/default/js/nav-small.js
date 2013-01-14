$(document).ready(function() {
function a(b, c) {
$(b).children("li").each(function(b, d) {
var e="";
for(var f=0;
f<c;
f++) {
e+="      "
}
e+=$(d).children("a").text();
$("#responsive-main-nav-menu").append("<option value = '"+$(d).children("a").attr("href")+"'>"+e+"</option>");
if($(d).children("ul").size()==1) {
a($(d).children("ul"), c+1)
}
}
)
}
a($("#main-nav-menu > ul"), 0);
$("#main-nav-menu").find("li:has(ul) > a").each(function() {
$(this).append("<span class = 'indicator'/>")
}
);
}
)
