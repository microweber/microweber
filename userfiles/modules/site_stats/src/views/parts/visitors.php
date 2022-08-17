<?php
if (!$data) {
    return;
}
?>
    <script>
        $(document).ready(function () {
            $('.more-info-show').on('click', function () {
                var showID = $(this).data('id');

                if ($(this).hasClass('active')) {
                    $('#' + showID).slideUp();
                    $(this).removeClass('active');
                } else {
                    $('.more-info-show').removeClass('active');
                    $('.more-info').slideUp();

                    $('#' + showID).slideDown();
                    $(this).addClass('active');
                }
            });

//            $('.visitor-url').on('click', function (event) {
//                event.preventDefault();
//            });
        });
    </script>
 <?php foreach ($data as $item): ?>
    <?php
    $os = strtolower($item['browser_os']);
    if ($os == 'ios' OR $os == 'os x') {
        $os = 'apple';
    } elseif ($os == 'androidos') {
        $os = 'android';
    } elseif ($os == 'windows') {
        $os = 'windows';
    } elseif ($os == 'linux') {
        $os = 'linux';
    } else {
        $os = 'question-circle';
    }

    $browser = strtolower($item['browser_name']);
    if ($browser == 'firefox') {
        $browser = 'firefox';
    } elseif ($browser == 'chrome') {
        $browser = 'chrome';
    } elseif ($browser == 'ie') {
        $browser = 'internet-explorer';
    } elseif ($browser == 'safari') {
        $browser = 'safari';
    } else {
        $browser = 'question-circle';
    }
    ?>

    <div class="item visitor more-info-show" data-id="more-<?php print $item['id']; ?>">
        <div class="top-row">
            <div class="flag"><span
                <?php
                if (strtolower($item['country_code']) == "unknown") {
                    print "class='mdi mdi-earth tip'";
                   }  else {
                     ?> class='flag-icon flag-icon-<?php print strtolower($item['country_code'] . " " . "tip");
                    }
                    ?>'></span></div>
            <div class="visitor-name text-start text-left">

                <?php print $item['user_ip']; ?>
                <a rel="noreferrer noopener" href="https://www.ip2location.com/demo/<?php print $item['user_ip']; ?>" class="text-muted btn btn-link btn-rounded btn-icon btn-sm"  target="_blank"><i class="mdi  mdi-open-in-new"></i></a>



            </div>
            <div class="timestamp tip" data-tip="<?php print $item['updated_at']; ?>"><?php print mw()->format->ago($item['updated_at']); ?></div>
        </div>
        <div class="clearfix"></div>


        <div class="current-page-row">
            <div class="current-page">
                <?php
                if (isset($item['title'])) {
                    echo $item['title'];
                } else {
                    echo 'Undefined';
                }
                ?>
                <div class="tip pull-right mx-1" data-tip="Browser: <?php print $item['browser_name']; ?>">
                    <i class="fab fa-<?php print $browser; ?>"></i>
                </div>
                <div class="tip pull-right mx-1" data-tip="OS: <?php print $item['browser_os']; ?>">
                    <i class="fab fa-<?php print $os; ?>"></i>
                </div>
            </div>
        </div>

        <div class="mw-ui-row">
            <div class="mw-ui-col" style="width:30px;">
                <div class="referral">
                    <img src="<?php print modules_url() ?>site_stats/icon_ref_direct_16x16.png" class="referral_icon" title="Referred by <?php print $item['referrer_id']; ?>">
                </div>
            </div>

            <div class=" mw-ui-col">
                <ul class="page-dots">
                    <?php foreach ($item['views_data'] as $view): ?>
                        <li class="page-circle" style="background-color:rgba(0,0,0,0.3)"><a href="<?php print $view['url'] ?>" class="visitor-url" title="<?php print $view['url'] ?>" rel="noreferrer noopener"></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <div class="more-info" id="more-<?php print $item['id']; ?>">
            <?php foreach ($item['views_data'] as $view): ?>
                <a href="<?php print $view['url']; ?>" target="_blank" rel="noreferrer noopener">
                    <div class="page-visited">
                        <div class="page-title"><?php print $view['title']; ?>
                            <div class="pull-right"><?php print mw()->format->ago($view['updated_at']); ?></div>
                        </div>
                        <div class="page-url"><?php print $view['url']; ?></div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
<?php endforeach; ?>
