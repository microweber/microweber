@php
 $past_page = site_url();
@endphp

@if (user_can_access('module.content.edit')):
<li>
    <a href="{{$past_page}}?editmode=y"
       class="btn btn-primary bg-white border-0 go-live-edit-href-set admin-toolbar-buttons">
        <img height="28" width="28" src="<?php print modules_url()?>/microweber/api/libs/mw-ui/assets/img/live-edit-button.svg" alt="">
        <span class="text-dark ms-2" style="font-size: 14px; font-weight: bold;">EDIT</span>
    </a>
</li>
@endif
