 <? $view = url_param('view'); ?>
<nav id="primary">
  <ul>
    <li <? if(trim($view) == ''): ?> class="current" <? endif; ?>> <a href="index.html"> <span class="icon dashboard"></span> Dashboard </a> </li>
    <li <? if(trim($view) == 'edit_profile'): ?> class="current" <? endif; ?> > <a href="<? print site_url('members/view:edit_profile') ?>"> <span class="icon pencil"></span> My profile </a> </li>
    <li <? if(trim($view) == 'posts'): ?> class="current" <? endif; ?> > <a href="<? print site_url('members/view:posts') ?>"> <span class="icon tables"></span> My jobs ads </a> </li>
    <li > <a href="charts.html"> <span class="icon chart"></span> <span class="badge">4</span> Charts </a> </li>
  </ul>
</nav>
