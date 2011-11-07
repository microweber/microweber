
    <? $v = ( url_param('action', true) );?>
      
 <? if($v) {
	 
	 include("snippets/".$v.'.php'); 
	 
	 
 }  else { ?>

<div class="mercury-snippet-panel">
  <div class="filter">
    <input class="filter" type="text">
  </div>
  <ul>
    <li data-filter="editable, snippet, favorite, beer"> <img alt="Snippet Name" data-snippet="editable" src="http://www.bytemycode.com/img/icon_snippet.gif"/>
      <h4>Snippet Name</h4>
      <div class="description">A one to two line long description of what this snippet does.</div>
    </li>
  </ul>
</div>
<?  }   ?>