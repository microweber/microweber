<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" xmlns:v='urn:schemas-microsoft-com:vml'>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
		<title></title>
         <script type="text/javascript">
            imgurl="<? print TEMPLATE_URL ?>img/";
         </script>
		<link rel="stylesheet" href="<? print TEMPLATE_URL ?>css/font.php" type="text/css" media="screen"  />
		<link rel="stylesheet" href="<? print TEMPLATE_URL ?>css/style.css" type="text/css" media="screen"  />
        <? echo '<!--[if IE]><?import namespace="v" implementation="#default#VML" ?><![endif]-->'; ?>
        <script type="text/javascript" src="<?  print site_url('api/js'); ?>"></script>
 
        <script type="text/javascript" src="<? print TEMPLATE_URL ?>js/libs.js"></script>
        <script type="text/javascript" src="<? print TEMPLATE_URL ?>js/functions.js"></script>
	</head>
	<body>
        <div id="container">
          <div id="wrapper">
            <div id="header">
                <div id="header_top">
                  <div id="logo">
                    <a href="#">Tilos</a>
                  </div>
                  <div id="top_nav">
                    <ul>
                      <li><a href="#">Create an account</a>|</li>
                      <li><a href="#">Wishlist</a>|</li>
                      <li><a href="#">Login</a>|</li>
                      <li><a href="#">View cart</a></li>
                    </ul>
                    
                    <editable rel="global"  field="welcome_text">
                   <p>Welcome to Tilos Inc</p>  
                     </editable>
                    
                    
                    
                    
                  </div>
                </div>
                <div id="nav">
               
                    <block id="header_block" global="true" />
  
                  
                  <div id="nav_cart">
                     <span class="items">0</span>
                     <strong>Items</strong>
                     <img src="<? print TEMPLATE_URL ?>img/cart.png" alt="" />
                     <a href="#">View cart </a>
                  </div>
                </div>
            </div><!-- /#header -->
            <div id="content">