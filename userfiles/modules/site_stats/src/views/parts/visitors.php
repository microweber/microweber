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

    <div class="card hover-bg-light item visitor more-info-show shadow-sm p-3 my-4" data-id="more-<?php print $item['id']; ?>">
        <div class="d-flex align-items-center justify-content-between my-3">
              <span
                    <?php
                    if (strtolower($item['country_code']) == "unknown") {
                        print "class='mdi mdi-earth tip'";
                    }  else {
                    ?> class='flag-icon flag-icon-<?php print strtolower($item['country_code'] . " " . "tip");
                  } ?>'>
              </span>

              <div class="visitor-name text-start text-left">
                  <?php print $item['user_ip']; ?>
                  <a rel="noreferrer noopener" href="https://extreme-ip-lookup.com/<?php print $item['user_ip']; ?>" class="text-muted btn btn-link btn-rounded btn-icon btn-sm"  target="_blank"><i class="mdi  mdi-open-in-new"></i></a>
              </div>

            <div class="timestamp tip" data-tip="<?php print $item['updated_at']; ?>"><?php print mw()->format->ago($item['updated_at']); ?></div>
        </div>
        <div class="clearfix"></div>

        <div class="py-1">
            <div class="current-page">
                <?php
                if (isset($item['title'])) {
                    echo $item['title'];
                } else {
                    echo 'Undefined';
                }
                ?>
                <div class="tip pull-right mx-1" data-tip="Browser: <?php print $item['browser_name']; ?>">
                    <i class="fa fa-<?php print $browser; ?>"></i>
                </div>
                <div class="tip pull-right mx-1" data-tip="OS: <?php print $item['browser_os']; ?>">
                    <i class="fa fa-<?php print $os; ?>"></i>
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center flex-wrap py-1">
            <div class="col-lg-3 col-md-6 col-12" style="width:30px;">
                <div class="referral">
                    <img src="<?php print modules_url() ?>site_stats/icon_ref_direct_16x16.png" class="referral_icon" title="Referred by <?php print $item['referrer_id']; ?>">
                </div>
            </div>

            <div class="col-lg-9 col-md-6 col-12">
                <ul class="page-dots my-0">
                    <?php foreach ($item['views_data'] as $view): ?>
                        <li class="page-circle" style="background-color:rgba(0,0,0,0.3)"><a href="<?php print $view['url'] ?>" class="visitor-url" title="<?php print $view['url'] ?>" rel="noreferrer noopener"></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <div class="more-info my-2" id="more-<?php print $item['id']; ?>">
            <?php foreach ($item['views_data'] as $view): ?>
                <a href="<?php print $view['url']; ?>" target="_blank" rel="noreferrer noopener">
                    <div class="page-visited">
                        <div class="d-flex justify-content-between align-items-center">
                            <label  class="form-label"><?php print $view['title']; ?></label>
                            <label class="form-label"><?php print mw()->format->ago($view['updated_at']); ?></label>
                        </div>
                        <div class="page-url"><?php print $view['url']; ?></div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
<?php endforeach; ?>
