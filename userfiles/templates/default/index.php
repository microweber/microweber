<?php

/*

type: layout
content_type: dynamic
name: Homepage layout

description: Home layout

*/

?>
<? include THIS_TEMPLATE_DIR. "header.php"; ?>


<div class="container edit" id="home-top">
    <div class="mw-row">
      <div class="mw-col" style="width: 50%">
        <div class="mw-col-container">
            <module type="pictures" template="bootstrap_carousel"  />
        </div>
      </div>
      <div class="mw-col" style="width: 50%">
          <div class="mw-col-container">
              <div class="edit">
                  <h2 class="element">Welcome to Microweber</h2>
                  <h4 class="element">New World Theme</h4>
                  <blockquote class="element"><em>Imagine " ... A world without rules and controls, without borders or boundaries; a world where anything is possible. Where we go from there is a choice I leave to you. ... "</em></blockquote>
                  <p class="element">
                    You can edit this text in what ever ways you like.<br>
                    You can format it, delete it or even insert other elements inside it. <br>
                    You can do what ever you want. It's up to your imagination. <br>
                  </p>
                  <p class="element"><a href="javascript:;" class="btn btn-primary btn-large right">Demo &raquo;</a></p>
              </div>
          </div>
      </div>
    </div>
</div>




<div class="edit" style="height: 100px;">



</div>





<? include THIS_TEMPLATE_DIR. "footer.php"; ?>
