<?php must_have_access(); ?>

<?php
$currentVersion = MW_VERSION;
$currentPHPVersion = PHP_VERSION;
$latestVersion = mw_standalone_updater_get_latest_version();
$latestVersionComposerJson = mw_standalone_updater_get_latest_composer_json();

if ($latestVersionComposerJson and isset($latestVersionComposerJson['require']['php']) and $latestVersionComposerJson['require']['php']) {
    $latestPHPVersionNeeded = $latestVersionComposerJson['require']['php'];
    $latestPHPVersionNeeded = str_replace(['>=', '=', '<=', '<','>',], '', $latestPHPVersionNeeded);
} else {
    $latestPHPVersionNeeded = false;
}


$canIUpdate = true;
$canIUpdateMessage = [];
$projectMainDir = dirname(dirname(dirname(__DIR__)));

if (is_dir($projectMainDir . DS . '.git')) {
    $canIUpdate = true;
    $canIUpdateMessages[] = 'The git repository is recognized on your server.';
}



/*
if (app()->environment() == 'production') {
    $canIUpdate = false;
    $canIUpdateMessages[] = 'The app is on production environment.';
}
*/

if (!class_exists('ZipArchive') ) {
    $canIUpdate = false;
    $canIUpdateMessages[] = 'ZipArchive PHP extension is required auto updater.';
}
$curl_errors = mw_standalone_updater_has_curl_errors();
if ($curl_errors) {
    $canIUpdate = false;
    $canIUpdateMessages[] = $curl_errors;
}
if (!function_exists('curl_init') ) {
    $canIUpdate = false;
    $canIUpdateMessages[] = 'The Curl PHP extension is required auto updater.';
}
if (!function_exists('json_decode') ) {
    $canIUpdate = false;
    $canIUpdateMessages[] = 'The JSON PHP extension is required auto updater.';
}

if (!is_writable($projectMainDir . DS . 'src')) {
    $canIUpdate = false;
    $canIUpdateMessages[] = 'The src folder must be writable.';
}

if (!is_writable($projectMainDir . DS . 'userfiles')) {
    $canIUpdate = false;
    $canIUpdateMessages[] = 'The userfiles folder must be writable.';
}

if (!is_writable($projectMainDir . DS . 'storage')) {
    $canIUpdate = false;
    $canIUpdateMessages[] = 'The storage folder must be writable.';
}

if (is_link($projectMainDir . DS . 'vendor')) {
    $canIUpdate = false;
    $canIUpdateMessages[] = 'The vendor folder must not be a symlink.';
}


if (!is_writable($projectMainDir . DS . 'vendor')) {
    $canIUpdate = false;
    $canIUpdateMessages[] = 'The vendor folder must be writable.';
}

if (function_exists('disk_free_space')) {
    $bytes = disk_free_space($projectMainDir);
    $si_prefix = array('B', 'KB', 'MB', 'GB', 'TB', 'EB', 'ZB', 'YB');
    $base = 1024;
    $class = min((int)log($bytes, $base), count($si_prefix) - 1);

    if (($bytes / pow($base, $class)) < 1) {
        $canIUpdate = false;
        $canIUpdateMessages[] = 'The minimum required free disk space is 1GB, you have ' . sprintf('%1.2f', $bytes / pow($base, $class)) . ' ' . $si_prefix[$class] . ' on your server.';
    }
}

$isUpToDate = false;
if ($latestVersion) {
    if (\Composer\Semver\Comparator::lessThanOrEqualTo($latestVersion, $currentVersion)) {
        $isUpToDate = true;
    }
}


if ($latestPHPVersionNeeded) {
    if (\Composer\Semver\Comparator::greaterThan($latestPHPVersionNeeded, $currentPHPVersion)) {
        $canIUpdate = false;
        $canIUpdateMessages[] = 'You need to update to PHP version ' . $latestPHPVersionNeeded . ', you have ' . $currentPHPVersion . '.';
    }
}
?>

<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>

<module type="standalone-updater/delete_temp_standalone"/>

<style>
    .mw-standalone-icons {
        font-size: 50px;
    }
