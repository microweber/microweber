<?php

return;

?><!-- Mautic Tracking script -->
<script>
    (function(w,d,t,u,n,a,m){w['MauticTrackingObject']=n;
        w[n]=w[n]||function(){(w[n].q=w[n].q||[]).push(arguments)},a=d.createElement(t),
        m=d.getElementsByTagName(t)[0];a.async=1;a.src=u;m.parentNode.insertBefore(a,m)
    })(window,document,'script','<?php print $this->getConfig('Mautic_Tracking', 'code'); ?>','mt');

    mt('send', 'pageview');
</script>
