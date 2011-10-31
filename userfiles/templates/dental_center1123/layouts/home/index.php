<?php

/*

type: layout

name: Home layout

description: Home site layout









*/



?>
<? include TEMPLATE_DIR. "header.php"; ?>

<div class="shadow">
  <div class="shadow-content box">
    <div id="HeadRotator">
      <div class="slider">
        <div class="frames">
          <div class="frame"> <img src="<? print TEMPLATE_URL ?>img/slide.jpg" alt="" /> </div>
          <div class="frame"> <img src="<? print TEMPLATE_URL ?>img/slide.jpg" alt="" /> </div>
          <div class="frame"> <img src="<? print TEMPLATE_URL ?>img/slide.jpg" alt="" /> </div>
          <div class="frame"> <img src="<? print TEMPLATE_URL ?>img/slide.jpg" alt="" /> </div>
        </div>
      </div>
      <div id="SliderControlls"></div>
    </div>
  </div>
</div>
<!-- /#shadow -->
<div id="Scroll"> <span class="slide_left"></span> <span class="slide_right"></span>
  <div id="ScrollContainer">
    <div id="ScrollHolder" class="slide_engine">
      <div class="shadow">
        <div class="shadow-content box"> <span class="img" style="background-image: url(<? print TEMPLATE_URL ?>img/profilaktika.jpg)"></span>
          <div class="scroll_info">
            <h3>Профилактика</h3>
            <p>Почистване на зъбен камък с ултразвуков светлинен скалер и полиране на зъби с швейцарски апарат (Air Flow).</p>
            <a href="<? print page_link(3427); ?>" class="more">Научи повече</a> </div>
        </div>
      </div>
      <div class="shadow">
        <div class="shadow-content box"> <span class="img" style="background-image: url(<? print TEMPLATE_URL ?>img/dentalna.jpg)"></span>
          <div class="scroll_info">
            <h3>Дентална Медицина</h3>
            <p>Екстрактрахирането, инцизирането и другите хирургични манипулации се извършват максимално атравматични и щадящо с най-подходящо обезболяване.</p>
            <a href="<? print page_link(3426); ?>" class="more">Научи повече</a> </div>
        </div>
      </div>
      <div class="shadow">
        <div class="shadow-content box"> <span class="img" style="background-image: url(<? print TEMPLATE_URL ?>img/detska.jpg)"></span>
          <div class="scroll_info">
            <h3>Детска Стоматология</h3>
            <p>По-трудните малки пациенти получават специална демонстрация и подготовка за следващото лечение.</p>
            <a href="<? print page_link(3425); ?>" class="more">Научи повече</a> </div>
        </div>
      </div>
      <!--<div class="shadow">
        <div class="shadow-content box"> <span class="img" style="background-image: url(<? print TEMPLATE_URL ?>img/profilaktika.jpg)"></span>
          <div class="scroll_info">
            <h3>Профилактика</h3>
            <p>Почистване на зъбен камък с ултразвуков светлинен скалер и полиране на зъби с швейцарски апарат (Air Flow).</p>
            <a href="#" class="more">Научи повече</a> </div>
        </div>
      </div>
      <div class="shadow">
        <div class="shadow-content box"> <span class="img" style="background-image: url(<? print TEMPLATE_URL ?>img/dentalna.jpg)"></span>
          <div class="scroll_info">
            <h3>Дентална Медицина</h3>
            <p>Екстрактрахирането, инцизирането и другите хирургични манипулации се извършват максимално атравматични и щадящо с най-подходящо обезболяване.</p>
            <a href="#" class="more">Научи повече</a> </div>
        </div>
      </div>
      <div class="shadow">
        <div class="shadow-content box"> <span class="img" style="background-image: url(<? print TEMPLATE_URL ?>img/detska.jpg)"></span>
          <div class="scroll_info">
            <h3>Детска СТоматология</h3>
            <p>По-трудните малки пациенти получават специална демонстрация и подготовка за следващото лечение.</p>
            <a href="#" class="more">Научи повече</a> </div>
        </div>
      </div>-->
    </div>
    <!-- /#ScrollHolder -->
  </div>
  <!-- /#ScrollContainer -->
</div>
<!-- /#Scroll -->
<? include   TEMPLATE_DIR.  "footer.php"; ?>
