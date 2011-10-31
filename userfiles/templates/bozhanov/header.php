<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" xmlns:v='urn:schemas-microsoft-com:vml'>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<title>{content_meta_title}</title>
<meta NAME="Description" CONTENT="{content_meta_description}">
<script type="text/javascript">
            imgurl="<? print TEMPLATE_URL ?>img/";
			 js_url_sfiri="<? print TEMPLATE_URL ?>js/";
			  templateurl="<? print TEMPLATE_URL ?>";
         </script>
<link rel="stylesheet" href="<? print TEMPLATE_URL ?>css/style.css" type="text/css" media="screen"  />
<link rel="stylesheet" href="<? print TEMPLATE_URL ?>js/decorative-gallery-demo/images_frame.css" type="text/css" media="screen"  />
<script type="text/javascript" src="http://google.com/jsapi"></script>
<script type="text/javascript">google.load("jquery", "1.4.2");</script>
 
<script type="text/javascript" src="<? print TEMPLATE_URL ?>js/lib.js"></script>
<script type="text/javascript" src="<? print TEMPLATE_URL ?>js/functions.js"></script>
<script type="text/javascript" src="<? print TEMPLATE_URL ?>js/sifr.js"></script>
<script type="text/javascript" src="<? print TEMPLATE_URL ?>js/sifr.setup.js"></script>
</head>
<body>

<div id="container">
<div id="header">
  <div class="wrap sunwrap" >
    
    <!--  <ul id="nav" class="nav">
                      <li><a class="nav_font" href="#">Начало</a></li>

                      <li><a  class="nav_font" href="#">Книгата</a></li>
                      <li><a  class="nav_font" href="#hp">Автора</a></li>
                      <li style="display: none"><a href="#">Коментари</a></li>
                      <li><a  class="nav_font" href="#">Контакти</a></li>
                    </ul>-->
                    
                          <!--  <h2 class="font_top">Бъди промяната която искаш да видиш в света</h2>-->
              <br />
 
   <h2 class="font_top">Официалният сайт на Димитър Божанов</h2>     
  
<h2 class="font_top2">за позитивно мислене и виртуални срещи с хубави хора</h2>     




 <? $nav = CI::model ( 'content' )->getMenuItemsByMenuName('main_menu');
		
		//p($nav);
		?>
      <ul id="nav2" class="nav2">
        <? foreach($nav as $n): ?>
        <li <? if($n["is_active"] == true) : ?>  class="active"  <? endif; ?> ><a class="navbtn<? if($n["is_active"] == true) : ?> active<? endif; ?>" href="<? print ucwords($n["url"]); ?>"><? print ucwords($n["title"]); ?></a></li>
        <? endforeach; ?>
     
      </ul>
<!-- <a id="order-top" href="http://coffee.massmediapublishing.com/order.php" title="Поръчай онлайн">Поръчай онлайн</a>-->

   
    <div class="c" style="padding-top: 15px;">&nbsp;</div>
    
    
    


    
    
    <!--<h2 style="width: 330px"  id="author">Димитър Божанов</h2>
    <h2 id="book-title">&bdquo;Кафе за събуждане&rdquo; </h2>
    <h2 id="book-desription">е книга за смисъла на живота.</h2>-->
    <div class="c" style="padding-bottom: 30px;">&nbsp;</div>
    
    
    
    
   
    <!-- /#about-the-book -->
  </div>
</div>
<!-- /#header -->
<div id="subscribe-bar">
                <div class="wrap">
                    <h3>Почерпи се безплатно кафе</h3>
                    <form name="mc-embedded-subscribe-form" target="_blank" method="post" action="http://ooyes.us1.list-manage.com/subscribe/post?u=2a532094e3945beb2eec848ba&amp;id=b4b42afc83">
                        <span class="field">
                            <input name="EMAIL" class="required-email" type="text" value="Запиши се тук  с твоя e-mail" />
                        </span>
                        <input class="type-submit" type="submit" value="" />
                    </form>
                   
                </div>
            </div><!-- /#subscribe-bar -->
            
           <!-- <div id="sun">&nbsp;</div>-->