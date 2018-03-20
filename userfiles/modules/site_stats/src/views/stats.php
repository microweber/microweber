<script>
    mw.lib.require('flag_icons');

    $(document).ready(function () {
        $('.sources .source-title').on('click', function () {
            $(this).parent().toggleClass('active');
            $(this).find('id').toggleClass('active');
        });
    });
</script>


<div class="stats-view">
    <div class="mw-ui-col">
        <div class="sources mw-ui-box">
            <ul class="">
                <?php include('parts/sources.php'); ?>
                <?php include('parts/sources.php'); ?>
                <?php include('parts/sources.php'); ?>
                <?php include('parts/sources.php'); ?>
                <?php include('parts/sources.php'); ?>
            </ul>
        </div>
    </div>

    <div class="mw-ui-col">
        <div class="contents mw-ui-box">
            <?php include('parts/contents.php'); ?>
            <?php include('parts/contents.php'); ?>
            <?php include('parts/contents.php'); ?>
        </div>
    </div>

    <div class="mw-ui-col">
        <div class="visitors mw-ui-box">
            <?php include('parts/visitors.php'); ?>
            <?php include('parts/visitors.php'); ?>
            <?php include('parts/visitors.php'); ?>
        </div>
    </div>
</div>
