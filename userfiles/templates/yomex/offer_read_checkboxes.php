<div>
      
      

      
      
      
      
       <?php $features1 = false;
	   if(trim($post['custom_fields']['features_1_desc']) !='') {
		$features1 =  explode(',',$post['custom_fields']['features_1_desc']);
		$features1 = array_filter($features1 ,'is_string');
			$each_ul = ceil(count($features1)/3);
	   }?>
      <?php if(!empty($features1)): ?>
       
      
      
              <h3><?php print $post['custom_fields']['features_1_title'] ?></h3>
        <div class="features">
        <?php $i = 0; foreach($features1 as $item):	   ?>
      <?php if($i == 0): ?>
      <ul>
        <?php endif; ?>
       <li> <?php print $item ?> </li>
        <?php $i++; if($i == $each_ul): ?>
      </ul>
      <?php endif; ?>
      <?php if($i == $each_ul) $i = 0; ?>
      <?php endforeach; ?>

        </div>
      <?php endif; ?>
      



  <?php $features1 = false;
	   if(trim($post['custom_fields']['features_2_desc']) !='') {
		$features1 =  explode(',',$post['custom_fields']['features_2_desc']);
		$features1 = array_filter($features1 ,'is_string');
		
		$each_ul = ceil(count($features1)/3);
		
	   }?>
      <?php if(!empty($features1)): ?>
       
      
      
              <h3><?php print $post['custom_fields']['features_2_title'] ?></h3>
        <div class="features">
        <?php $i = 0; foreach($features1 as $item):	   ?>
      <?php if($i == 0): ?>
      <ul>
        <?php endif; ?>
       <li> <?php print $item ?> </li>
        <?php $i++; if($i == $each_ul): ?>
      </ul>
      <?php endif; ?>
      <?php if($i == $each_ul) $i = 0; ?>
      <?php endforeach; ?>
          <div class="clear"></div>
        </div>
      <?php endif; ?>
      
      
      <!--
        <h3>В стаите</h3>
        <div class="features">
          <ul>
            <li> Интернет </li>
            <li> Работно място </li>
            <li> Брави  с  магнитни карти </li>
            <li> Мини бар </li>
            <li> Климатична инсталация </li>
          </ul>
          <ul>
            <li> Сейф </li>
            <li> Сателитна телевизия </li>
            <li> Цифрова телевизия </li>
            <li> Радио </li>
            <li> Телефон в стаята и банята </li>
          </ul>
          <ul>
            <li> Антиалергични завивки </li>
            <li> Вана </li>
            <li> Сешоар </li>
            <li> Аксесоари за баня /халат и чехли/ </li>
            <li> Паник бутон в банята </li>
          </ul>
          <div class="clear"></div>
        </div>
        
        
        
        
        
        <h3>В хотела</h3>
        <div class="features">
          <ul>
            <li> Безжичен интернет </li>
            <li> Компютри в стаите </li>
            <li> 24 – часов рум сервиз </li>
            <li> Багажно отделение </li>
            <li> Перално помещение </li>
            <li> Химическо чистене </li>
            <li> Открит и подземен паркинг </li>
            <li> Трансфер от и до летището </li>
            <li> Ресторант </li>
            <li> Лоби бар </li>
          </ul>
          <ul>
            <li> Конферентни услуги </li>
            <li> Спа център </li>
            <li> Закрит басейн </li>
            <li> Фитнес център </li>
            <li> Солариум </li>
            <li> Сауна и инфрачервена сауна </li>
            <li> Парна баня </li>
            <li> Масажно студио </li>
            <li> Джакузи </li>
            <li> Казино </li>
          </ul>
          <ul>
            <li> Бинго </li>
            <li> Арт кафе </li>
            <li> Художествена галерия </li>
            <li> Магазин за сувенири </li>
            <li> Фризьорски салон </li>
            <li> Салон за красота </li>
            <li> 24 –часова охрана на хотела </li>
            <li> Стая за инвалиди </li>
            <li> Бизнес център </li>
          </ul>
          <div class="clear"></div>
        </div>
        
        -->
        
        
        
        
        
        
        
        
        
        
        <div class="clear"></div>
      </div>