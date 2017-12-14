<div id="settings-holder">
    <br/><br/>
    <h5 style="font-weight: bold; display: block;">Skin Settings</h5>

    <?php include 'settings_padding.php'; ?>
    <?php include 'settings_overlay.php'; ?>

    <?php
    $youtube = get_option('youtube', $params['id']);
    if ($youtube == '') {
        $youtube = '';
    }
    ?>

    <div class="youtube-code" style="margin-top:15px;">
        <label class="mw-ui-label">YouTube Video Code</label>
        <input type="text" name="youtube" placeholder="JyNIJ8U03I0" class="mw-ui-field mw_option_field"/>
    </div>
</div>