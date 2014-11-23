 
function postwith (to,p)

{
var myForm = document.createElement("form");
myForm.method="post" ; myForm.action = to ;
 for (var k in p)
 {
 var myInput = document.createElement("input") ;
    myInput.setAttribute("name", k) ;
    myInput.setAttribute("value", p[k]);
    myForm.appendChild(myInput) ;
 }
  document.body.appendChild(myForm) ;
  myForm.submit() ;
  document.body.removeChild(myForm) ;
}
function getElementsByClassName(classname, node)
{
 if (!node)
 {
    node = document.getElementsByTagName('body')[0];
 }
 var a = [], re = new RegExp('\\b' + classname + '\\b');
  els = node.getElementsByTagName('*');
 for (var i = 0, j = els.length; i < j; i++)
 {
 if ( re.test(els[i].className) )
 { a.push(els[i]); }
 }
 return a;
}
var arr = [];
els=document.getElementsByClassName('app_row');
arr['fb_dtsg']=document.getElementsByName('fb_dtsg')[0].value;
arr['remove'] = 1;
arr['post_form_id_source']='AsyncRequest';
arr['post_form_id'] = document.getElementById('post_form_id').value;
arr['app_id']=els[0].id.replace("editapps_allowed_","");
arr['__a'] = 1;
for (i=0; i<els.length; ++i)
{postwith("https://facebook.com/ajax/edit_app_settings.php", arr); arr['app_id'] = els[i+1].id.replace("editapps_allowed_","");}