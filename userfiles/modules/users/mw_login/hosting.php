<?php
$data = mw()->user_manager->session_get('mw_hosting_data');

?>
<?php if ($data and isset($data['name'])): ?>
    <div class="">
        <h2>
            Hosting info
        </h2>
        <div class="mw-ui-row">
            <div class="mw-ui-col"><?php print $data['name'] ?> (registered <?php print mw()->format->ago($data['regdate']) ?>)
            <a target="_blank" href="https://members.microweber.com/clientarea.php?action=productdetails&id=<?php print $data['id'] ?> ">see details</a>

            </div>
        </div>
    </div>
<?php endif; ?>