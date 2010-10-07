
                    <div id="home-image">
                        <ul>
                            <li>
                                <a href="#">
                                    <img src="<?php print TEMPLATE_URL; ?>img/slide_image_0.jpg" alt=""  />
                                    <strong>... вашият път е наша цел ...</strong>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <img src="<?php print TEMPLATE_URL; ?>img/slide_image_1.jpg" alt=""  />
                                    <strong>Consectetur adipiscing elit.</strong>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div id="Fslider">
                        <a href="#" id="fsliderleft" onclick="FslideLeft();return false;">&nbsp;</a>
                        <a href="#" id="fsliderright" onclick="FslideRight();return false">&nbsp;</a>
                        <div id="Fslidercontent">
                            <h2 class="blue-title border-bottom">Избрахме за Вас</h2>
                            <div id="sliderAction">
      							<?php $params = array();
	  							    $params['selected_categories'] = array(1953); //izbrahme za vas	
	  							    $items = $this->content_model->getContentAndCache($params); 
	  							?>
								
								<?php if(!empty($items)): ?>
								<!--	<ul> -->
									<?php foreach($items as $item): ?>
									
										<li>
											<a href="#">
                                           		<span style="background-image: url(<?php print TEMPLATE_URL; ?>img/slider_img_1.jpg)">
             										<strong>&euro; 387</strong>
                                           		</span>
                                           		
                                           		<big>Кападокия</big>
                                           		Автобусна екскурзия без нощни преходи
                                        	</a>
										</li>

									<?php endforeach; ?>
								<!--	</ul>-->
								<?php endif; ?>								
                                
                                    <!--
									<ul>
                                    <li>
                                        <a href="#">
                                           <span style="background-image: url(<?php print TEMPLATE_URL; ?>img/slider_img_1.jpg)">
                                                <strong>&euro; 387</strong>
                                           </span>
                                           <big>Кападокия</big>
                                           Автобусна екскурзия без нощни преходи
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                           <span style="background-image: url(<?php print TEMPLATE_URL; ?>img/slider_img_2.jpg)">
                                                <strong>&euro; 387</strong>
                                           </span>
                                           <big>Виена - Прага</big>
                                           Автобусна екскурзия без нощни преходи
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                           <span style="background-image: url(<?php print TEMPLATE_URL; ?>img/slider_img_3.jpg)">
                                                <strong>&euro; 387</strong>
                                           </span>
                                           <big>Швейцария</big>
                                           Автобусна екскурзия без нощни преходи
                                        </a>
                                    </li>
                                </ul>
                                <ul>
                                    <li>
                                        <a href="#">
                                           <span style="background-image: url(<?php print TEMPLATE_URL; ?>img/slider_img_2.jpg)">
                                                <strong>&euro; 387</strong>
                                           </span>
                                           <big>Lorem ipsum dolor </big>
                                           Aliquam porttitor condimentum mi sed molestie.
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                           <span style="background-image: url(<?php print TEMPLATE_URL; ?>img/slider_img_3.jpg)">
                                                <strong>&euro; 387</strong>
                                           </span>
                                           <big>Швейцария</big>
                                           Автобусна екскурзия без нощни преходи
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                           <span style="background-image: url(<?php print TEMPLATE_URL; ?>img/slider_img_1.jpg)">
                                                <strong>&euro; 387</strong>
                                           </span>
                                           <big>Suspendisse </big>
                                           Suspendisse sapien est, vestibulum ac
                                        </a>
                                    </li>
                                </ul>
                                <ul>
                                    <li>
                                        <a href="#">
                                           <span style="background-image: url(<?php print TEMPLATE_URL; ?>img/slider_img_2.jpg)">
                                                <strong>&euro; 387</strong>
                                           </span>
                                           <big>Nunc dignissim</big>
                                           Nulla dui erat, vestibulum vitae suscipit ac
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                           <span style="background-image: url(<?php print TEMPLATE_URL; ?>img/slider_img_1.jpg)">
                                                <strong>&euro; 387</strong>
                                           </span>
                                           <big>Donec eu metus</big>
                                           ultrices posuere cubilia Curae
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                           <span style="background-image: url(<?php print TEMPLATE_URL; ?>img/slider_img_3.jpg)">
                                                <strong>&euro; 387</strong>
                                           </span>
                                           <big>Vestibulum</big>
                                           Morbi mattis mauris mauris
                                        </a>
                                    </li>
                                </ul>-->
                            </div><!-- /sliderAction -->
                        </div><!-- /Fslidercontent -->
                    </div><!-- /Fslider -->

                    <div class="offers">
                         <a href="#" class="seeall right">Виж всички</a>
                         <h2 class="gtitle">Сезонни предложения</h2>
                         
                         
                         
                         
                         <!-- Manevski -->
                         
      <!--                   
                          <?php $related = array();
	  $related['selected_categories'] = array(1954);
	  $related['is_special'] = 'y';
	  $limit[0] = 0;
	  $limit[1] = 30;
	  $related = $this->content_model->getContentAndCache($related, false,$limit ); ?>
  
  
  
  
  
  
   <?php if(!empty($related)): ?>
    <div class="offers"> <a href="<?php print $this->content_model->getContentURLByIdAndCache($page['id']); ?>/view:all/is_special:y" class="seeall right">Виж всички</a>
      <h2 class="gtitle">Специални предложения</h2>
      <span class="hr">&nbsp;</span>
      <ul class="offers-list 3rd-elem">
        <?php foreach($related as $item): ?>
        <?php $thumb = $this->content_model->contentGetThumbnailForContentId($item['id'], 250); 
	  $more = $this->core_model->getCustomFields($table = 'table_content', $id = $item['id']);

	  ?>
        <li> <a href="<?php print $this->content_model->contentGetHrefForPostId($item['id']) ; ?>"> <span style="background-image: url('<?php print $thumb ?>'); background-position:center center;"> <strong class="price">&euro; <?php print intval($more['price']); ?></strong> </span> <big><?php print character_limiter($item['content_title'], 30, '...'); ?></big> <?php print (character_limiter($item['content_description'], 50, '...')); ?></a> </li>
        <?php endforeach; ?>
      </ul>
    </div>
    <?php endif;  ?>
                         
                         
                 -->        
                         
                          <!-- /Manevski -->
                         
                         
                         
                         
                         
                         
                         
                         
                         
                         
                         
                         
                         
                         
                         
                         
                         
                         <span class="hr">&nbsp;</span>
                         <ul class="offers-list">
                            <li>
                                <a href="#">
                                   <span style="background-image: url(<?php print TEMPLATE_URL; ?>img/offer_1.jpg)">
                                        <strong class="price">&euro; 387</strong>
                                   </span>
                                   <big>Сицилия и Неапол</big>
                                   Автобусна екскурзия без нощни преходи
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                   <span style="background-image: url(<?php print TEMPLATE_URL; ?>img/offer_2.jpg)">
                                        <strong class="price">&euro; 387</strong>
                                   </span>
                                   <big>Френска ривиера</big>
                                   Автобусна екскурзия без нощни преходи
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                   <span style="background-image: url(<?php print TEMPLATE_URL; ?>img/offer_3.jpg)">
                                        <strong class="price">&euro; 387</strong>
                                   </span>
                                   <big>Париж</big>
                                   Автобусна екскурзия без нощни преходи
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                   <span style="background-image: url(<?php print TEMPLATE_URL; ?>img/offer_4.jpg)">
                                        <strong class="price">&euro; 387</strong>
                                   </span>
                                   <big>Испания и Андалусия</big>
                                   Автобусна екскурзия без нощни преходи
                                </a>
                            </li>
                         </ul>
                    </div>
                    <div class="offers offers-tabs">
                        <ul class="tab-nav">
                            <li><a href="#aktualni-promocii">Актуални промоции</a></li>
                            <li><a href="#spa-hoteli">Спа хотели</a></li>
                            <li><a href="#balneologia">Балнеология</a></li>
                            <li><a href="#kulturen-turizam">Културен туризъм</a></li>
                        </ul>
                        <div class="c"></div>
                        <div id="aktualni-promocii" class="xtab">
                            <ul class="offers-list">
                              <li>
                                  <a href="#">
                                     <span style="background-image: url(<?php print TEMPLATE_URL; ?>img/offer_1.jpg)">
                                          <strong class="price">&euro; 387</strong>
                                     </span>
                                     <big>Сицилия и Неапол</big>
                                     Автобусна екскурзия без нощни преходи
                                  </a>
                              </li>
                              <li>
                                  <a href="#">
                                     <span style="background-image: url(<?php print TEMPLATE_URL; ?>img/offer_2.jpg)">
                                          <strong class="price">&euro; 387</strong>
                                     </span>
                                     <big>Френска ривиера</big>
                                     Автобусна екскурзия без нощни преходи
                                  </a>
                              </li>
                              <li>
                                  <a href="#">
                                     <span style="background-image: url(<?php print TEMPLATE_URL; ?>img/offer_3.jpg)">
                                          <strong class="price">&euro; 387</strong>
                                     </span>
                                     <big>Париж</big>
                                     Автобусна екскурзия без нощни преходи
                                  </a>
                              </li>
                              <li>
                                  <a href="#">
                                     <span style="background-image: url(<?php print TEMPLATE_URL; ?>img/offer_4.jpg)">
                                          <strong class="price">&euro; 387</strong>
                                     </span>
                                     <big>Испания и Андалусия</big>
                                     Автобусна екскурзия без нощни преходи
                                  </a>
                              </li>
                           </ul>
                           <a href="#" class="seeall right" style="margin: 25px 0 0">Виж всички</a>
                        </div>
                        <div class="xtab" id="spa-hoteli">2</div>
                        <div class="xtab" id="balneologia">3</div>
                        <div class="xtab" id="kulturen-turizam">4</div>
                    </div>
                    <div class="pad">
                        <div class="col">
                            <h2 class="blue-title border-bottom">Online хотелски резервации</h2>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin id metus ac magna feugiat condimentum sit amet eget purus. Curabitur placerat accumsan nibh non blandit. Aliquam et elit nibh. Pellentesque vel lorem neque, eget rhoncus purus. Quisque eu metus id elit pellentesque dapibus vitae non tortor.
                            <br /> <br />
                            <a href="#" class="more">Прочети повече</a>
                            <br /> <br />
                        </div>
                        <div class="col">
                            <h2 class="blue-title border-bottom">Корпоративни клиенти</h2>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin id metus ac magna feugiat condimentum sit amet eget purus. Curabitur placerat accumsan nibh non blandit. Aliquam et elit nibh. Pellentesque vel lorem neque, eget rhoncus purus. Quisque eu metus id elit pellentesque dapibus vitae non tortor.
                            <br /> <br />
                            <a href="#" class="more">Прочети повече</a>
                            <br /> <br />
                        </div>
                        <div class="col">
                            <h2 class="blue-title border-bottom">Други услуги</h2>
                            <ul class="list">
                              <li><a href="#">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</a></li>
                              <li><a href="#">Duis ut libero orci, vitae molestie lectus.</a></li>
                              <li><a href="#">Nullam porta sapien quis tellus egestas sed gravida lorem bibendum.</a></li>
                            </ul>
                            <br /> <br />
                            <a href="#" class="more">Прочети повече</a>
                            <br /> <br />
                        </div>
                        <div class="col">
                            <h2 class="blue-title border-bottom">Абитюрентски балове</h2>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin id metus ac magna feugiat condimentum sit amet eget purus. Curabitur placerat accumsan nibh non blandit. Aliquam et elit nibh. Pellentesque vel lorem neque, eget rhoncus purus. Quisque eu metus id elit pellentesque dapibus vitae non tortor.
                            <br /> <br />
                            <a href="#" class="more">Прочети повече</a>
                            <br /> <br />
                        </div>
                    </div>
                    <span class="hr" style="width: ">&nbsp;</span>
                    <div class="pad relative">
                        <a href="#" class="left" style="margin-right: 15px;"><img src="<?php print TEMPLATE_URL; ?>img/yomexaddb.jpg" alt="" /></a>
                        <div id="mw-item">
                            <div class="col">
                                <h2 class="blue-title border-bottom">Идеи за пътуване</h2>
                                <form method="post" action="#" id="subscribe" class="validate">
                                 За да получавате нашите идеи, моля пратете ни е-мейла си.
                                 <br /> <br />
                                 <span class="input">
                                     <input type="text" class="required-email" value="E-mail" onfocus="this.value=='E-mail'?this.value='':''" onblur="this.value==''?this.value='E-mail':''" />
                                 </span> <br />
                                    <a href="#" class="submit seeall">Регистрирай се</a>
                                    <input type="submit" class="xsubmit" />
                                    <br /><br />
                                </form>
                            </div>
                            <div class="col">
                                <h2 class="blue-title border-bottom">Подари ваучер</h2>
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin id metus ac magna feugiat condimentum sit amet eget purus. Curabitur placerat accumsan nibh non blandit. Aliquam et elit nibh. Pellentesque vel lorem neque, eget rhoncus purus.
                                <br /> <br />
                                <a href="#" class="more">Прочети повече</a>
                                <br /> <br />
                            </div>
                            <div class="col yomex-baat">
                                <h2 class="blue-title border-bottom">Yomex</h2>
                                Yomex е член на:<br />
                                <a target="_blank" href="http://www.baatbg.org" title="Българска асоциация за алтернативен туризъм"><img src="<?php print TEMPLATE_URL; ?>img/baat.jpg" alt="БААТ" /></a><br />
                                При нас можете да платите с:  <br />
                                <img src="<?php print TEMPLATE_URL; ?>img/visa.jpg" alt="Visa" title="Visa" />
                                <img src="<?php print TEMPLATE_URL; ?>img/masterc.jpg" alt="Master Card" title="Master Card" />
                                <img src="<?php print TEMPLATE_URL; ?>img/paypal.jpg" alt="Paypal" title="Paypal" />
                            </div>
                            <div id="ismsg" class="clear"><a href="#">Удостоверение за туроператор N:25687</a> / <a href="#">Удостоверение за регистрация на турагент ПР -34532</a> </div>
                        </div>
                    </div>
                    <div id="footernav">
                        <ul>
                          <li><a href="#">х-ли в София</a>/</li>
                          <li><a href="#">бутикови хотели, почивки в чужбина</a>/</li>
                          <li><a href="#">зали за конференция</a>/</li>
                          <li><a href="#">абитюрентски бал в Шератон</a>/</li>
                          <li><a href="#">х-ли в София, бутикови хотели</a>/</li>
                          <li><a href="#">почивки в чужбина</a>/</li>
                          <li><a href="#">зали за конференция</a>/</li>
                          <li><a href="#">абитюрентски бал в Шератон</a>/</li>
                        </ul>
                    </div>

                
    