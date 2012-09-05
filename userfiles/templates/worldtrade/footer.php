<div id="footer">
        <div class="right_col right">
          <div class="pattern_line"></div>
          <p class="footer_text">Всички цени са в български лева <br />
          

 
<? 
				   $login = array();
				   $login['content_layout_name'] = 'distributors';
				  
				  $login=get_pages($login);
				  $login = $login[0];
				//  var_dump($shop_page);
				  ?>
        <? if(!empty($login)): ?>
          
          
<small><a class="small_link" href="<? print page_link($login['id']); ?>" ><? print  $login['content_title'] ?></a></small>

<? endif; ?>

</p>






          <a  href="<? print site_url(); ?>" class="footer_logo"></a> </div>
        <div class="clener"></div>
      </div>
      <img src="<? print TEMPLATE_URL ?>images/bg-image.gif" alt="" id="bg-img" /> </div>
  </div>
</div>
</body>
</html>