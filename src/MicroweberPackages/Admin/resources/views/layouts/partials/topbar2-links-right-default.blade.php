@php
 $past_page = site_url();
@endphp

@if (user_can_access('module.content.edit'))
<li class="go-live-edit-nav-item-holder">
    <a href="{{admin_url('live-edit')}}"
       class="btn btn-light border-0 go-live-edit-href-set admin-toolbar-buttons">
        <img height="28" width="28" src="<?php print modules_url()?>/microweber/api/libs/mw-ui/assets/img/live-edit-button.svg" alt="">
        <span class="  ms-2" style="font-size: 14px; font-weight: bold;">EDIT</span>
    </a>
</li>
@endif
