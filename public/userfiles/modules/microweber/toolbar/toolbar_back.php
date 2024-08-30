


<style>
    #mw-site-preview-navigation{
        position: fixed;
        top: 0;
        transform: translateX(-50%);
        left: 50%;
        z-index: 999;
        color: #0a0a0a  !important;
    }
    #mw-site-preview-navigation, #mw-site-preview-navigation a {
        font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
        color: #0a0a0a  !important;
    }

    @media screen and (max-width: 767px) {

       .mw-live-edit-back-to-editmode-link {
            display: none;
        }
    }

</style>

<script>

    mw.require('mai.css')
    mw.require('components.css')

</script>

<div class="mw-ui-btn-nav d-flex align-items-center " id="mw-site-preview-navigation">




    <a href="<?php echo route('admin.content.index'); ?>" id="mw_back_to_admin" class="mw-ui-btn">
        <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="M480.078 729.333q72.255 0 122.755-50.578 50.5-50.579 50.5-122.833 0-72.255-50.578-122.755-50.579-50.5-122.833-50.5-72.255 0-122.755 50.578-50.5 50.579-50.5 122.833 0 72.255 50.578 122.755 50.579 50.5 122.833 50.5Zm-.235-62.666q-46.176 0-78.343-32.324-32.167-32.323-32.167-78.5 0-46.176 32.324-78.343 32.323-32.167 78.5-32.167 46.176 0 78.343 32.324 32.167 32.323 32.167 78.5 0 46.176-32.324 78.343-32.323 32.167-78.5 32.167ZM480 856q-146 0-264.667-82.5Q96.667 691 40 556q56.667-135 175.333-217.5Q334 256 480 256q146 0 264.667 82.5Q863.333 421 920 556q-56.667 135-175.333 217.5Q626 856 480 856Zm0-300Zm-.112 233.334q118.445 0 217.612-63.5Q796.667 662.333 848.667 556q-52-106.333-151.054-169.834-99.055-63.5-217.501-63.5-118.445 0-217.612 63.5Q163.333 449.667 110.666 556q52.667 106.333 151.721 169.834 99.055 63.5 217.501 63.5Z"/></svg>

        <span class="mw-live-edit-back-to-editmode-link">




        <?php _e("Admin"); ?>

    </span>
    </a>


    <a id="mw_back_to_live_edit" href="<?php
    if (defined('CONTENT_ID') and CONTENT_ID != 0) {
        $u = app()->content_manager->link(CONTENT_ID);
    } else {
        $u = mw()->url_manager->current(1, 1);
    }

    print $u ?>?editmode=iframe" class="mw-ui-btn">
        <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" width="24">
            <path
                d="M181.674-179.761h41.13l441.087-441.565-41.13-41.13-441.087 441.565v41.13Zm613.043-484.326L665.761-793.043l36.978-37.218q19.631-19.63 47.859-19.75 28.228-.119 47.859 19.272l37.782 37.782q18.435 18.196 17.837 44.153-.598 25.956-18.315 43.674l-41.044 41.043Zm-41.76 41.761L247.761-117.13H118.804v-128.957l504.957-504.956 129.196 128.717Zm-109.392-19.565-20.804-20.565 41.13 41.13-20.326-20.565Z"></path>
        </svg>

        <span class="mw-live-edit-back-to-editmode-link">

      &nbsp;<?php _e("Live Edit"); ?>
</span>

    </a>

</div>

