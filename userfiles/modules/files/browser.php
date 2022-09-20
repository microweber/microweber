<?php
must_have_access();
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
$params_get_files['hide_files'] = ['index.html','index.php'];


$data = app()->make(\MicroweberPackages\Utils\System\Files::class)->get($params_get_files);




$path_nav = explode(DS, $path);


$tn_size = 150;
$image_item_class = 'image-item';
$browser_list_holder_class = 'mw-browser-list';

$viewsize_param = '';



if (!isset($params['viewsize'])) {
    $params['viewsize'] = 'big';
}
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
    mw.lib.require('xss');
</script>
    <script>

    var back = function () {
        var curr = decodeURIComponent(mw.url.windowHashParam('path') || '')
            .replace(/\\|\//g, '•')
            .split('•')
        .filter(function (item){ return !!item; });
        if(!curr.length) {
            if (self === top ) {
                location.href = '<?php print admin_url(); ?>view:modules';
            }
            return;
        }
        curr.pop();

        mw.url.windowHashParam('path', encodeURIComponent(curr.join('\\')))
    }

    var select = function (node, p) {
        mw.element('.file-selected').removeClass('file-selected');
        mw.element(node).addClass('file-selected');
        p = filterXSS(p);
        mw.url.windowHashParam('select-file', p);
    }


</script>

<style>

    .file-selected{
        position: relative;

    }

    .file-selected:after{
        display: flex;
        align-items: center;
        justify-content: center;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 135px;
        content: "\F012C";
        font-family: 'Material Design Icons';
        color: #3dc47e;
        background-color: rgba(0, 0, 0, .33);
        font-weight: bold;
        font-size: 40px;
    }
    .mw-file-browser-header .dropdown, .mw-browser-uploader-path{
        display: inline-block;
        margin-left: 7px;
    }
    .mw-file-browser-header{
        display: flex;
        justify-content: space-between;
        padding: 5px 0 15px;
    }

    .mw-file-item-delete:after{
        content: "\F0156";
        font-family: "Material Design Icons";
    }
    .mw-file-item-delete{
        position: absolute;
        display: block;
        top: 3px;
        right: 3px;
        background-color: rgba(255,255,255,.7);
        width: 20px;
        height: 20px;
        line-height: 19px;
        text-align: center;
        cursor: pointer;
        font-weight: bold;
        font-size: 16px;
        border-radius: 1px;
    }
    .mw-browser-list li:hover .mw-file-item-delete{
        background-color: rgba(255,255,255,1);
    }
    .as-image {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        position: relative;
        height: 60px;
        width: 100%;
        background-repeat: no-repeat;
        background-position: center;
        background-size: cover;

    }
    .mw-browser-list-name,
    .as-image {
        font-size: 12px;
    }
    .mw-browser-list li.mw-browser-list-folder:hover .as-image {
        background-color: #f2f3f5;
    }
    .mw-browser-list-folder .as-image .mdi{
        font-size: 48px;
        line-height: 48px;
    }

    .as-image:after{
        background-color: whitesmoke;
        position: absolute;
        top:0;
        left: 0;
        content: '';
        width: 100%;
        height: 100%;
        opacity: 0;
        transition: .3s;
    }
    .image-item-not-ready:after{
        opacity: 1;
    }
    .as-image .mw-spinner{
        position: absolute;
        top:50%;
        left: 50%;
        transform:  translate(-50%, -50%);
    }

    #files_ctrl_holder :disabled{
        pointer-events: none;
        filter: grayscale(50);
        opacity: .33;
        transition: .2s;
    }



</style>

