<?php

/*

type: layout
content_type: dynamic
name: Homepage layout

description: Home layout

*/

?>
<? include TEMPLATE_DIR. "header.php"; ?>

<section id="content">
  <div class="container edit"  field="content" rel="page"> 
    <!-- Icons -->
    <div class="row">
      <div class="span12">
        <div class="thumbnails">
          <div class="mw-row">
            <div class="mw-col" style="width: 25%">
              <div class="mw-col-container">
                <div class="element thumbnail"> <img src="{TEMPLATE_URL}img/icon-1.png" alt="" class="element img-circle" />
                  <h2>Easy to customize</h2>
                  <p class="p0">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh eui
                    smod tincidunt ut laor.</p>
                </div>
              </div>
            </div>
            <div class="mw-col" style="width: 25%">
              <div class="mw-col-container">
                <div class="element thumbnail"> <img src="{TEMPLATE_URL}img/icon-2.png" alt="" class="element img-circle" />
                  <h2>Responsive Template</h2>
                  <p class="p0">Lorem ipsum dolor sit amet, consecteter
                    dipiscing elit, sed diam nonummy nibh euis
                    mod tincidunt ut laore.</p>
                </div>
              </div>
            </div>
            <div class="mw-col" style="width: 25%">
              <div class="mw-col-container">
                <div class="element thumbnail"> <img src="{TEMPLATE_URL}img/icon-3.png" alt="" class="element img-circle" />
                  <h2>Clean Design</h2>
                  <p class="p0">Lorem ipsum dolor sit amet, consectet
                    er adipiscing elit, sed diam nonummy nibh euismod tincidunt ut lao.</p>
                </div>
              </div>
            </div>
            <div class="mw-col" style="width: 25%">
              <div class="mw-col-container">
                <div class="element thumbnail"> <img src="{TEMPLATE_URL}img/icon-4.png" alt="" class="element img-circle" />
                  <h2>Calculation</h2>
                  <p class="p0">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh eui
                    smod tincidunt ut laor.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="section-4">
    <div class="container edit"  field="sub_content" rel="page"> 
      <!-- Strip with button -->
      <div class="row">
        <div class="span12">
          <h2>Welcome to Decision. We provide <span>Clean</span> and <span>Simple</span> design.</h2>
          <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh eui
            smod tincidunt ut laor.</p>
          <a href="#" class="btn btn-large btn-success"><i class="icon-star icon-white"></i> Lets work together!</a> </div>
      </div>
    </div>
  </div>
  <div class="container edit"  field="sub_content2" rel="page">
    <div class="row"> 
      <!-- welcome -->
      <div class="span4">
        <div class="border-right">
          <h2 class="indent-1">Welcome!</h2>
          <p class="p2">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh eui smod tincidunt ut laoreet dolore magna aliquam erat volutpat.</p>
          <ul class="list p2">
            <li><a href="#">In faucibus orci luctus et</a></li>
            <li><a href="#">Ultrices posuere cubilia Curae</a></li>
            <li><a href="#">Suspendisse sollicitudin velit sed leo</a></li>
            <li><a href="#">Ut pharetra augue nec augue</a></li>
            <li><a href="#">Nam elit agna,endrerit sit amet</a></li>
          </ul>
          <a href="#" class="btn btn-small btn-success"><i class="icon-share-alt icon-white"></i> More Features</a> </div>
      </div>
      <!-- meet our team -->
      <div class="span8">
        <div class="clearfix">
          <h2 class="indent-1 bot-0 pull-left">Meet Our Team</h2>
          <div class="btn-group tab-button" id="myTab"> <a data-toggle="tab" href="#team1" class="btn" type="button">Team 1</a> <a data-toggle="tab" href="#team2" class="btn" type="button">Team 2</a> <a data-toggle="tab" href="#team3" class="btn" type="button">Team 3</a> </div>
        </div>
        <div class="tab-content" id="myTabContent">
          <div id="team1" class="tab-pane fade in active">
            <ul class="thumbnails thumbnails_3">
              <li class="span2">
                <div class="thumbnail_3">
                  <figure class="img-circle"><img src="{TEMPLATE_URL}img/page1-img8.jpg" alt="" class="img-circle"></figure>
                  <div><a href="#" class="lead">Tomas Brown</a><br>
                    <small>business analyst</small> Nam aliquam volutpat leo vel lorem bibendum suncea elit.</div>
                </div>
              </li>
              <li class="span2">
                <div class="thumbnail_3">
                  <figure class="img-circle"><img src="{TEMPLATE_URL}img/page1-img9.jpg" alt="" class="img-circle"></figure>
                  <div><a href="#" class="lead">Andrew Johnson</a><br>
                    <small>business planning</small> Nam aliquam volutpat leo vel lorem bibendum suncea elit.</div>
                </div>
              </li>
              <li class="span2">
                <div class="thumbnail_3">
                  <figure class="img-circle"><img src="{TEMPLATE_URL}img/page1-img10.jpg" alt="" class="img-circle"></figure>
                  <div><a href="#" class="lead">Sean Pete</a><br>
                    <small>Client Support</small> Nam aliquam volutpat leo vel lorem bibendum suncea elit.</div>
                </div>
              </li>
              <li class="span2">
                <div class="thumbnail_3">
                  <figure class="img-circle"><img src="{TEMPLATE_URL}img/page1-img11.jpg" alt="" class="img-circle"></figure>
                  <div><a href="#" class="lead">Sarah Smith</a><br>
                    <small>strategy development</small> Nam aliquam volutpat leo vel lorem bibendum suncea elit.</div>
                </div>
              </li>
            </ul>
          </div>
          <div id="team2" class="tab-pane fade">
            <ul class="thumbnails thumbnails_3">
              <li class="span2">
                <div class="thumbnail_3">
                  <figure class="img-circle"><img src="{TEMPLATE_URL}img/page1-img11.jpg" alt="" class="img-circle"></figure>
                  <div><a href="#" class="lead">Tomas Brown</a><br>
                    <small>business analyst</small> Nam aliquam volutpat leo vel lorem bibendum suncea elit.</div>
                </div>
              </li>
              <li class="span2">
                <div class="thumbnail_3">
                  <figure class="img-circle"><img src="{TEMPLATE_URL}img/page1-img8.jpg" alt="" class="img-circle"></figure>
                  <div><a href="#" class="lead">Andrew Johnson</a><br>
                    <small>business planning</small> Nam aliquam volutpat leo vel lorem bibendum suncea elit.</div>
                </div>
              </li>
              <li class="span2">
                <div class="thumbnail_3">
                  <figure class="img-circle"><img src="{TEMPLATE_URL}img/page1-img9.jpg" alt="" class="img-circle"></figure>
                  <div><a href="#" class="lead">Sean Pete</a><br>
                    <small>Client Support</small> Nam aliquam volutpat leo vel lorem bibendum suncea elit.</div>
                </div>
              </li>
              <li class="span2">
                <div class="thumbnail_3">
                  <figure class="img-circle"><img src="{TEMPLATE_URL}img/page1-img10.jpg" alt="" class="img-circle"></figure>
                  <div><a href="#" class="lead">Sarah Smith</a><br>
                    <small>strategy development</small> Nam aliquam volutpat leo vel lorem bibendum suncea elit.</div>
                </div>
              </li>
            </ul>
          </div>
          <div id="team3" class="tab-pane fade">
            <ul class="thumbnails thumbnails_3">
              <li class="span2">
                <div class="thumbnail_3">
                  <figure class="img-circle"><img src="{TEMPLATE_URL}img/page1-img10.jpg" alt="" class="img-circle"></figure>
                  <div><a href="#" class="lead">Tomas Brown</a><br>
                    <small>business analyst</small> Nam aliquam volutpat leo vel lorem bibendum suncea elit.</div>
                </div>
              </li>
              <li class="span2">
                <div class="thumbnail_3">
                  <figure class="img-circle"><img src="{TEMPLATE_URL}img/page1-img11.jpg" alt="" class="img-circle"></figure>
                  <div><a href="#" class="lead">Andrew Johnson</a><br>
                    <small>business planning</small> Nam aliquam volutpat leo vel lorem bibendum suncea elit.</div>
                </div>
              </li>
              <li class="span2">
                <div class="thumbnail_3">
                  <figure class="img-circle"><img src="{TEMPLATE_URL}img/page1-img8.jpg" alt="" class="img-circle"></figure>
                  <div><a href="#" class="lead">Sean Pete</a><br>
                    <small>Client Support</small> Nam aliquam volutpat leo vel lorem bibendum suncea elit.</div>
                </div>
              </li>
              <li class="span2">
                <div class="thumbnail_3">
                  <figure class="img-circle"><img src="{TEMPLATE_URL}img/page1-img9.jpg" alt="" class="img-circle"></figure>
                  <div><a href="#" class="lead">Sarah Smith</a><br>
                    <small>strategy development</small> Nam aliquam volutpat leo vel lorem bibendum suncea elit.</div>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <!-- client logo -->
    <div class="row edit"  field="sub_content2" rel="global" >
      <div class="span12">
        <ul class="list-banners">
          <li><a href="#"><img src="{TEMPLATE_URL}img/banner-1.png" alt=""></a></li>
          <li><a href="#"><img src="{TEMPLATE_URL}img/banner-2.png" alt=""></a></li>
          <li><a href="#"><img src="{TEMPLATE_URL}img/banner-3.png" alt=""></a></li>
          <li><a href="#"><img src="{TEMPLATE_URL}img/banner-4.png" alt=""></a></li>
          <li><a href="#"><img src="{TEMPLATE_URL}img/banner-5.png" alt=""></a></li>
          <li><a href="#"><img src="{TEMPLATE_URL}img/banner-6.png" alt=""></a></li>
        </ul>
      </div>
    </div>
  </div>
</section>

<!-- /#main-content -->

<? include TEMPLATE_DIR. "footer.php"; ?>
