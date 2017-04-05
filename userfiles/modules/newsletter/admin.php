<script>
    $(document).ready(function () {
        mw.tabs(
            {
                nav: '#newsletter-admin .mw-ui-btn-nav-tabs a',
                tabs: '#newsletter-admin .mw-ui-box-content'
            }
        );
    });
</script>
<div class="module-live-edit-settings" id="newsletter-admin">
    <div class="mw-ui-btn-nav mw-ui-btn-nav-tabs">
        <a class="mw-ui-btn active" href="javascript:;">Subscribers</a>
        <a class="mw-ui-btn" style="display: none;" href="javascript:;">Campaigns</a>
        <a class="mw-ui-btn" style="display: none;" href="javascript:;">Settings</a>
    </div>
    <div class="mw-ui-box">
        <div class="mw-ui-box-content" style="display: block;">
            <module type="newsletter/subscribers"/>
        </div>
        <div style="display: none;" class="mw-ui-box-content">
            <module type="newsletter/campaigns"/>
        </div>
        <div style="display: none;" class="mw-ui-box-content">
            <module type="newsletter/campaigns"/>
        </div>
    </div>
</div>