</style>


<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-xl-3 mb-xl-0 mb-3">
                <?php $module_info = module_info($params['module']); ?>

                <!--                    <img height="100px" width="100px" src="--><?php //echo $module_info['icon']; ?><!--" class="module-icon-svg-fill "/>-->

                <h5 class="font-weight-bold settings-title-inside"><?php _e($module_info['name']); ?></h5>

                <small class="text-muted"><?php _e('Easy update your website builder and CMS with Standalone Updater'); ?></small>
            </div>
            <div class="col-xl-9">
                <div class="card bg-azure-lt ">
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-12 row text-center justify-content-center">
                                <div class="text-center ">

                                    <?php if ($canIUpdate) { ?>
                                        <?php if ($isUpToDate) { ?>

                                            <div class="mb-4 mt-2 module-standalone-icons-check">
                                            <h1 class="text-success">
                                                <svg fill="currentColor"xmlns="http://www.w3.org/2000/svg" height="48" viewBox="0 96 960 960" width="48"><path d="m421 758 283-283-46-45-237 237-120-120-45 45 165 166Zm59 218q-82 0-155-31.5t-127.5-86Q143 804 111.5 731T80 576q0-83 31.5-156t86-127Q252 239 325 207.5T480 176q83 0 156 31.5T763 293q54 54 85.5 127T880 576q0 82-31.5 155T763 858.5q-54 54.5-127 86T480 976Zm0-60q142 0 241-99.5T820 576q0-142-99-241t-241-99q-141 0-240.5 99T140 576q0 141 99.5 240.5T480 916Zm0-340Z"/></svg>

                                            </h1>
                                            <h4 class="text-success font-weight-bold"> Up to date!</h4>
                                            <?php
                                        } else { ?>

                                            <h1 class="text-danger">
                                                <svg fill="currentColor"xmlns="http://www.w3.org/2000/svg" height="48" viewBox="0 96 960 960" width="48"><path d="M833 1015 718 900q-50 36-110 56t-128 20q-85 0-158-30.5T195 861q-54-54-84.5-127T80 576q0-68 20-128t56-110L26 208l43-43 807 807-43 43Zm-353-99q55 0 104-15.5t91-43.5L498 680l-77 78-165-166 45-45 120 120 32-32-254-254q-28 42-43.5 91T140 576q0 145 97.5 242.5T480 916Zm324-102-43-43q28-42 43.5-91T820 576q0-145-97.5-242.5T480 236q-55 0-104 15.5T285 295l-43-43q50-36 110-56t128-20q84 0 157 31t127 85q54 54 85 127t31 157q0 68-20 128t-56 110ZM585 594l-46-45 119-119 46 45-119 119Zm-62-61Zm-86 86Z"/></svg>
                                            </h1>
                                            <h5 class="text-danger font-weight-bold"> You're not up to date!</h5><br/>
                                        <?php } ?>

                                        </div>

                                        <p class="mb-0">Your current version is <span class="font-weight-bold"><?php echo $currentVersion; ?></span> and the latest version is <span class="font-weight-bold"><?php echo $latestVersion; ?></span></p>&nbsp;


                                        <form method="post" action="<?php echo route('api.standalone-updater.update-now'); ?>">
                                            <div class="d-flex justify-content-center">
                                                <div class="form-group mb-0 mr-4">
                                                    <div class="input-group align-items-center h-100">
                                                        <!--                                    <label> Version:</label>&nbsp;-->
                                                        <select name="version" class="form-select js-standalone-updater-select-version">
                                                            <option value="latest">Latest stable</option>
                                                            <option value="dev">Latest Developer</option>
                                                            <option value="dev_unstable">Latest Developer (unstable)</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <script type="text/javascript">
                                                    $(document).ready(function () {
                                                        $('.js-standalone-updater-select-version').change(function () {
                                                            if ($(this).val() == 'dev' || $(this).val() == 'dev_unstable') {
                                                                $('.js-standalone-updater-update-button').html('Update');
                                                            } else {
                                                                $('.js-standalone-updater-update-button').html('Reinstall');
                                                            }
                                                        });
                                                    });
                                                </script>

                                                <?php if ($isUpToDate) { ?>
                                                    <button method="submit" class="btn btn-success js-standalone-updater-update-button h-100 ms-3">
                                                        Reinstall
                                                    </button>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <button method="submit" class="btn btn-success js-standalone-updater-update-button h-100 ms-3">
                                                        Update now!
                                                    </button>
                                                    <?php
                                                }
                                                ?>
                                            </div>

                                            <script type="text/javascript">
                                                $(document).ready(function () {
                                                    mw.options.form('.js-standalone-updater-settings', function () {
                                                        mw.clear_cache();
                                                        mw.notification.success("<?php _ejs("All changes are saved"); ?>.");
                                                    });
                                                });
                                            </script>


                                            <div class="mb-0 mt-4 mr-4 js-standalone-updater-settings">
                                                <a href="#"
                                                   onClick="$('.js-advanced-settings').toggle();"><?php echo _e('Advanced settings'); ?></a>
                                                <div class="js-advanced-settings " style="display:none">

                                                    <div class="d-flex flex-wrap justify-content-center align-items-center text-start">
                                                        <div class="col-xl-8 col-12 my-xl-0 my-3 p-2">
                                                            <div class="card mx-2 h-100">
                                                                <div class="card-body">
                                                                    <div class="form-group mb-0 mr-4">
                                                                        <div class="">
                                                                            <label class="mb-3">  <?php echo _e('Max receive speed download (per second)'); ?></label>
                                                                            <select name="max_receive_speed_download"
                                                                                    class="ml-4 form-select mw_option_field"
                                                                                    option-group="standalone_updater">
                                                                                <option
                                                                                    value="0" <?php if (get_option('max_receive_speed_download', 'standalone_updater') == '0'): ?> selected="selected" <?php endif; ?>>
                                                                                    Unlimited
                                                                                </option>
                                                                                <option
                                                                                    value="5" <?php if (get_option('max_receive_speed_download', 'standalone_updater') == '5'): ?> selected="selected" <?php endif; ?>>
                                                                                    5MB/s
                                                                                </option>
                                                                                <option
                                                                                    value="2" <?php if (get_option('max_receive_speed_download', 'standalone_updater') == '2'): ?> selected="selected" <?php endif; ?>>
                                                                                    2MB/s
                                                                                </option>
                                                                                <option
                                                                                    value="1" <?php if (get_option('max_receive_speed_download', 'standalone_updater') == '1'): ?> selected="selected" <?php endif; ?>>
                                                                                    1MB/s
                                                                                </option>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-xl-8 col-12 my-xl-0 my-3 p-2">
                                                            <div class="card mx-2 h-100">
                                                                <div class="card-body">
                                                                    <div class="form-group mb-0 mr-4 mt-2">
                                                                        <div class="">
                                                                            <label class="mb-3">  <?php echo _e('Download method'); ?></label>
                                                                            <select name="download_method" class="ml-4 form-select mw_option_field"
                                                                                    option-group="standalone_updater">
                                                                                <option
                                                                                    value="curl" <?php if (get_option('download_method', 'standalone_updater') == 'curl'): ?> selected="selected" <?php endif; ?>>
                                                                                    CURL
                                                                                </option>
                                                                                <option
                                                                                    value="file_get_contents" <?php if (get_option('download_method', 'standalone_updater') == 'file_get_contents'): ?> selected="selected" <?php endif; ?>>
                                                                                    File Get Contents
                                                                                </option>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </form>
                                        <?php
                                    } else {
                                        ?>

                                        <h1 class="text-danger"><i class="mw-standalone-icons mdi mdi-close-circle-outline"></i></h1>
                                        <h5 class="text-danger font-weight-bold">The standalone update can't be run on this server
                                            because:</h5>
                                        <ol>
                                            <?php
                                            foreach ($canIUpdateMessages as $message):
                                                ?>
                                                <li style="font-weight: bold"><?php echo $message; ?></li>
                                            <?php
                                            endforeach;
                                            ?>
                                        </ol>
                                        <br/>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
