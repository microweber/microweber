<?php
only_admin_access();
/**
 * Simple file browser
 *
 * Gets all files from dir and output them in a template
 *
 * @package        modules
 * @subpackage    files
 * @category    modules
 */
?>
<?php
// Explore the files via a web interface.
$script = $config['url']; // the name of this script
//$path = media_base_path(); // the path the script should access
$path = media_uploads_path(); // the path the script should access
$path_restirct = media_uploads_path(); // the path the script should access




//$environment = App::environment();


if (isset($params['path']) and trim($params['path']) != '' and trim($params['path']) != 'false') {

    $path = $params['path']; // the path the script should access

}


$path = str_replace('./', '', $path);
$path = str_replace('..', '', $path);
$path = urldecode($path);


$path = str_replace($path_restirct, '', $path);

//$data = rglob($path);
$params_get_files = array();
$params_get_files['directory'] = $path_restirct . $path;
$params_get_files['restrict_path'] = $path_restirct ;

if (isset($params['search'])) {
    $params_get_files['search'] = $params['search'];
}

if (isset($params['sort_by'])) {
    $params_get_files['sort_by'] = $params['sort_by'];
}
if (isset($params['sort_order'])) {
    $params_get_files['sort_order'] = $params['sort_order'];
}

if (isset($params_get_files['directory']) and !is_dir($params_get_files['directory'])) {
    notif(_e('You are trying to open invalid folder', true), 'error');
} else if (isset($params_get_files['directory']) and is_dir($params_get_files['directory']) and !is_writable($params_get_files['directory'])) {
    notif(_e('Your folder is not writable. You wont be able to upload in it.', true), 'warning');
}
//  $params['keyword']


$data = mw('Microweber\Utils\Files')->get($params_get_files);

$path_nav = explode(DS, $path);

?>
<script>

    PreviousFolder = [];


</script>


<div class="mw-ui-box mw-file-browser">
    <div class="mw-ui-box-header"><a href="javascript:;" onclick="mw.url.windowHashParam('path', PreviousFolder);"
                                     class="mw-ui-btn mw-ui-btn-small pull-right mw-ui-btn-invert">
            <?php _e("Back"); ?>
        </a> <span class="mw-browser-uploader-path">
    <?php if (is_array($path_nav)): ?>
        <?php

        $path_nav_pop = false;
        foreach ($path_nav as $item): ?>
            <?php

            if ($path_nav_pop == false) {
                $path_nav_pop = $item;
            } else {

                $path_nav_pop = $path_nav_pop . DS . $item;

            }
            if (strlen($item) > 0):
                ?>
                <script>PreviousFolder.push('<?php print urlencode($path_nav_pop) ?>');</script>
                <a href="#path=<?php print urlencode($path_nav_pop) ?>" style="color: #212121;"><span
                            class="<?php print $config['module_class']; ?> path-item"><?php print ($item) ?></span></a>&raquo;
            <?php endif; endforeach; ?>
    <?php endif; ?>
    </span></div>
    <?php // } ?>
    <script>
        PreviousFolder.length > 1 ? PreviousFolder.pop() : '';
        PreviousFolder = PreviousFolder.length > 1 ? PreviousFolder[PreviousFolder.length - 1] : PreviousFolder[0];
    </script>
    <div class="mw-ui-box-content" id="mw-browser-list-holder">
        <?php if (isset($data['dirs'])): ?>
            <ul class="mw-browser-list">
                <?php foreach ($data['dirs'] as $item): ?>
                    <?php $dir_link = str_replace($path_restirct, '', $item); ?>
                    <li>
                        <a title="<?php print basename($item) . '&#10;' . dirname($item); ?>"
                           href="#path=<?php print urlencode($dir_link); ?>">
                            <span class="mw-icon-category"></span>
                            <span><?php print basename($item); ?></span>
                        </a>
                        <span class="mw-icon-close"
                              onclick="deleteItem('<?php print urlencode($dir_link); ?>', '<?php print basename($item); ?>');"></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
        <?php endif; ?>
        <div class="mw-ui-box-hr"></div>
        <?php if (isset($data['files'])): ?>
            <ul class="mw-browser-list">
                <?php foreach ($data['files'] as $item): ?>
                    <li>
                        <a title="<?php print basename($item) . '&#10;' . dirname($item); ?>"
                           class="mw-browser-list-file mw-browser-list-<?php print substr(strrchr($item, '.'), 1); ?>"
                           href="<?php print mw()->url_manager->link_to_file($item) ?>"
                           onclick="mw.url.windowHashParam('select-file', '<?php print mw()->url_manager->link_to_file($item) ?>'); return false;">
                            <?php $ext = strtolower(get_file_extension($item)); ?>
                            <?php if ($ext == 'jpg' or $ext == 'png' or $ext == 'gif' or $ext == 'jpeg' or $ext == 'bmp'): ?>
                                <img data-src="<?php print thumbnail(mw()->url_manager->link_to_file($item), 48, 48); ?>"
                                     class="image-item image-item-not-ready"/>
                            <?php else: ?>
                                <span class="mw-fileico mw-fileico-<?php print $ext; ?>"><?php print $ext; ?></span>
                            <?php endif; ?>
                            <span><?php print basename($item) ?></span>
                        </a>
                        <?php $rand = md5($item); ?>
                        <div class="mw-file-item-check">
                            <label class="mw-ui-check pull-left">
                                <input type="checkbox" onchange="gchecked()" name="fileitem" id="v<?php print $rand; ?>"
                                       value="<?php print $item; ?>"/>
                                <span></span>
                            </label>
                            <span class="mw-icon-close"
                                  onclick="deleteItem(mwd.getElementById('v<?php print $rand; ?>').value);"></span>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
            <script>
                rendImages = window.rendImages || function () {
                        var all = mwd.querySelectorAll('.image-item-not-ready'),
                            l = all.length,
                            i = 0;
                        for (; i < l; i++) {
                            var item = all[i];
                            var datasrc = item.getAttribute("data-src");
                            if (mw.tools.inview(item) && datasrc !== null) {
                                $(item).attr('src', datasrc).removeClass('image-item-not-ready');
                            }
                        }
                    }
                $(window).bind('load scroll ajaxStop', function () {
                    rendImages();
                });

            </script>
        <?php endif; ?>
    </div>
</div>
