<?php
$scw_cookie_style = '';
if (!empty($this->config['backgroundColor'])) {
    $scw_cookie_style .= 'background:' . $this->config['backgroundColor'] . ';';
}
if (!empty($this->config['textColor'])) {
    $scw_cookie_style .= 'color:' . $this->config['textColor'] . ';';
}
?>

<link href="<?php print modules_url(); ?>/cookie_notice/scwCookie/output/assets/scwCookie.css" rel="stylesheet" type="text/css"/>

<style>
    .scw-cookie-panel-toggle::before {
        content: '<?php _lang('Turn cookies on / off', 'modules/cookie_notice'); ?>';
    }
    .scw-cookie-panel-toggle:hover::before {
        width:200px !important;
    }
    .scw-btn .mdi {
        font-size:24px;
        color:#fff;
    }
    .scw-btn .mdi-close-thick {
        color: #d51818;
    }
    .scw-cookie-panel-toggle .mdi {
        font-size:24px;
        color:#fff;
    }
</style>

<div class="scw-cookie<?php print ($this->decisionMade ? ' scw-cookie-out' : ''); ?>" style="<?php print $scw_cookie_style; ?>">
    <div class="scw-cookie-panel-toggle scw-cookie-panel-toggle-<?php print $this->config['panelTogglePosition']; ?>"
         onclick="scwCookiePanelToggle()"
    >
        <span class="mdi mdi-cookie"></span>
    </div>
    <div class="scw-cookie-content">
        <div class="scw-cookie-message">
            <?php $liveChatMessage = $this->config['showLiveChatMessage'] ? ', provide live chat' : ''; ?>
            <?php _lang('We use cookies to personalise content ' . $liveChatMessage.' and to analyse our web traffic.', 'modules/cookie_notice'); ?>
        </div>
        <div class="scw-cookie-decision">
            <div class="scw-cookie-btn" onclick="scwCookieHide('<?php print $this->mod_id; ?>')">
                <?php _lang('Accept all cookies', 'modules/cookie_notice'); ?>
            </div>
            <div class="scw-btn scw-cookie-settings scw-cookie-tooltip-trigger"
                 onclick="scwCookieDetails()"
                 data-label="<?php _lang('Cookie settings', 'modules/cookie_notice'); ?>"
            >
                <span class="mdi mdi-cog"></span>
            </div>
            <div class="scw-btn scw-cookie-policy scw-cookie-tooltip-trigger" data-label="<?php _lang('Cookie policy', 'modules/cookie_notice'); ?>">
                <a href="<?php print $this->config['cookiePolicyURL']; ?>" target="_blank">
                    <span class="mdi mdi-file-document"></span>
                </a>
            </div>
            <div class="scw-btn scw-cookie-policy">
                <a href="#" onclick="scwCookiePanelToggle()">
                    <span class="mdi mdi-close-thick"></span>
                </a>
            </div>
        </div>
        <div class="scw-cookie-details">
            <div class="scw-cookie-details-title"><?php _lang('Manage your cookies', 'modules/cookie_notice'); ?></div>
            <div class="scw-cookie-toggle">
                <div class="scw-cookie-name"><?php _lang('Essential site cookies', 'modules/cookie_notice'); ?></div>
                <label class="scw-cookie-switch checked disabled">
                    <input type="checkbox" name="essential" checked="checked" disabled="disabled"/>
                    <div></div>
                </label>
            </div>
            <?php if ($this->enabledCookies()): ?>
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
            <?php endif; ?>
        </div>
    </div>
</div>
<script src="<?php print modules_url(); ?>/cookie_notice/scwCookie/output/assets/js-cookie.js" type="text/javascript"></script>
<script src="<?php print modules_url(); ?>/cookie_notice/scwCookie/output/assets/scwCookie.js" type="text/javascript"></script>
