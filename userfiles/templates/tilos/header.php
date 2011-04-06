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
        
         <script type="text/javascript">
         function add_to_cart_callback(){
			 
			//alert(1); 
			
			 
		 }
         
         
         </script>
        
        
        
        
	</head>
	<body>
        <div id="container">
          <div id="wrapper">
            <div id="header">
                <div id="header_top">
                  <div id="logo">
                    <a href="<? print site_url(); ?>">Tilos</a>
                  </div>
                  <div id="top_nav">
                     
                    <ul>
                      
                     
                    <?  if(user_id()  != false):  ?>
                    
                     <? 
				   $shop_page = array();
				   $shop_page['content_layout_name'] = 'shop';
				  
				  $shop_page=get_pages($shop_page);
				  $shop_page = $shop_page[0];
		 //  var_dump($shop_page);
				  ?>
                     <li><a href="<? print page_link_to_layout('members'); ?>">Profile</a>|</li>
                     
                      <li><a href="#"  onclick="mw.users.LogOut()">Log Out</a>|</li>
                      <li><a href="<? print page_link($shop_page['id']); ?>">View cart</a></li>
                      
                      
                      <? else: ?>
 
  <? 
				   $members = array();
				   $members['content_layout_name'] = 'members';
				  
				  $members=get_pages($members);
				  $members = $members[0];
				//  var_dump($shop_page);
				  ?>
                  
                    <li><a href="<? print page_link($members['id']); ?>">Log in</a></li>
 
  <? endif; ?>
  
  
                      
                    
                      
                      
                    </ul>
                   
                    
                   <p>Welcome to Tilos Inc</p>  
                    
                    
                    
                    
                    
                  </div>
                </div>
                <div id="nav">
               
                    <block id="header_block" global="true" />
  
                  
                  
                  <? 
				   $shop_page = array();
				   $shop_page['content_layout_name'] = 'shop';
				  
				  $shop_page=get_pages($shop_page);
				  $shop_page = $shop_page[0];
				//  var_dump($shop_page);
				  ?>
                  <? if(!empty($shop_page)): ?>
                  <div id="nav_cart">
                   <a href="<? print page_link($shop_page['id']); ?>/view:cart">
                     <span class="items cart_items_qty"><? print get_items_qty() ; ?></span>
                     <strong>Items</strong></a>
                     <img src="<? print TEMPLATE_URL ?>img/cart.png" alt="" />
                     <a href="<? print page_link($shop_page['id']); ?>/view:cart">View cart </a>
                  </div>
                  <? endif; ?>
                  
                  
                </div>
            </div><!-- /#header -->
            <div id="content">