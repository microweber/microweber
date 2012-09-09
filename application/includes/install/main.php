<!DOCTYPE html>
<!--[if lt IE 7]><html class="ie9 ie8 ie7 ie6" lang="en"><![endif]-->
<!--[if IE 7]><html class="ie9 ie8 ie7" lang="en"><![endif]-->
<!--[if IE 8]><html class="ie9 ie8" lang="en"><![endif]-->
<!--[if IE 9]><html class="ie9" lang="en"><![endif]-->
<!--[if gt IE 9]><!--><html lang="en"><!--<![endif]-->

<head>
<title>Microweber Configuration</title>

<!-- 
		Include BaseDemo
		
		BaseDemo is a code demoing tool for displaying demos the easiest way possible
		It is not required for the demo to work and should not be included in your
		implementation of the demoed code on your projects.
		
		For more on BaseDemo here: https://github.com/sebnitu/BaseDemo
	-->
<link type="text/css" rel="stylesheet" media="all" href="<? print INCLUDES_URL; ?>api/api.css"/>
<script type="text/javascript" src="<? print INCLUDES_URL; ?>js/jquery-latest.js"></script>
<script type="text/javascript" src="<? print SITE_URL; ?>api.js"></script>
<script src="<? print INCLUDES_URL; ?>install/bootstrap.js"></script>

 
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
        <h2>Database setup</h2>
        <p>
        <form method="GET">
          <table  cellspacing="5" cellpadding="5">
            <tr>
              <td>Dabase type</td>
              <td><select name="db_type">
                  <option value="sqlite">sqlite</option>
                  <option value="mysql">mysql</option>
                </select></td>
            </tr>
            <tr>
              <td>Dabase file</td>
              <td><? $dbs = directory_map(DBPATH); // d($dbs);	   ?>
                <select name="db_file">
                  <option value="db_file_new">New database</option>
                  <? if(!empty($dbs)): ?>
                  <? foreach($dbs as $item): ?>
                  <option value="<?  print $item ?>">
                  <?  print $item ?>
                  </option>
                  <? endforeach; ?>
                  <? endif; ?>
                </select></td>
            </tr>
            <tr>
              <td>Custom dns</td>
              <td><input name="custom_dsn" />
                <br>
                <small>For usage with mysql or other databases (ex. mysql:host=localhost;port=3306;dbname=my_database_name)</small></td>
            </tr>
            <tr>
              <td>DB_USER</td>
              <td><input name="DB_USER" /></td>
            </tr>
            <tr>
              <td>DB_PASS</td>
              <td><input name="DB_PASS" /></td>
            </tr>
            <tr>
              <td>Save</td>
              <td><input type="submit" name="submit" ></td>
            </tr>
          </table>
        </form>
        </p>
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