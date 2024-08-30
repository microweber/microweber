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
    <div class="row p-3 align-items-center justify-content-center">
        <div class="col-md-6 col-12 text-center">
            <?php $liveChatMessage = $this->config['showLiveChatMessage'] ? ', provide live chat' : ''; ?>
            <?php _lang('We use cookies to personalise content ' . $liveChatMessage.' and to analyse our web traffic.', 'modules/cookie_notice'); ?>
        </div>
        <div class="col-md-5 col-12 pt-md-0 pt-3 d-flex justify-content-center justify-content-md-end">
            <div class="scw-cookie-btn" onclick="scwCookieHide('<?php print $this->mod_id; ?>')">
                <?php _lang('Accept all cookies', 'modules/cookie_notice'); ?>
            </div>
            <div>
                <div class="scw-btn scw-cookie-settings scw-cookie-tooltip-trigger"
                     onclick="scwCookieDetails()"
                     data-label="<?php _lang('Cookie settings', 'modules/cookie_notice'); ?>"
                >
                    <svg fill="#fff" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="m370-80-16-128q-13-5-24.5-12T307-235l-119 50L78-375l103-78q-1-7-1-13.5v-27q0-6.5 1-13.5L78-585l110-190 119 50q11-8 23-15t24-12l16-128h220l16 128q13 5 24.5 12t22.5 15l119-50 110 190-103 78q1 7 1 13.5v27q0 6.5-2 13.5l103 78-110 190-118-50q-11 8-23 15t-24 12L590-80H370Zm70-80h79l14-106q31-8 57.5-23.5T639-327l99 41 39-68-86-65q5-14 7-29.5t2-31.5q0-16-2-31.5t-7-29.5l86-65-39-68-99 42q-22-23-48.5-38.5T533-694l-13-106h-79l-14 106q-31 8-57.5 23.5T321-633l-99-41-39 68 86 64q-5 15-7 30t-2 32q0 16 2 31t7 30l-86 65 39 68 99-42q22 23 48.5 38.5T427-266l13 106Zm42-180q58 0 99-41t41-99q0-58-41-99t-99-41q-59 0-99.5 41T342-480q0 58 40.5 99t99.5 41Zm-2-140Z"/></svg>
                </div>
                <div class="scw-btn scw-cookie-policy scw-cookie-tooltip-trigger" data-label="<?php _lang('Cookie policy', 'modules/cookie_notice'); ?>">
                    <a href="<?php print $this->config['cookiePolicyURL']; ?>" target="_blank">
                        <svg fill="#fff" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M320-240h320v-80H320v80Zm0-160h320v-80H320v80ZM240-80q-33 0-56.5-23.5T160-160v-640q0-33 23.5-56.5T240-880h320l240 240v480q0 33-23.5 56.5T720-80H240Zm280-520v-200H240v640h480v-440H520ZM240-800v200-200 640-640Z"/></svg>
                    </a>
                </div>
                <div class="scw-btn scw-cookie-policy">
                    <a href="#" onclick="scwCookiePanelToggle()" data-bs-toggle="tooltip" data-bs-placement="top" title="<?php _lang('Close', 'modules/cookie_notice'); ?>">
                        <svg fill="#DC3545" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/></svg>
                    </a>
                </div>
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
