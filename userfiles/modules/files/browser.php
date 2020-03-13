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
$params_get_files['restrict_path'] = $path_restirct;

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

if (isset($params['extensions']) and $params['extensions']) {
    $params_get_files['extensions'] = $params['extensions'];
}

$data = mw('Microweber\Utils\Files')->get($params_get_files);

$path_nav = explode(DS, $path);


$tn_size = 150;
$image_item_class = 'image-item';
$browser_list_holder_class = 'mw-browser-list';

$viewsize_param = '';

if (isset($params['viewsize'])) {
    $viewsize_param = $params['viewsize'];

    if ($params['viewsize'] == 'big') {
        $tn_size = 260;
        $image_item_class = 'image-item-big';
        $browser_list_holder_class = 'mw-browser-list mw-browser-list-big';

    }
}

?>
<script>

    PreviousFolder = [];


</script>


<div class="mw-ui-box mw-file-browser">
    <div class="mw-ui-box-header">

<span class="pull-right">

<?php
$sortby_param = '';
$sortby_param_ord = '';


if (isset($params['sort_by'])) {
    $sort_params = explode(' ', $params['sort_by']);
    if (isset($sort_params[1])) {
        $sortby_param = $sort_params[0];
        $sortby_param_ord = $sort_params[1];
    }
}
?>

<script>
    $(document).ready(function () {
        $('#file_browser_sort_by').on('change', function () {
            var val = ($(this).find('option:selected').val());
            if(!val){
                mw.url.windowHashParam('sort_by', '');
            } else {
                mw.url.windowHashParam('sort_by', val);
            }
        });
    });
</script>

    <select name="file_browser_sort_by" id="file_browser_sort_by" class="mw-ui-field">
        <option value="" onmousedown="mw.url.windowHashParam('sort_by', '');"><?php _e("Sort by"); ?></option>
        <option value="basename ASC"  <?php if ($sortby_param == 'basename' and $sortby_param_ord == 'ASC'): ?> selected <?php endif; ?> ><?php _e("File name"); ?> &#8593;</option>
        <option value="basename DESC"  <?php if ($sortby_param == 'basename' and $sortby_param_ord == 'DESC'): ?> selected <?php endif; ?> ><?php _e("File name"); ?> &#8595;</option>
        <option value="filemtime ASC"  <?php if ($sortby_param == 'filemtime' and $sortby_param_ord == 'ASC'): ?> selected <?php endif; ?> ><?php _e("Modified time"); ?> &#8593;</option>
        <option value="filemtime DESC"  <?php if ($sortby_param == 'filemtime' and $sortby_param_ord == 'DESC'): ?> selected <?php endif; ?> ><?php _e("Modified time"); ?> &#8595;</option>
        <option value="filesize ASC"  <?php if ($sortby_param == 'filesize' and $sortby_param_ord == 'ASC'): ?> selected <?php endif; ?> ><?php _e("File size"); ?> &#8593;</option>
        <option value="filesize DESC"  <?php if ($sortby_param == 'filesize' and $sortby_param_ord == 'DESC'): ?> selected <?php endif; ?> ><?php _e("File size"); ?> &#8595;</option>
    </select>




<?php _e("Thumbnail size"); ?>:
        <a href="javascript:;" onclick="mw.url.windowHashParam('viewsize', '');"
           class="mw-ui-btn mw-ui-btn-small  ">
          <?php _e("Small"); ?>
        </a>
        <a href="javascript:;" onclick="mw.url.windowHashParam('viewsize', 'big');"
           class="mw-ui-btn mw-ui-btn-small  "><?php _e("Big"); ?>

        </a>
        <a href="javascript:;" onclick="mw.url.windowHashParam('path', PreviousFolder);"
           class="mw-ui-btn mw-ui-btn-small  mw-ui-btn-invert">



            <?php _e("Back"); ?>
        </a>

      </span>


        <span class="mw-browser-uploader-path">


            <a href="#path=" style="color: #212121;"><span class="<?php print $config['module_class']; ?> path-item"><?php _e('Main') ?></span></a>&raquo;


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
                    <?php $dir_link = $item; ?>
                    <?php $dir_link = str_replace($path_restirct, '', $dir_link); ?>
                    <li>

                        <a title="<?php print basename($item); ?>"
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
            <ul class="<?php print $browser_list_holder_class ?>">
                <?php foreach ($data['files'] as $item): ?>
                    <li>
                        <a title="<?php print basename($item); ?>"
                           class="mw-browser-list-file mw-browser-list-<?php print substr(strrchr($item, '.'), 1); ?>"
                           href="<?php print mw()->url_manager->link_to_file($item) ?>"
                           onclick="mw.url.windowHashParam('select-file', '<?php print mw()->url_manager->link_to_file($item) ?>'); return false;">
                            <?php $ext = strtolower(get_file_extension($item)); ?>
                            <?php if ($ext == 'jpg' or $ext == 'png' or $ext == 'gif' or $ext == 'jpeg' or $ext == 'bmp'): ?>
                                <span data-src="<?php print thumbnail(mw()->url_manager->link_to_file($item), $tn_size, $tn_size, true); ?>"
                                      class="<?php print basename($item) ?> as-image image-item-not-ready"></span>
                            <?php else: ?>
                                <div class="mw-fileico mw-fileico-<?php print $ext; ?>">
                                    <span><?php print $ext; ?></span></div>
                            <?php endif; ?>
                            <span class="mw-browser-list-name"><?php print basename($item) ?></span>
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
            <style>

                .as-image {
                    display: block;
                    height: 60px;
                    width: 100%;
                    background-repeat: no-repeat;
                    background-size: cover;
                    background-position: center;
                }

                .mw-browser-list-big .as-image {
                    height: 150px;
                }
            </style>
            <script>
                rendImages = window.rendImages || function () {
                    var all = mwd.querySelectorAll('.image-item-not-ready'),
                        l = all.length,
                        i = 0;
                    for (; i < l; i++) {
                        var item = all[i];
                        var datasrc = item.getAttribute("data-src");
                        if (mw.tools.inview(item) && datasrc !== null) {
                            if (item.nodeName === 'IMG') {
                                $(item).attr('src', datasrc).removeClass('image-item-not-ready');
                            } else {
                                $(item).css('backgroundImage', 'url(' + datasrc + ')').removeClass('image-item-not-ready');
                            }

                        }
                    }
                };
                var browserList = mw.$('#mw-browser-list-holder')
                $(browserList).on('scroll', function () {
                    rendImages();
                });
                $(window).on('load', function () {
                    if (window.thismodal) {
                        $(thismodal).on('dialogCenter', function () {
                            setTimeout(function () {
                                rendImages();
                            }, 333);
                        })
                    }
                    $()
                });
                $(window).on('load ajaxStop resize', function () {
                    setTimeout(function () {
                        rendImages();
                    }, 333);

                    browserList.height($(top).height() - browserList.offset().top - 220)
                });

            </script>
        <?php endif; ?>
    </div>
</div>
