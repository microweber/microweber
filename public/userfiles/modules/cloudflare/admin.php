<?php must_have_access(); ?>
<div class="alert alert-primary" role="alert">
    <h2>Cloudflare module has no admin panel</h2>
    <p>This module fetches the Cloudflare IPs and sets them as trusted proxies in the Laravel trusted proxies configuration.</p>
    <p>This is done automatically and there is no need for an admin panel.</p>
    <p>If you set any value from <code>config/trustedproxy.php</code> in <code>trustedproxy.proxies</code> , this module respects it and does not override it.</p>

</div>
