</div>
<!-- /#content -->
</div>
<!-- /#container -->

<div id="footer">
  <div class="footer-content">
    <div class="footer-list">
      <h3>Account</h3>
      <ul>
        <li><a href="<? print site_url('dashboard') ?>">My dashboard</a></li>
        <li><a href="<? print site_url('dashboard/action:find-friends') ?>">Find friends</a></li>
      </ul>
    </div>
    <div class="footer-list">
      <h3>Education</h3>
      <ul>
        <li><a href="<? print site_url('education') ?>">Education materials</a></li>
        <li><a href="<? print site_url('questions/action:list') ?>">Questions and anwsers</a></li>
 
      </ul>
    </div>
    <div class="footer-list">
      <h3>Fun</h3>
      <ul>
        <li><a href="<? print site_url('games') ?>">Play games</a></li>
        <li><a href="<? print site_url('toy-swap') ?>">Toy swap</a></li>
      </ul>
    </div>
    <div class="footer-list">
      <h3>Videos</h3>
      <ul>
        <li><a href="<? print site_url('skid-e-tube') ?>">Skide-e-tube</a></li>
        <li><a href="<? print site_url('movie-night') ?>">Movie night</a></li>
         
      </ul>
    </div>
    <div class="footer-list">
      <h3>About us</h3>
      <ul>
        <li><a href="<? print site_url('what-is-skid-e-kids') ?>">What is Skid-e-kids</a></li>
        <li><a href="<? print site_url('blog') ?>">Blog</a></li>
 
       
       
       
      </ul>
    </div>
  </div>
  <!-- /#footer-content -->
</div>
<!-- /#footer -->
<?php
//p(CI::model('core')->path_list);
//p(CI::model('core')->cache_storage_hits);
 
include (ACTIVE_TEMPLATE_DIR.'footer_stats_scripts.php') ?>

<div id="addr">
  <div id="addr-content">
    <address>
    &copy; <?php echo date('Y') ?> <a href="<? print site_url(); ?>">Skid-e-Kids Network</a>. All Rights Reserved  - <a href="<? print page_link(518); ?>">Terms and of use</a> | <a href="<? print page_link(519); ?>">Privacy policy</a> | <a href="<? print site_url('toy-swap-terms-and-conditions'); ?>">Toy swap terms &amp; conditions</a> | <a href="<? print site_url('contacts') ?>">Contact us</a>
    </address>
    <span id="web-design">Created by <a href="http://ooyes.net" title="Website Design">ooYes.net</a></span> </div>
</div>
<div id="overlay" style="display: none">&nbsp;</div>
<div id="preloader"><span>&nbsp;</span></div>
<div id="header_login_form">
  <microweber module="users/login"></microweber>
  <span class="modalclose" title="Close" onclick="modal.close()"></span>
</div>
<div id="tip"></div>
</body></html>