<div class="card-body pt-3 pb-0 px-0" >
    <div class="row">

        <div class="col-md-12">
            <div class="card style-1 mb-1">
                <div class="card-body pt-3">
                    <div class="row">
                        <div class="col-12 justify-content-between">




                            <div class="mw-file-browser">
                                <div class="mw-file-browser-header">
                                    <div>

                                        <a href="javascript:;" onclick="back()"
                                           class="btn btn-outline-primary icon-left btn-sm">
                                            <i class="mdi mdi-keyboard-backspace" style="margin-top: -4px;"></i>
                                            <?php _e("Back"); ?>
                                        </a>

                                        <div class="mw-browser-uploader-path">
                                            <ol class="breadcrumb bg-transparent py-0 m-0">
                                                <li class="breadcrumb-item"><a href="#path="><?php _e('Main') ?></a></li>

                                                <a href="#path=" style="color: #212121;"><span class="<?php print $config['module_class']; ?> path-item"></span></a>/
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
                                                             <li class="breadcrumb-item"><a href="#path=<?php print urlencode($path_nav_pop) ?>"><span class="<?php print $config['module_class']; ?> path-item"><?php print ($item) ?></span></a></li>

                                                        <?php endif; endforeach; ?>
                                                <?php endif; ?>

                                            </ol>
                                        </div>

                                    </div>
                                    <div>

                                        <div class="dropdown">
                                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButtonTnSize" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Thumbnail size
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButtonTnSize">
                                                <a class="dropdown-item" onclick="mw.url.windowHashParam('viewsize', 'small');">Small</a>
                                                <a class="dropdown-item" onclick="mw.url.windowHashParam('viewsize', 'big');">Big</a>
                                            </div>
                                        </div>

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

                                        <select name="file_browser_sort_by" data-style="btn-sm" id="file_browser_sort_by" class="selectpicker file_browser_sort_by">
                                            <option value="" onmousedown="mw.url.windowHashParam('sort_by', '');"><?php _e("Sort by"); ?></option>
                                            <option value="basename ASC"  <?php if ($sortby_param == 'basename' and $sortby_param_ord == 'ASC'): ?> selected <?php endif; ?> ><?php _e("File name"); ?> &#8593;</option>
                                            <option value="basename DESC"  <?php if ($sortby_param == 'basename' and $sortby_param_ord == 'DESC'): ?> selected <?php endif; ?> ><?php _e("File name"); ?> &#8595;</option>
                                            <option value="filemtime ASC"  <?php if ($sortby_param == 'filemtime' and $sortby_param_ord == 'ASC'): ?> selected <?php endif; ?> ><?php _e("Modified time"); ?> &#8593;</option>
                                            <option value="filemtime DESC"  <?php if ($sortby_param == 'filemtime' and $sortby_param_ord == 'DESC'): ?> selected <?php endif; ?> ><?php _e("Modified time"); ?> &#8595;</option>
                                            <option value="filesize ASC"  <?php if ($sortby_param == 'filesize' and $sortby_param_ord == 'ASC'): ?> selected <?php endif; ?> ><?php _e("File size"); ?> &#8593;</option>
                                            <option value="filesize DESC"  <?php if ($sortby_param == 'filesize' and $sortby_param_ord == 'DESC'): ?> selected <?php endif; ?> ><?php _e("File size"); ?> &#8595;</option>
                                        </select>
                                    </div>






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











     </div>
                                <?php // } ?>

                                <div id="mw-browser-list-holder">
                                    <?php if (isset($data['dirs'])): ?>
                                        <ul class="<?php print $browser_list_holder_class ?>">
                                            <?php foreach ($data['dirs'] as $item): ?>
                                                <?php $dir_link = $item; ?>
                                                <?php $dir_link = str_replace($path_restirct, '', $dir_link); ?>
                                                <li class="mw-browser-list-folder mw-browser-list-create">

                                                    <a title="<?php print basename($item); ?>"
                                                       href="#path=<?php print urlencode($dir_link); ?>">
                                                        <span class="as-image">
                                                            <span class="mdi mdi-folder"></span>
                                                            <span><?php print basename($item); ?></span>
                                                        </span>
                                                    </a>
                                                    <span class="mw-file-item-delete"
                                                          onclick="deleteItem('<?php print urlencode($item); ?>', '<?php print basename($item); ?>');"></span>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php else: ?>
                                    <?php endif; ?>
                                    <div class="mw-ui-box-hr"></div>
                                    <?php if (isset($data['files'])): ?>
                                        <ul class="<?php print $browser_list_holder_class ?>">
                                            <?php foreach ($data['files'] as $item): ?>
                                                <?php $rand = 'item'.crc32($item); ?>
                                                <li class="mw-browser-list-item-rand-<?php print $rand; ?>">

                                                <div class="mw-browser-list-item">
                                                    <a title="<?php print basename($item); ?>"
                                                       class="mw-browser-list-file mw-browser-list-<?php print substr(strrchr($item, '.'), 1); ?>"
                                                       href="<?php print mw()->url_manager->link_to_file($item) ?>"
                                                       onclick="select(this, '<?php print mw()->url_manager->link_to_file($item) ?>'); return false;">
                                                        <?php $ext = strtolower(get_file_extension($item)); ?>
                                                        <?php if ($ext == 'jpg' or $ext == 'png' or $ext == 'gif' or $ext == 'jpeg' or $ext == 'bmp' or $ext == 'webp' or $ext == 'svg'): ?>
                                                            <span data-src="<?php print thumbnail(mw()->url_manager->link_to_file($item), $tn_size, $tn_size, true); ?>"
                                                                  class="<?php print basename($item) ?> as-image image-item-not-ready"></span>
                                                        <?php else: ?>
                                                            <div class="mw-fileico mw-fileico-<?php print $ext; ?>">
                                                                <span><?php print $ext; ?></span></div>
                                                        <?php endif; ?>
                                                        <span class="mw-browser-list-name"><?php print basename($item) ?></span>
                                                    </a>

                                                    <div class="mw-file-item-check">
                                                        <label class="mw-ui-check pull-left">
                                                            <input type="checkbox" oninput="gchecked()" name="fileitem" id="v<?php print $rand; ?>"
                                                                   value="<?php print $item; ?>"/>
                                                            <span></span>
                                                        </label>
                                                        <span class="mw-file-item-delete"
                                                              onclick="deleteItem(document.getElementById('v<?php print $rand; ?>').value,false,false,'.mw-browser-list-item-rand-<?php print $rand; ?>');"></span>
                                                    </div>
                                                    </div>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>

                                        <script>
                                            rendImages = window.rendImages || function () {
                                                var all = document.querySelectorAll('.image-item-not-ready'),
                                                    l = all.length,
                                                    i = 0;
                                                for (; i < l; i++) {
                                                    var item = all[i];
                                                    var datasrc = item.getAttribute("data-src");
                                                    if (mw.tools.inview(item) && datasrc !== null) {
                                                        item.removeAttribute("data-src")

                                                        if(self === top){
                                                            mw.spinner(({element: item, size: 30})).show();
                                                            (function (node){
                                                                mw.image.preload(datasrc, function () {
                                                                    mw.spinner(({element: node})).hide();
                                                                })
                                                            })(item)
                                                        }


                                                        if (item.nodeName === 'IMG') {
                                                            $(item).attr('src', datasrc).removeClass('image-item-not-ready');
                                                        } else {
                                                            $(item).css('backgroundImage', 'url(' + datasrc + ')').removeClass('image-item-not-ready');
                                                        }


                                                    }
                                                }
                                            };
                                            var browserList = mw.$('#mw-browser-list-holder')
                                            // $(browserList).on('scroll', function () {
                                            //     rendImages();
                                            // });
                                            $(window).on('load', function () {
                                                if (window.thismodal) {
                                                    $(thismodal).on('dialogCenter', function () {
                                                        setTimeout(function () {
                                                            rendImages();
                                                        }, 333);
                                                    })
                                                }

                                            });
                                            $(window).on('load ajaxStop resize scroll', function () {
                                                setTimeout(function () {
                                                    rendImages();
                                                }, 333);

                                                // browserList.height($(top).height() - browserList.offset().top - 220)
                                            });

                                        </script>
                                    <?php endif; ?>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

