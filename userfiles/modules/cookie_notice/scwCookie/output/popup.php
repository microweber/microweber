<?php
$scw_cookie_style = '';
if(!empty($this->config['backgroundColor'])) {
	$scw_cookie_style = ' style="background:' . $this->config['backgroundColor'] . '"';
}
?>
<link href="userfiles/modules/cookie_notice/scwCookie/output/assets/scwCookie.min.css" rel="stylesheet" type="text/css">
<div class="scw-cookie<?php print ($this->decisionMade ? ' scw-cookie-out' : ''); ?>"<?php print $scw_cookie_style;?>>
    <div class="scw-cookie-panel-toggle scw-cookie-panel-toggle-<?php print $this->config['panelTogglePosition']; ?>"
        onclick="scwCookiePanelToggle()"
    >
        <span class="icon icon-cookie"></span>
    </div>
    <div class="scw-cookie-content">
        <div class="scw-cookie-message">
            <?php $liveChatMessage = $this->config['showLiveChatMessage'] ? ', provide live chat' : ''; ?>
            We use cookies to personalise content<?php print $liveChatMessage; ?> and to analyse our web traffic.
        </div>
        <div class="scw-cookie-decision">
            <div class="scw-cookie-btn" onclick="scwCookieHide('<?php print $this->mod_id;?>')">OK</div>
            <div class="scw-cookie-settings scw-cookie-tooltip-trigger"
                onclick="scwCookieDetails()"
                data-label="Cookie settings"
            >
                <span class="icon icon-settings"></span>
            </div>
            <div class="scw-cookie-policy scw-cookie-tooltip-trigger" data-label="Cookie policy">
                <a href="<?php print $this->config['cookiePolicyURL']; ?>">
                    <span class="icon icon-policy"></span>
                </a>
            </div>
        </div>
        <div class="scw-cookie-details">
            <div class="scw-cookie-details-title">Manage your cookies</div>
            <div class="scw-cookie-toggle">
                <div class="scw-cookie-name">Essential site cookies</div>
                <label class="scw-cookie-switch checked disabled">
                    <input type="checkbox" name="essential" checked="checked" disabled="disabled"/>
                    <div></div>
                </label>
            </div>
            <?php foreach ($this->enabledCookies() as $name => $label) { ?>
                <div class="scw-cookie-toggle">
                    <div class="scw-cookie-name" onclick="scwCookieToggle(this)"><?php print $label; ?></div>
                    <label class="scw-cookie-switch<?php print ($this->isAllowed($name) ? ' checked' : ''); ?>">
                        <input type="checkbox"
                        name="<?php print $name; ?>"
                        <?php print ($this->isAllowed($name) ? 'checked="checked"' : ''); ?>
                        />
                        <div></div>
                    </label>
                </div>

                <?php } ?>
            </div>
        </div>
    </div>
    <script src="userfiles/modules/cookie_notice/scwCookie/output/assets/js-cookie.js" type="text/javascript"></script>
    <script src="userfiles/modules/cookie_notice/scwCookie/output/assets/scwCookie.min.js" type="text/javascript"></script>
