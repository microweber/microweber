 <!-- sidebar box 1 -->
  <div class="content_right_box">
    <h4>We can help you with</h4>
    <p>We are committed to handing over your project on time and high quality.</p>
    <? $services = get_page('services');	  ?>
    <microweber module="content/pages_tree" from="<? print $services['id'] ?>" />
    <p>Quisque eleifend, arcu a dictum varius, risus neque venenatis arcu. Quisque
      eleifend.</p>
  </div>