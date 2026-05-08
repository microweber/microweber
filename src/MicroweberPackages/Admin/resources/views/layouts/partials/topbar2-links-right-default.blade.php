@php
 $past_page = site_url();
@endphp

{{-- AI-86 / TICKET-Q (cycle-97 2026-05-09): admin-context guard.
     The cycle-22 baseline gated the chip on
     `user_can_access('module.content.edit')` — that check confirms
     the USER has permission, but it does not confirm the current
     ROUTE is part of the admin panel. If a future regression
     accidentally @include's this partial inside a public template
     (e.g. via a renamed component, a bad layout extension, or an
     event_trigger emission misfiring), the chip would leak into
     the public hero. The `is_admin()` early-guard is a defense-in-
     depth check that returns the user-identity portion of "admin
     context" (the chip ONLY makes sense for an admin user). The
     auth check below stays as-is — both must pass.
     Pre-check is_admin() because non-admin users can never have
     the content.edit permission anyway, so this is a no-op for
     legitimate admin-layout usage. --}}
@if (is_admin() && user_can_access('module.content.edit'))
<li class="go-live-edit-nav-item-holder">
    <a href="{{admin_url('live-edit')}}"
       class="btn btn-light border-0 go-live-edit-href-set admin-toolbar-buttons" style="background-color: #e2f9e6; ">
        <img height="28" width="28" src="<?php print modules_url()?>/microweber/api/libs/mw-ui/assets/img/live-edit-button.svg" alt="">
        <span class="  ms-2" style="font-size: 14px; font-weight: bold;">EDIT</span>
    </a>
</li>
@endif
